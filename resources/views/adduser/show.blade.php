@extends('layouts.app')

@section('title')
    User Show
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">User Show </li>
                </ol>
            </nav>
        </div>


        {{-- ==== Stats Cards ==== --}}
        <div class="row">
            {{-- Total Sales --}}
            <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-3 bg-primary text-white rounded-circle">
                                    <i class="fas fa-chart-line fa-lg"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h5 class="fw-semibold mb-0 lh-1">{{ number_format($totalSales) }}</h5>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Total Sales</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Fee Refund --}}
            <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-3 bg-danger text-white rounded-circle">
                                    <i class="fas fa-user-md fa-lg"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h5 class="fw-semibold mb-0 lh-1">{{ number_format($totalDoctorRefund) }}</h5>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Total Fee Refund</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Service Refund --}}
            <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-3 bg-warning text-white rounded-circle">
                                    <i class="fas fa-stethoscope fa-lg"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h5 class="fw-semibold mb-0 lh-1">{{ number_format($totalRefundServices) }}</h5>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Total Service Refund</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Profit --}}
            <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-3 bg-success text-white rounded-circle">
                                    <i class="fas fa-dollar-sign fa-lg"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h5 class="fw-semibold mb-0 lh-1">{{ number_format($profit) }}</h5>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Profit</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- ==== Doctor Appointments Section ==== --}}
        @if ($user->user_type === 'doctor')
            <div class="card custom-card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Appointments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Fee</th>
                                    <th>Discount</th>
                                    <th>Final Fee</th>
                                    <th>Services</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d M, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                        <td>{{ number_format($appointment->fee) }}</td>
                                        <td>{{ number_format($appointment->discount) }}</td>
                                        <td><strong>{{ number_format($appointment->final_fee) }}</strong></td>
                                        <td>
                                            @if ($appointment->services->count())
                                                {{ $appointment->services->pluck('name')->join(', ') }}
                                            @else
                                                <span class="text-muted">No Services</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No Appointments Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
