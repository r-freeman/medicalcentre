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
                        <form method="POST" action="{{ route('doctor.visits.update', $doctorVisit->id) }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="doctor_id" name="doctor_id" value=" {{ Auth::user()->id }}">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                       value="{{ old('date', $doctorVisit->date) }}">
                            </div>
                            <div class="form-group">
                                <label for="time">Time</label>
                                <input type="time" class="form-control" id="time" name="time"
                                       value="{{ old('time', $doctorVisit->time) }}">
                            </div>
                            <div class="form-group">
                                <label for="duration">Duration</label>
                                <input type="duration" class="form-control" id="duration" name="duration"
                                       value="{{ old('duration', $doctorVisit->duration) }}" placeholder="Minutes">
                            </div>
                            <div class="form-group">
                                <label for="patient_id">Patient {{ old('patient_id') }}</label>
                                <select class="form-control" id="patient_id" name="patient_id"
                                    @wastrashed($doctorVisit->patient_id) disabled="disabled">
                                        <option value="{{ $doctorVisit->patient_id }}">{{ $doctorVisit->patient_name }}</option>
                                        <input type="hidden" id="patient_id" name="patient_id" value="{{ $doctorVisit->patient_id }}">
                                    @else
                                        >
                                        @foreach($patients as $patient)
                                            <option
                                                value="{{ $patient->id }}"
                                                @if(old('patient_id') == $patient->id)
                                                    selected
                                                @elseif($doctorVisit->patient_id == $patient->id)
                                                    selected
                                                @endif
                                            >{{ $patient->name }}</option>
                                        @endforeach
                                    @endwastrashed
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cost">Cost</label>
                                <input type="text" class="form-control" id="cost" name="cost"
                                       value="{{ old('cost', $doctorVisit->cost) }}">
                            </div>
                            <button class="btn btn-outline-success" type="submit">Update</button>
                            <a href="{{ route('doctor.visits.index') }}" onclick="window.history.go(-1); return false;" class="btn btn-link float-right">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
