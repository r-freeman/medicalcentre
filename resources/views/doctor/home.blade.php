@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>Welcome back, {{ $doctor->name }}.</p>

                        <div class="card my-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Profile</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover" id="doctors-table">
                                    <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td>{{ $doctor->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{ $doctor->address }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>{{ $doctor->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{ $doctor->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Start Date</td>
                                        <td>{{ $doctor->start_date }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="btn-group-md" role="group" aria-label="Basic example">
                                    <a href="{{ route('doctor.edit', $doctor->id) }}" class="btn btn-outline-success">Edit</a>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Visits</p>
                                    <a href="{{ route('doctor.visits.create') }}">Add New Visit</a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(count($doctorVisits) == 0)
                                    <p>There are no visits</p>
                                @else
                                    <table class="table table-hover" id="doctor-visits-table">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Duration</th>
                                            <th>Patient</th>
                                            <th>Cost</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($doctorVisits as $doctorVisit)
                                            <tr data-id="{{ $doctorVisit->id }}">
                                                <td>{{ $doctorVisit->date }}</td>
                                                <td>{{ $doctorVisit->time }}</td>
                                                <td>{{ $doctorVisit->duration }}</td>
                                                <td>{{ $doctorVisit->patient_name }}</td>
                                                <td>{{ $doctorVisit->cost }}</td>
                                                <td>
                                                    <div class="btn-group-md" role="group" aria-label="Basic example">
                                                        <a href="{{ route('doctor.visits.show', $doctorVisit->id) }}"
                                                           class="btn btn-outline-primary">View</a>
                                                        <a href="{{ route('doctor.visits.edit', $doctorVisit->id) }}"
                                                           class="btn btn-outline-success">Edit</a>
                                                        <form action="{{ route('doctor.visits.destroy', $doctorVisit->id) }}"
                                                              style="display: inline-block" method="POST">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button type="submit" class="form-control btn btn-outline-danger"
                                                                    onclick="return confirm('Delete this visit?')">
                                                                Cancel
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
