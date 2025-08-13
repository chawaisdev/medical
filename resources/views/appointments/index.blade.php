@extends('layouts.app')

@section('title')
    Appointment Index
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Appointment Index</li>
                </ol>
            </nav>
            <a href="{{ route('appointment.create') }}" class="btn btn-primary btn-sm">Add Appointment</a>
        </div>

        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title">All Appointment</div>
                    <form action="{{ route('appointment.index') }}" method="GET" class="d-flex align-items-center">
                        <div class="me-2">
                            <input type="date" name="date" class="form-control"
                                value="{{ request()->query('date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Doctor Name</th>
                                    <th>Patient Name</th>
                                    <th>Time</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($appointments as $appointment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                                        <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}</td>
                                        <td>
                                            <form action="{{ route('appointment.destroy', $appointment->id) }}"
                                                method="POST" style="display: inline;"
                                                onsubmit="return confirm('Are you sure you want to delete?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('appointment.edit', $appointment->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fa fa-pen-to-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
