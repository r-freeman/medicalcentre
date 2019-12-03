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
                            <a href="{{ route('doctor.visits.create') }}">Add New Visit</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (count($doctorVisits) === 0)
                            <p>There are no visits.</p>
                        @else
                            <table class="table table-hover" id="visits-table">
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

                            {{ $doctorVisits->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
