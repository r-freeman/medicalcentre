<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $role;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:doctor');
        $this->role = 'doctor';
    }

    public function index()
    {
        // get the authenticated user
        $doctor = Auth::user();

        // add start_date attribute from role_data pivot table
        $doctor->addAttributesFromPivot($this->role, [
            'start_date'
        ]);

        $doctorVisits = $doctor->doctorVisits()->get();

        // we want to include the patient name for each doctor visit
        foreach ($doctorVisits as $doctorVisit) {
            // add patient name to each doctor visit (including soft deleted patients)
            $doctorVisit->addAttributes(['patient_name' => User::withTrashed()->find($doctorVisit->patient_id)->name]);
        }

        return view('doctor.home')
            ->with([
                'doctor' => $doctor,
                'doctorVisits' => $doctorVisits
            ]);
    }
}
