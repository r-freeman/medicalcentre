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
                                <td>{{ $doctorVisit->date }}</td>
                            </tr>
                            <tr>
                                <td>Time</td>
                                <td>{{ $doctorVisit->time }}</td>
                            </tr>
                            <tr>
                                <td>Duration</td>
                                <td>{{ $doctorVisit->duration }}</td>
                            </tr>
                            <tr>
                                <td>Patient</td>
                                <td>{{ $doctorVisit->patient_name }}</td>
                            </tr>
                            <tr>
                                <td>Cost</td>
                                <td>{{ $doctorVisit->cost }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="btn-group-md" role="group" aria-label="Basic example">
                            <a href="{{ route('doctor.visits.edit', $doctorVisit->id) }}" class="btn btn-outline-success">Edit</a>
                            <form action="{{ route('doctor.visits.destroy', $doctorVisit->id) }}"
                                  style="display: inline-block" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="form-control btn btn-outline-danger"
                                        onclick="return confirm('Cancel this visit?')">Cancel
                                </button>
                            </form>
                            <a href="{{ route('doctor.visits.index') }}" onclick="window.history.go(-1); return false;" class="btn btn-outline">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
