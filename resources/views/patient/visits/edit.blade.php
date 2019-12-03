@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="card-header">Edit Visit</div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('patient.visits.update', $patientVisit->id) }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="patient_id" name="patient_id" value=" {{ Auth::user()->id }}">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                       value="{{ old('date', $patientVisit->date) }}">
                            </div>
                            <div class="form-group">
                                <label for="time">Time</label>
                                <input type="time" class="form-control" id="time" name="time"
                                       value="{{ old('time', $patientVisit->time) }}">
                            </div>
                            <div class="form-group">
                                <label for="duration">Duration</label>
                                <input type="duration" class="form-control" id="duration" name="duration"
                                       value="{{ old('duration', $patientVisit->duration) }}" placeholder="Minutes">
                            </div>
                            <div class="form-group">
                                <label for="doctor_id">Doctor {{ old('doctor_id') }}</label>
                                <select class="form-control" id="doctor_id" name="doctor_id"
                                    {{-- If patient was soft deleted, disable the select field and leave original patient_id --}}
                                    @wastrashed($patientVisit->doctor_id) disabled="disabled">
                                        <option value="{{ $patientVisit->doctor_id }}">{{ $patientVisit->doctor_name }}</option>
                                        <input type="hidden" id="patient_id" name="patient_id" value="{{ $patientVisit->doctor_id }}">
                                    @else
                                        >
                                        @foreach($doctors as $doctor)
                                            <option
                                                value="{{ $doctor->id }}"
                                                @if(old('doctor_id') == $doctor->id)
                                                    selected
                                                @elseif($patientVisit->doctor_id == $doctor->id)
                                                    selected
                                                @endif
                                            >{{ $doctor->name }}</option>
                                        @endforeach
                                    @endwastrashed
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cost">Cost</label>
                                <input type="text" class="form-control" id="cost" name="cost"
                                       value="{{ old('cost', $patientVisit->cost) }}">
                            </div>
                            <button class="btn btn-outline-success" type="submit">Update</button>
                            <a href="{{ route('patient.visits.index') }}" class="btn btn-link float-right">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
