<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    private $role;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->role = 'patient';
    }

    /**
     * Define validator rules for store and update methods
     *
     * @param $id
     * @param $passwordRequired
     * @param $insurance
     * @return array
     */
    protected function validatorRules($id, $passwordRequired = false, $insurance = false)
    {
        return [
            'name' => 'required|max:191',
            'address' => 'required|max:191',
            'phone' => 'required|max:191',
            'email' => 'required|max:191|unique:users,email,' . $id,
            'insurance' => 'numeric|max:1',
            'policy_no' => $insurance ? 'required|max:10' : '',
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
        // return collection of users with patient role
        $patients = User::whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->get();

        foreach ($patients as $patient) {
            // add attributes from the role_data pivot table to each patient instance
            $patient->addAttributesFromPivot($this->role, ['insured', 'policy_no']);
        }

        return view('admin.patients.index')
            ->with(['patients' => $patients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $patient = new User();
        $patient->name = $request->input('name');
        $patient->address = $request->input('address');
        $patient->phone = $request->input('phone');
        $patient->email = $request->input('email');
        $patient->password = Hash::make($request->input('password'));

        // cast insured string to int
        $insured = (int)$request->input('insured');

        // validate user input
        $request->validate($this->validatorRules('', true, $insured ? true : false));

        // save the user
        $patient->save();

        // attach patient role and save insured, policy_no attributes in role_data pivot
        $patient->roles()->attach(Role::where('name', $this->role)->first(), [
            'insured' => $insured,
            'policy_no' => $insured ? $request->input('policy_no') : null
        ]);

        return redirect()->route('admin.patients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patient = User::findOrFail($id);

        // add insured and policy_no attributes from the role_data pivot table to the patient
        $patient->addAttributesFromPivot($this->role, [
            'insured',
            'policy_no'
        ]);

        return view('admin.patients.show')
            ->with([$this->role => $patient]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = User::findOrFail($id);

        $patient->addAttributesFromPivot($this->role, [
            'insured',
            'policy_no'
        ]);

        return view('admin.patients.edit')
            ->with([$this->role => $patient]);
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
        $patient = User::findOrFail($id);

        $patient->name = $request->input('name');
        $patient->address = $request->input('address');
        $patient->phone = $request->input('phone');
        $patient->email = $request->input('email');

        if ($request->input('password') !== null) {
            $patient->password = Hash::make($request->input('password'));
        }

        // cast insured string to int
        $insured = (int)$request->input('insured');

        $request->validate($this->validatorRules($patient->id, false, $insured ? true : false));

        // save updated attributes on the role_data pivot table
        $patient->updatePivotAttributes($this->role, [
            'insured' => $insured,
            'policy_no' => $insured ? $request->input('policy_no') : null
        ]);

        $patient->save();

        return redirect()->route('admin.patients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
