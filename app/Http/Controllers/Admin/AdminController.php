<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private $role;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->role = 'admin';
    }

    /**
     * Define validator rules for store and update methods
     *
     * @param $id
     * @param $passwordRequired
     * @param $insurance
     * @return array
     */
    protected function validatorRules($id, $passwordRequired = false)
    {
        return [
            'name' => 'required|max:191',
            'address' => 'required|max:191',
            'phone' => 'required|max:191',
            'email' => 'required|max:191|unique:users,email,' . $id,
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
        $admin = User::findOrFail($id);

        // dont allow this admin to update another
        if($admin->id !== Auth::user()->id) {
            // send user back home
            return redirect()->route('home');
        }

        return view('admin.edit')
            ->with([$this->role => $admin]);
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
        $admin = User::findOrFail($id);

        $admin->name = $request->input('name');
        $admin->address = $request->input('address');
        $admin->phone = $request->input('phone');
        $admin->email = $request->input('email');

        if ($request->input('password') !== null) {
            $admin->password = Hash::make($request->input('password'));
        }

        $request->validate($this->validatorRules($admin->id, false));

        $admin->save();

        return redirect()->route('home');
    }
}
