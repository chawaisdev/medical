@extends('layouts.app')

@section('title', 'Refund Request')

@section('body')
    <div class="container-fluid">
        {{-- Breadcrumb --}}
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Create Refund</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xl-9 mx-auto">
                <div class="card shadow-lg border-0 rounded-4">
                    {{-- Header --}}
                    <div class="card-header bg-gradient-primary text-white py-3 rounded-top-4">
                        <h4 class="mb-0">
                            <i class="fa fa-rotate-left me-2"></i>
                            Refund Request: {{ $appointment->patient->name ?? 'N/A' }}
                        </h4>
                    </div>

                    <div class="card-body p-4">
                        {{-- Appointment & Billing --}}
                        <div class="row g-4 mb-4">
                            {{-- Appointment Details --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3 text-primary">Appointment Details</h6>
                                        <p><i class="fa fa-user-md text-primary me-2"></i><strong>Doctor:</strong>
                                            {{ $appointment->doctor->name ?? 'N/A' }}</p>
                                        <p><i class="fa fa-user text-success me-2"></i><strong>Patient:</strong>
                                            {{ $appointment->patient->name ?? 'N/A' }}</p>
                                        <p><i class="fa fa-calendar text-warning me-2"></i><strong>Date:</strong>
                                            {{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}</p>
                                        <p><i class="fa fa-clock text-info me-2"></i><strong>Time:</strong>
                                            {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Billing Details --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3 text-primary">Billing Summary</h6>

                                        @php
                                            $totalServicesFee = $appointment->services->sum('price');
                                            $doctorFee = $appointment->fee;
                                            $totalDiscount =
                                                $appointment->discount + $appointment->services->sum('discount' ?? 0);
                                            $finalFee = $doctorFee + $totalServicesFee - $totalDiscount;
                                        @endphp

                                        <p class="mb-2"><strong>Doctor Fee:</strong> <span
                                                class="float-end">{{ number_format($doctorFee, 2) }}</span></p>
                                        <p class="mb-2"><strong>Services Fee:</strong> <span
                                                class="float-end">{{ number_format($totalServicesFee, 2) }}</span></p>
                                        <p class="mb-2 text-danger"><strong>Total Discount:</strong> <span
                                                class="float-end">-{{ number_format($totalDiscount, 2) }}</span></p>
                                        <hr>
                                        <p class="mb-0 text-success fw-bold fs-5"><strong>Final Amount:</strong> <span
                                                class="float-end">{{ number_format($finalFee, 2) }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Services Table --}}
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-3">Services Included</h6>
                            <div class="table-responsive shadow-sm">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Service Name</th>
                                            <th>Fee</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($appointment->services as $index => $service)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $service->name }}</td>
                                                <td>{{ number_format($service->price, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No services found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Refund Form --}}
                        <div class="mt-4">
                            <form action="{{ route('refunds.store') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                <input type="hidden" name="patient_id" value="{{ $appointment->patient->id }}">

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Requested Refund Amount <span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="requested_amount"
                                        class="form-control shadow-sm" placeholder="Enter requested refund amount" required>
                                    <div class="invalid-feedback">Requested amount is required</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Reason</label>
                                    <textarea name="reason" class="form-control shadow-sm" rows="3" placeholder="Enter reason for refund request"></textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" id="submitButton"
                                        {{ $refundExists ? 'disabled' : '' }}>
                                        <i class="fa fa-paper-plane me-1"></i>
                                        {{ $refundExists ? 'Refund Already Requested' : 'Submit Refund Request' }}
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div> {{-- card-body --}}
                </div>
            </div>
        </div>
    </div>
@endsection
