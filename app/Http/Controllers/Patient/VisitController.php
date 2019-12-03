<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:patient');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patientVisits = Auth::user()->patientVisits()->paginate(5);

        foreach ($patientVisits as $patientVisit) {
            // using withTrashed because visit might reference a soft deleted doctor
            $doctor = User::withTrashed()->find($patientVisit->doctor_id);

            // add doctor name to each visit to display in template
            $patientVisit->addAttributes([
                'doctor_name' => $doctor->name,
            ]);
        }

        return view('patient.visits.index')
            ->with(['patientVisits' => $patientVisits]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patientVisit = Auth::user()->patientVisits()->findOrFail($id);

        // using withTrashed because visit might reference a soft deleted doctor
        $doctor = User::withTrashed()->find($patientVisit->doctor_id);

        // add doctor name to visit to display in template
        $patientVisit->addAttributes([
            'doctor_name' => $doctor->name
        ]);

        return view('patient.visits.show')
            ->with(['patientVisit' => $patientVisit]);
    }

    /**
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patientVisit = Auth::user()->patientVisits()->findOrFail($id);
        $patientVisit->delete();

        return redirect()->route('patient.visits.index')
            ->with('danger', 'Visit was cancelled!');
    }
}
