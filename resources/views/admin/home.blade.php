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

                        <p>Welcome back, {{ $admin->name }}.</p>

                        <div class="card mt-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Profile</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover" id="admins-table">
                                    <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td>{{ $admin->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{ $admin->address }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>{{ $admin->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{ $admin->email }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="btn-group-md" role="group" aria-label="Basic example">
                                    <a href="{{ route('admin.edit', $admin->id) }}"
                                       class="btn btn-outline-success">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
