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
        </div>

        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title">All Appointments</div>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Doctor Name</th>
                                    <th>Time</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($patient as $patient)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $patient->doctor->name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($patient->time)->format('h:i A') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($patient->date)->format('Y-m-d') }}</td>
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
