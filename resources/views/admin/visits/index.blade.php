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
                            <p class="mb-0">Visits</p>
                            <a href="{{ route('admin.visits.create') }}">Add New Visit</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (count($visits) === 0)
                            <p>There are no visits.</p>
                        @else
                            <table class="table table-hover" id="visits-table">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Duration</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Cost</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($visits as $visit)
                                    <tr data-id="{{ $visit->id }}">
                                        <td>{{ $visit->date }}</td>
                                        <td>{{ $visit->time }}</td>
                                        <td>{{ $visit->duration }}</td>
                                        <td>
                                            @wastrashed($visit->patient_id)
                                                {{ $visit->patient_name }}
                                            @else
                                                <a href="{{ route('admin.patients.show', $visit->patient_id) }}">{{ $visit->patient_name }}</a>
                                            @endwastrashed
                                        </td>
                                        <td>
                                            @wastrashed($visit->doctor_id)
                                                {{ $visit->doctor_name }}
                                            @else
                                                <a href="{{ route('admin.doctors.show', $visit->doctor_id) }}">{{ $visit->doctor_name }}</a>
                                            @endwastrashed
                                        </td>
                                        <td>{{ $visit->cost }}</td>
                                        <td>
                                            <div class="btn-group-md" role="group" aria-label="Basic example">
                                                <a href="{{ route('admin.visits.show', $visit->id) }}"
                                                   class="btn btn-outline-primary">View</a>
                                                <a href="{{ route('admin.visits.edit', $visit->id) }}"
                                                   class="btn btn-outline-success">Edit</a>
                                                <form action="{{ route('admin.visits.destroy', $visit->id) }}"
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

                            {{ $visits->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
