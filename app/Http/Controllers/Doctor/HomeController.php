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
        // only admin and doctors can access doctor area
        $this->middleware('role:admin,doctor');
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

        $doctorVisits = [];

        // we want to include the patient name for each doctor visit
        foreach ($doctor->doctorVisits as $doctorVisit) {
            // add patient name to each doctor visit (including soft deleted patients)
            $doctorVisit->addAttributes(['patient_name' => User::withTrashed()->find($doctorVisit->patient_id)->name]);

            // push doctorVisit into doctorVisits array
            array_push($doctorVisits, $doctorVisit);
        }

        return view('doctor.home')
            ->with([
                'doctor' => $doctor,
                'doctorVisits' => $doctorVisits
            ]);
    }
}
