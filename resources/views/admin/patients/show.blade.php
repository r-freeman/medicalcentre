@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Patient
                    </div>
                    <div class="card-body">
                        <table class="table table-hover" id="patients-table">
                            <tbody>
                            <tr>
                                <td>Name</td>
                                <td>{{ $patient->name }}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>{{ $patient->address }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $patient->phone }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $patient->email }}</td>
                            </tr>
                            <tr>
                                <td>Insured</td>
                                <td>{{ $patient->insured ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <td>Policy Number</td>
                                <td>{{ $patient->policy_no ?: 'None' }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="btn-group-md" role="group" aria-label="Basic example">
                            <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-outline-success">Edit</a>
                            <form action="{{ route('admin.patients.destroy', $patient->id) }}"
                                  style="display: inline-block" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="form-control btn btn-outline-danger"
                                        onclick="return confirm('Delete {{ $patient->name }}?')">Delete
                                </button>
                            </form>
                            <a href="{{ route('admin.patients.index') }}" onclick="window.history.go(-1); return false;" class="btn btn-outline">Back</a>
                        </div>
                        <div class="card mt-5 mb-2">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Visits</p>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(count($patientVisits) == 0)
                                    <p>There are no visits</p>
                                @else
                                    <table class="table table-hover" id="doctor-visits-table">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Duration</th>
                                            <th>Doctor</th>
                                            <th>Cost</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($patientVisits as $patientVisit)
                                            <tr data-id="{{ $patientVisit->id }}">
                                                <td>{{ $patientVisit->date }}</td>
                                                <td>{{ $patientVisit->time }}</td>
                                                <td>{{ $patientVisit->duration }}</td>
                                                <td>
                                                    @wastrashed($patientVisit->doctor_id)
                                                    {{ $patientVisit->doctor_name }}
                                                    @else
                                                        <a href="{{ route('admin.doctors.show', $patientVisit->doctor_id) }}">{{ $patientVisit->doctor_name }}</a>
                                                    @endwastrashed
                                                </td>
                                                <td>{{ $patientVisit->cost }}</td>
                                                <td>
                                                    <div class="btn-group-md" role="group" aria-label="Basic example">
                                                        <a href="{{ route('admin.visits.show', $patientVisit->id) }}"
                                                           class="btn btn-outline-primary">View</a>
                                                        <a href="{{ route('admin.visits.edit', $patientVisit->id) }}"
                                                           class="btn btn-outline-success">Edit</a>
                                                        <form action="{{ route('admin.visits.destroy', $patientVisit->id) }}"
                                                              style="display: inline-block" method="POST">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button type="submit" class="form-control btn btn-outline-danger"
                                                                    onclick="return confirm('Delete this visit?')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
