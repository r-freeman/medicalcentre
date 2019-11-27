<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Visit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Define validator rules for store and update methods
     *
     * @param $patient_id
     * @param $doctor_id
     * @return array
     */
    protected function validatorRules($patient_id, $doctor_id)
    {
        return [
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'duration' => 'required|numeric',
            // make sure patient_id !== doctor_id
            'patient_id' => [
                'required',
                'integer',
                Rule::notIn([$doctor_id])
            ],
            // make sure doctor id !== patient_id
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
        $visits = Visit::all();

        foreach ($visits as $visit) {
            // using withTrashed because visit might reference a soft deleted patient
            $patient = User::withTrashed()->find($visit->patient_id);

            // using withTrashed because visit might reference a soft deleted doctor
            $doctor = User::withTrashed()->find($visit->doctor_id);

            // add patient and doctor names to each visit to display in template
            $visit->addAttributes([
                'patient_name' => $patient->name,
                'doctor_name' => $doctor->name
            ]);
        }

        return view('admin.visits.index')
            ->with(['visits' => $visits]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get all patients
        $patients = User::whereHas('roles', function ($q) {
            $q->where('name', 'patient');
        })->get();

        // get all doctors
        $doctors = User::whereHas('roles', function ($q) {
            $q->where('name', 'doctor');
        })->get();

        return view('admin.visits.create')
            ->with([
                'patients' => $patients,
                'doctors' => $doctors
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // find the patient
        $patient = User::find($request->input('patient_id'));

        // make sure user has patient role
        if(!$patient->hasRole('patient')) {
            // invalidate the patient_id if user isn't a patient
            $request->merge(['patient_id' => null]);
        }

        // find the doctor
        $doctor = User::find($request->input('doctor_id'));

        // make sure user has doctor role
        if(!$doctor->hasRole('doctor')) {
            // invalidate the doctor_id if user isn't a doctor
            $request->merge(['doctor_id' => null]);
        }

        // create a new visit
        $visit = new Visit();
        $visit->date = $request->input('date');
        $visit->time = $request->input('time');
        $visit->duration = $request->input('duration');
        $visit->patient_id = $request->input('patient_id');
        $visit->doctor_id = $request->input('doctor_id');
        $visit->cost = $request->input('cost');

        // validate the user input
        $request->validate($this->validatorRules($patient->id, $doctor->id));

        // save the visit
        $visit->save();

        return redirect()->route('admin.visits.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $visit = Visit::findOrFail($id);

        // using withTrashed because visit might reference a soft deleted patient
        $patient = User::withTrashed()->find($visit->patient_id);

        // using withTrashed because visit might reference a soft deleted doctor
        $doctor = User::withTrashed()->find($visit->doctor_id);

        // add patient and doctor names to visit to display in template
        $visit->addAttributes([
            'patient_name' => $patient->name,
            'doctor_name' => $doctor->name
        ]);

        return view('admin.visits.show')
            ->with(['visit' => $visit]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $visit = Visit::findOrFail($id);

        $visit->delete();

        return redirect()->route('admin.visits.index');
    }
}
