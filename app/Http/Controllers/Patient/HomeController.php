<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $role;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:patient');
        $this->role = 'patient';
    }

    public function index()
    {
        // get the authenticated user
        $patient = Auth::user();

        // add insured and policy_no attribute from role_data pivot table
        $patient->addAttributesFromPivot($this->role, [
            'insured',
            'policy_no'
        ]);

        return view('patient.home')
            ->with([$this->role => $patient]);
    }
}
