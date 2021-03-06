@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                       Doctor
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
                            <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-outline-success">Edit</a>
                            <form action="{{ route('admin.doctors.destroy', $doctor->id) }}"
                                  style="display: inline-block" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="form-control btn btn-outline-danger"
                                        onclick="return confirm('Delete {{ $doctor->name }}?')">Delete
                                </button>
                            </form>
                            <a href="{{ route('admin.doctors.index') }}" onclick="window.history.go(-1); return false;" class="btn btn-outline">Back</a>
                        </div>
                        <div class="card mt-5 mb-2">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Visits</p>
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
                                                <td>
                                                    @wastrashed($doctorVisit->patient_id)
                                                    {{ $doctorVisit->patient_name }}
                                                    @else
                                                        <a href="{{ route('admin.patients.show', $doctorVisit->patient_id) }}">{{ $doctorVisit->patient_name }}</a>
                                                    @endwastrashed
                                                </td>
                                                <td>{{ $doctorVisit->cost }}</td>
                                                <td>
                                                    <div class="btn-group-md" role="group" aria-label="Basic example">
                                                        <a href="{{ route('admin.visits.show', $doctorVisit->id) }}"
                                                           class="btn btn-outline-primary">View</a>
                                                        <a href="{{ route('admin.visits.edit', $doctorVisit->id) }}"
                                                           class="btn btn-outline-success">Edit</a>
                                                        <form action="{{ route('admin.visits.destroy', $doctorVisit->id) }}"
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

                                    {{ $doctorVisits->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
