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
                                    <th>Services</th>
                                    <th>Doctor Fee</th>
                                    <th>Discount</th>
                                    <th>Final Fee</th>
                                    <th>Total Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($appointments as $appointment)
                                    @php
                                        // Calculate totals (without additional charges)
                                        $servicesTotal = $appointment->services->sum('price');
                                        $finalFee = $appointment->final_fee ?? 0;
                                        $total = $finalFee + $servicesTotal;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                                        <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}</td>
                                        <td>
                                            @if ($appointment->services->count() > 0)
                                                @foreach ($appointment->services as $service)
                                                    <span class="badge bg-primary">
                                                        {{ $service->name }} ({{ $service->price }})
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">No Services</span>
                                            @endif
                                        </td>
                                        <td>{{ $appointment->fee ?? 'N/A' }}</td>
                                        <td>{{ $appointment->discount ?? 'N/A' }}</td>
                                        <td>{{ $finalFee }}</td>
                                        <td><strong>{{ $total }}</strong></td>
                                        <td>
                                            <a href="{{ route('appointments.print', $appointment->id) }}" target="_blank"
                                                class="btn btn-sm btn-info">
                                                <i class="fa fa-print"></i>
                                            </a>
                                            <a href="{{ route('refunds.show', $appointment->id) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="fa fa-undo"></i> Refund
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
