<?php

namespace App\Http\Controllers\Doctor;

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
        $this->middleware('role:doctor');
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
        $doctorVisits = Auth::user()->doctorVisits()->paginate(5);

        foreach ($doctorVisits as $visit) {
            // using withTrashed because visit might reference a soft deleted patient
            $patient = User::withTrashed()->find($visit->patient_id);

            // add patient name to each visit to display in template
            $visit->addAttributes([
                'patient_name' => $patient->name,
            ]);
        }

        return view('doctor.visits.index')
            ->with(['doctorVisits' => $doctorVisits]);
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

        return view('doctor.visits.create')
            ->with(['patients' => $patients]);
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
        if (!$patient->hasRole('patient')) {
            // invalidate the patient_id if user isn't a patient
            $request->merge(['patient_id' => null]);
        }

        // doctor should be the authenticated user
        $doctor = Auth::user();

        // make sure user has doctor role and doctor id matches the doctor id in form
        if (!$doctor->hasRole('doctor') && $doctor->id !== $request->input('doctor_id')) {
            // invalidate the doctor_id if user isn't a doctor
            $request->merge(['doctor_id' => null]);
        }

        // create a new doctor visit
        $doctorVisit = new Visit();
        $doctorVisit->date = $request->input('date');
        $doctorVisit->time = $request->input('time');
        $doctorVisit->duration = $request->input('duration');
        $doctorVisit->patient_id = $request->input('patient_id');
        $doctorVisit->doctor_id = $request->input('doctor_id');
        $doctorVisit->cost = $request->input('cost');

        // validate the user input
        $request->validate($this->validatorRules($patient->id, $doctor->id, true));

        // save the visit
        $doctorVisit->save();

        return redirect()->route('doctor.visits.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doctorVisit = Auth::user()->doctorVisits()->findOrFail($id);

        // using withTrashed because visit might reference a soft deleted patient
        $patient = User::withTrashed()->find($doctorVisit->patient_id);

        // add patient and doctor names to visit to display in template
        $doctorVisit->addAttributes([
            'patient_name' => $patient->name
        ]);

        return view('doctor.visits.show')
            ->with(['doctorVisit' => $doctorVisit]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // get the doctor visit
        $doctorVisit = Auth::user()->doctorVisits()->findOrFail($id);

        // find the patient and include soft deleted patients
        $patient = User::withTrashed()->find($doctorVisit->patient_id);

        // add patient name to visit
        $doctorVisit->addAttributes([
            'patient_name' => $patient->name
        ]);

        // get all users with patient role
        $patients = User::whereHas('roles', function ($q) {
            $q->where('name', 'patient');
        })->get();

        return view('doctor.visits.edit')
            ->with([
                'doctorVisit' => $doctorVisit,
                'patients' => $patients
            ]);
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
        // find the patient
        $patient = User::withTrashed()->find($request->input('patient_id'));

        // make sure user has patient role
        if (!$patient->hasRole('patient')) {
            // invalidate the patient_id
            $request->merge(['patient_id' => null]);
        }

        // doctor should be the authenticated user
        $doctor = Auth::user();

        // make sure user has doctor role and doctor id matches the doctor id in form
        if (!$doctor->hasRole('doctor') && $doctor->id !== $request->input('doctor_id')) {
            // invalidate the doctor_id
            $request->merge(['doctor_id' => null]);
        }

        // find the doctor visit
        $doctorVisit = $doctor->doctorVisits()->findOrFail($id);

        $doctorVisit->date = $request->input('date');
        $doctorVisit->time = $request->input('time');
        $doctorVisit->duration = $request->input('duration');
        $doctorVisit->patient_id = $request->input('patient_id');
        $doctorVisit->doctor_id = $request->input('doctor_id');
        $doctorVisit->cost = $request->input('cost');

        // validate the user input
        $request->validate($this->validatorRules($patient->id, $doctor->id, false));

        // save the visit
        $doctorVisit->save();

        return redirect()->route('admin.visits.index');
    }

    /**
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doctorVisit = Auth::user()->doctorVisits()->findOrFail($id);
        $doctorVisit->delete();

        return redirect()->route('doctor.visits.index');
    }
}
