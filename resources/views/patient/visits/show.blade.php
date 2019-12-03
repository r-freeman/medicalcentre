@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Visit
                    </div>
                    <div class="card-body">
                        <table class="table table-hover" id="visits-table">
                            <tbody>
                            <tr>
                                <td>Date</td>
                                <td>{{ $patientVisit->date }}</td>
                            </tr>
                            <tr>
                                <td>Time</td>
                                <td>{{ $patientVisit->time }}</td>
                            </tr>
                            <tr>
                                <td>Duration</td>
                                <td>{{ $patientVisit->duration }}</td>
                            </tr>
                            <tr>
                                <td>Doctor</td>
                                <td>{{ $patientVisit->doctor_name }}</td>
                            </tr>
                            <tr>
                                <td>Cost</td>
                                <td>{{ $patientVisit->cost }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="btn-group-md" role="group" aria-label="Basic example">
                            <a href="{{ route('patient.visits.edit', $patientVisit->id) }}" class="btn btn-outline-success">Edit</a>
                            <form action="{{ route('patient.visits.destroy', $patientVisit->id) }}"
                                  style="display: inline-block" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="form-control btn btn-outline-danger"
                                        onclick="return confirm('Cancel this visit?')">Cancel
                                </button>
                            </form>
                            <a href="{{ route('patient.visits.index') }}" onclick="window.history.go(-1); return false;" class="btn btn-outline">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
