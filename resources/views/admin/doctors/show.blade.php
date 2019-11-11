@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $doctor->name }}
                    </div>
                    <div class="card-body">
                        <table class="table table-hover" id="doctors-table">
                            <tbody>
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
                            <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.doctors.destroy', $doctor->id) }}"
                                  style="display: inline-block" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="form-control btn btn-outline-danger"
                                        onclick="return confirm('Delete {{ $doctor->name }}?')">Delete
                                </button>
                            </form>
                            <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
