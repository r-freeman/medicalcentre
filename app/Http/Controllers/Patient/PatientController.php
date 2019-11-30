<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    private $role;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:patient');
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = User::findOrFail($id);

        // dont allow this patient to update another
        if($patient->id !== Auth::user()->id) {
            // send user back home
            return redirect()->route('home');
        }

        $patient->addAttributesFromPivot($this->role, [
            'insured',
            'policy_no'
        ]);

        return view('patient.edit')
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

        return redirect()->route('home');
    }
}
