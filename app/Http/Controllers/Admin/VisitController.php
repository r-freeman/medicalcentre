<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Visit;
use Illuminate\Http\Request;

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
     * @param $id
     * @param $passwordRequired
     * @return array
     */
    protected function validatorRules($id, $passwordRequired)
    {
        return [

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }
}
