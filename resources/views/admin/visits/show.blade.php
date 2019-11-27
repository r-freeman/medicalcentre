@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        View visit
                    </div>
                    <div class="card-body">
                        <table class="table table-hover" id="visits-table">
                            <tbody>
                            <tr>
                                <td>Date</td>
                                <td>{{ $visit->date }}</td>
                            </tr>
                            <tr>
                                <td>Time</td>
                                <td>{{ $visit->time }}</td>
                            </tr>
                            <tr>
                                <td>Duration</td>
                                <td>{{ $visit->duration }}</td>
                            </tr>
                            <tr>
                                <td>Patient</td>
                                <td>
                                    @wastrashed($visit->patient_id)
                                    {{ $visit->patient_name }}
                                    @else
                                        <a href="{{ route('admin.patients.show', $visit->patient_id) }}">{{ $visit->patient_name }}</a>
                                    @endwastrashed
                                </td>
                            </tr>
                            <tr>
                                <td>Doctor</td>
                                <td>
                                    @wastrashed($visit->doctor_id)
                                    {{ $visit->doctor_name }}
                                    @else
                                        <a href="{{ route('admin.doctors.show', $visit->doctor_id) }}">{{ $visit->doctor_name }}</a>
                                    @endwastrashed
                                </td>
                            </tr>
                            <tr>
                                <td>Cost</td>
                                <td>{{ $visit->cost }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="btn-group-md" role="group" aria-label="Basic example">
                            <a href="{{ route('admin.visits.edit', $visit->id) }}" class="btn btn-outline-success">Edit</a>
                            <form action="{{ route('admin.visits.destroy', $visit->id) }}"
                                  style="display: inline-block" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="form-control btn btn-outline-danger"
                                        onclick="return confirm('Delete this visit?')">Delete
                                </button>
                            </form>
                            <a href="{{ route('admin.visits.index') }}" onclick="window.history.go(-1); return false;" class="btn btn-outline">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
