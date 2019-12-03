@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {{-- Display error flash message if it was set --}}
                @if(session('danger'))
                    @component('danger')
                        <strong>{{ session('danger') }}</strong>
                    @endcomponent
                @endif

                {{-- Display success flash message if it was set --}}
                @if(session('success'))
                    @component('success')
                        <strong>{{ session('success') }}</strong>
                    @endcomponent
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <p class="mb-0">Doctors</p>
                            <a href="{{ route('admin.doctors.create') }}">Add New Doctor</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (count($doctors) === 0)
                            <p>There are no doctors.</p>
                        @else
                            <table class="table table-hover" id="doctors-table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($doctors as $doctor)
                                    <tr data-id="{{ $doctor->id }}">
                                        <td> {{ $doctor->name }}</td>
                                        <td> {{ $doctor->phone }}</td>
                                        <td> {{ $doctor->email }}</td>
                                        <td>
                                            <div class="btn-group-md" role="group" aria-label="Basic example">
                                                <a href="{{ route('admin.doctors.show', $doctor->id) }}"
                                                   class="btn btn-outline-primary">View</a>
                                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}"
                                                   class="btn btn-outline-success">Edit</a>
                                                <form action="{{ route('admin.doctors.destroy', $doctor->id) }}"
                                                      style="display: inline-block" method="POST">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="submit" class="form-control btn btn-outline-danger"
                                                            onclick="return confirm('Delete {{ $doctor->name }}?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{ $doctors->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
