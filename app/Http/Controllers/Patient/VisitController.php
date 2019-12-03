<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\User;
use App\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:patient');
    }

    /**
     * Define validator rules for store and update methods
     *
     * @param $patient_id
     * @param $doctor_id
     * @param $create
     * @return array
     */
    protected function validatorRules($patient_id, $doctor_id, $create)
    {
        return [
            'date' => 'required|date',
            'time' => $create ? 'required|date_format:H:i' : 'required|date_format:H:i:s',
            'duration' => 'required|numeric',
            // make sure patient_id !== doctor_id
            'patient_id' => [
                'required',
                'integer',
                Rule::notIn([$doctor_id])
            ],
            // make sure doctor_id !== patient_id
            'doctor_id' => [
                'required',
                'integer',
                Rule::notIn([$patient_id])
            ],
            'cost' => 'required|numeric'
        ];
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get all patients
        $doctors = User::whereHas('roles', function ($q) {
            $q->where('name', 'doctor');
        })->get();

        return view('patient.visits.create')
            ->with(['doctors' => $doctors]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // find the doctor
        $doctor = User::find($request->input('doctor_id'));

        // make sure user has doctor role
        if (!$doctor->hasRole('doctor')) {
            // invalidate the doctor_id if user isn't a doctor
            $request->merge(['doctor_id' => null]);
        }

        // patient should be the authenticated user
        $patient = Auth::user();

        // make sure user has patient role and patient id matches the patient id in form
        if (!$patient->hasRole('patient') && $patient->id !== $request->input('patient_id')) {
            // invalidate the patient_id if user isn't a patient
            $request->merge(['patient_id' => null]);
        }

        // create a new doctor visit
        $patientVisit = new Visit();
        $patientVisit->date = $request->input('date');
        $patientVisit->time = $request->input('time');
        $patientVisit->duration = $request->input('duration');
        $patientVisit->patient_id = $request->input('patient_id');
        $patientVisit->doctor_id = $request->input('doctor_id');
        $patientVisit->cost = $request->input('cost');

        // validate the user input
        $request->validate($this->validatorRules($patient->id, $doctor->id, true));

        // save the visit
        $patientVisit->save();

        return redirect()->route('patient.visits.index')
            ->with('success', 'Success! Visit was created.');
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
