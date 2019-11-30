<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    private $role;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:doctor');
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $doctor = User::findOrFail($id);

        // dont allow this doctor to update another
        if($doctor->id !== Auth::user()->id) {
            // send user back home
            return redirect()->route('home');
        }

        $doctor->addAttributesFromPivot($this->role, ['start_date']);

        return view('doctor.edit')
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

        if ($request->input('password') !== null) {
            $doctor->password = Hash::make($request->input('password'));
        }

        // save updated attributes on the role_data pivot table
        $doctor->updatePivotAttributes($this->role, [
            'start_date' => $request->input('start_date')
        ]);

        $doctor->save();

        return redirect()->route('doctor.home');
    }
}
