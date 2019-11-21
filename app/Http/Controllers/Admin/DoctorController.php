<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    private $role;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->role = 'doctor';
    }

    /**
     * Define validator rules for store and update methods
     *
     * @param $id
     * @param $passwordRequired
     * @return array
     */
    protected function validatorRules($id, $passwordRequired)
    {
        return [
            'name' => 'required|max:191',
            'address' => 'required|max:191',
            'phone' => 'required|max:191',
            'email' => 'required|max:191|unique:users,email,' . $id,
            'start_date' => 'required|date',
            'password' => $passwordRequired ? 'required|min:8|confirmed' : 'confirmed'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return collection of users with doctor role
        $doctors = User::whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->get();

        foreach ($doctors as $doctor) {
            // add attributes from the role_data pivot table to each doctor instance
            $doctor->addAttributesFromPivot($this->role, ['start_date']);
        }

        return view('admin.doctors.index')
            ->with(['doctors' => $doctors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validatorRules('', true));

        $doctor = new User();
        $doctor->name = $request->input('name');
        $doctor->address = $request->input('address');
        $doctor->phone = $request->input('phone');
        $doctor->email = $request->input('email');
        $doctor->password = Hash::make($request->input('password'));

        // save the user first
        $doctor->save();

        // attach doctor role and save start_date attribute in role_data pivot
        $doctor->roles()->attach(Role::where('name', $this->role)->first(), [
            'start_date' => $request->input('start_date'),
        ]);

        return redirect()->route('admin.doctors.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doctor = User::findOrFail($id);

        // add start_date attribute from the role_data pivot table to this user
        $doctor->addAttributesFromPivot($this->role, ['start_date']);

        return view('admin.doctors.show')
            ->with([$this->role => $doctor]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $doctor = User::findOrFail($id);

        $doctor->addAttributesFromPivot($this->role, ['start_date']);

        return view('admin.doctors.edit')
            ->with([$this->role => $doctor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $doctor = User::findOrFail($id);

        $request->validate($this->validatorRules($doctor->id, false));

        $doctor->name = $request->input('name');
        $doctor->address = $request->input('address');
        $doctor->phone = $request->input('phone');
        $doctor->email = $request->input('email');

        if($request->input('password') !== null) {
            $doctor->password = Hash::make($request->input('password'));
        }

        // save updated attributes on the role_data pivot table
        $doctor->updatePivotAttributes($this->role, [
            'start_date' => $request->input('start_date')
        ]);

        $doctor->save();

        return redirect()->route('admin.doctors.index');
    }

    /**
     * Doctor is soft deleted since we still need to refer to them in visits
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doctor = User::findOrFail($id);

        // Soft delete doctor
        $doctor->delete();

        return redirect()->route('admin.doctors.index');
    }
}
