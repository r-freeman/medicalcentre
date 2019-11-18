@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        View patient
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
                            <a href="{{ route('admin.patients.index') }}" class="btn btn-outline">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
