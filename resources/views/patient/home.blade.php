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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
