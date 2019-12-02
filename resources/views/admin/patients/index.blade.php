@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <p class="mb-0">Patients</p>
                            <a href="{{ route('admin.patients.create') }}">Add New Patient</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (count($patients) === 0)
                            <p>There are no patients.</p>
                        @else
                            <table class="table table-hover" id="patients-table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($patients as $patient)
                                    <tr data-id="{{ $patient->id }}">
                                        <td> {{ $patient->name }}</td>
                                        <td> {{ $patient->phone }}</td>
                                        <td> {{ $patient->email }}</td>
                                        <td>
                                            <div class="btn-group-md" role="group" aria-label="Basic example">
                                                <a href="{{ route('admin.patients.show', $patient->id) }}"
                                                   class="btn btn-outline-primary">View</a>
                                                <a href="{{ route('admin.patients.edit', $patient->id) }}"
                                                   class="btn btn-outline-success">Edit</a>
                                                <form action="{{ route('admin.patients.destroy', $patient->id) }}"
                                                      style="display: inline-block" method="POST">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="submit" class="form-control btn btn-outline-danger"
                                                            onclick="return confirm('Delete {{ $patient->name }}?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{ $patients->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
