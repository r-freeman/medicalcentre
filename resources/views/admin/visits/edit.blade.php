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
                        <form method="POST" action="{{ route('admin.visits.update', $visit->id) }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                       value="{{ old('date', $visit->date) }}">
                            </div>
                            <div class="form-group">
                                <label for="time">Time</label>
                                <input type="time" class="form-control" id="time" name="time"
                                       value="{{ old('time', $visit->time) }}">
                            </div>
                            <div class="form-group">
                                <label for="duration">Duration</label>
                                <input type="duration" class="form-control" id="duration" name="duration"
                                       value="{{ old('duration', $visit->duration) }}" placeholder="Minutes">
                            </div>
                            <div class="form-group">
                                <label for="patient_id">Patient {{ old('patient_id') }}</label>
                                <select class="form-control" id="patient_id" name="patient_id"
                                    {{-- If patient was soft deleted, disable the select field and leave original patient_id --}}
                                    @wastrashed($visit->patient_id) disabled="disabled">
                                        <option value="{{ $visit->patient_id }}">{{ $visit->patient_name }}</option>
                                        <input type="hidden" id="patient_id" name="patient_id" value="{{ $visit->patient_id }}">
                                    @else
                                        >
                                        @foreach($patients as $patient)
                                            <option
                                                value="{{ $patient->id }}"
                                                @if(old('patient_id') == $patient->id)
                                                    selected
                                                @elseif($visit->patient_id == $patient->id)
                                                    selected
                                                @endif
                                            >{{ $patient->name }}</option>
                                        @endforeach
                                    @endwastrashed
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="doctor_id">Doctor</label>
                                <select class="form-control" id="doctor_id" name="doctor_id"
                                    {{-- If doctor was soft deleted, disable the select field and leave original doctor_id --}}
                                    @wastrashed($visit->doctor_id) disabled="disabled">
                                        <option value="{{ $visit->doctor_id }}">{{ $visit->doctor_name }}</option>
                                        <input type="hidden" id="doctor_id" name="doctor_id" value="{{ $visit->doctor_id }}">
                                    @else
                                        >
                                        @foreach($doctors as $doctor)
                                            <option
                                                value="{{ $doctor->id }}"
                                                @if(old('doctor_id') == $doctor->id)
                                                selected
                                                @elseif($visit->doctor_id == $doctor->id)
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
                                       value="{{ old('cost', $visit->cost) }}">
                            </div>
                            <button class="btn btn-outline-success" type="submit">Update</button>
                            <a href="{{ route('admin.visits.index') }}" class="btn btn-link float-right">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
