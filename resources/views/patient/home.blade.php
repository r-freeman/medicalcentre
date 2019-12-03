@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{-- Display success flash message if it was set --}}
                @if(session('success'))
                    @component('success')
                        <strong>{{ session('success') }}</strong>
                    @endcomponent
                @endif

                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>Welcome back, {{ $patient->name }}.</p>

                        <div class="card my-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Profile</p>
                                </div>
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
                                        <td>{{ $patient->policy_no ?: 'None'  }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="btn-group-md" role="group" aria-label="Basic example">
                                    <a href="{{ route('patient.edit', $patient->id) }}" class="btn btn-outline-success">Edit</a>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Visits</p>
                                    <a href="{{ route('patient.visits.create') }}">Add New Visit</a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(count($patientVisits) == 0)
                                    <p>There are no visits</p>
                                @else
                                    <table class="table table-hover" id="patient-visits-table">
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
                                                <td>{{ $patientVisit->doctor_name }}</td>
                                                <td>{{ $patientVisit->cost }}</td>
                                                <td>
                                                    <div class="btn-group-md" role="group" aria-label="Basic example">
                                                        <a href="{{ route('patient.visits.show', $patientVisit->id) }}"
                                                           class="btn btn-outline-primary">View</a>
                                                        <a href="{{ route('patient.visits.edit', $patientVisit->id) }}"
                                                           class="btn btn-outline-success">Edit</a>
                                                        <form
                                                            action="{{ route('patient.visits.destroy', $patientVisit->id) }}"
                                                            style="display: inline-block" method="POST">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token"
                                                                   value="{{ csrf_token() }}">
                                                            <button type="submit"
                                                                    class="form-control btn btn-outline-danger"
                                                                    onclick="return confirm('Cancel this visit?')">
                                                                Cancel
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                    {{ $patientVisits->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
