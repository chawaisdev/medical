@extends('layouts.app')

@section('title', 'Refund Request')

@section('body')
    <div class="container-fluid">
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
                    <div class="card-header bg-gradient-primary text-white py-3 rounded-top-4">
                        <h4 class="mb-0">
                            <i class="fa fa-rotate-left me-2"></i>
                            Refund Request: {{ $appointment->patient->name ?? 'N/A' }}
                        </h4>
                    </div>

                    <div class="card-body p-4">
                        {{-- Appointment & Billing --}}
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3 text-primary">Appointment Details</h6>
                                        <p><strong>Doctor:</strong> {{ $appointment->doctor->name ?? 'N/A' }}</p>
                                        <p><strong>Patient:</strong> {{ $appointment->patient->name ?? 'N/A' }}</p>
                                        <p><strong>Date:</strong>
                                            {{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}</p>
                                        <p><strong>Time:</strong>
                                            {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3 text-primary">Billing Summary</h6>
                                        @php
                                            $totalServicesFee = $appointment->services->sum('price');
                                            $doctorFee = $appointment->fee;
                                            $discount = $appointment->discount ?? 0;
                                            $discountedDoctorFee = $doctorFee - $discount;
                                            $finalFee = $discountedDoctorFee + $totalServicesFee;
                                        @endphp
                                        <p><strong>Doctor Fee:</strong> {{ number_format($doctorFee, 2) }}</p>
                                        <p><strong>Discount:</strong> -{{ number_format($discount, 2) }}</p>
                                        <p><strong>Services Fee:</strong> {{ number_format($totalServicesFee, 2) }}</p>
                                        <hr>
                                        <p class="fw-bold text-success">Final Amount: {{ number_format($finalFee, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Refund Form --}}
                        <form action="{{ route('refunds.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                            <input type="hidden" name="patient_id" value="{{ $appointment->patient->id }}">

                            {{-- Services --}}
                            <h6 class="fw-bold text-primary mb-3">Services Included</h6>
                            <div class="table-responsive shadow-sm mb-4">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Service Name</th>
                                            <th>Fee</th>
                                            <th>Select</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($appointment->services as $index => $service)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $service->name }}</td>
                                                <td>{{ number_format($service->price, 2) }}</td>
                                                <td>
                                                    <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                                        class="form-check-input service-checkbox"
                                                        data-price="{{ $service->price }}">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No services found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Doctor Fee --}}
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="doctor_fee_refunded" id="doctorFeeRefunded"
                                        class="form-check-input" value="1" data-price="{{ $discountedDoctorFee }}">
                                    <label class="form-check-label" for="doctorFeeRefunded">
                                        Include Doctor's Fee ({{ number_format($discountedDoctorFee, 2) }}) in Refund
                                    </label>
                                </div>
                            </div>

                            {{-- Requested Amount --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Requested Refund Amount <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="requested_amount" class="form-control shadow-sm"
                                    id="requestedAmount" placeholder="Enter requested refund amount" required>
                                <div class="invalid-feedback">Requested amount is required and must not exceed the total
                                    refundable amount.</div>
                                <small class="form-text text-muted">Total Refundable: <span
                                        id="totalRefundable">0.00</span></small>
                            </div>

                            {{-- Reason --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Reason</label>
                                <textarea name="reason" class="form-control shadow-sm" rows="3" placeholder="Enter reason for refund request"></textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" id="submitBtn" class="btn btn-primary"
                                    @if ($refundExists) disabled @endif>
                                    <i class="fa fa-paper-plane me-1"></i>
                                    @if ($refundExists)
                                        Already Submitted
                                    @else
                                        Submit Refund Request
                                    @endif
                                </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Auto Update Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
            const doctorFeeCheckbox = document.getElementById('doctorFeeRefunded');
            const requestedAmountInput = document.getElementById('requestedAmount');
            const totalRefundableSpan = document.getElementById('totalRefundable');
            let totalRefundable = 0;

            function updateTotalRefundable() {
                totalRefundable = 0;

                serviceCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        totalRefundable += parseFloat(cb.dataset.price);
                    }
                });

                if (doctorFeeCheckbox.checked) {
                    totalRefundable += parseFloat(doctorFeeCheckbox.dataset.price);
                }

                totalRefundableSpan.textContent = totalRefundable.toFixed(2);
                requestedAmountInput.value = totalRefundable.toFixed(2); // auto update input
            }

            serviceCheckboxes.forEach(cb => cb.addEventListener('change', updateTotalRefundable));
            doctorFeeCheckbox.addEventListener('change', updateTotalRefundable);

            updateTotalRefundable();
        });
    </script>
@endsection
