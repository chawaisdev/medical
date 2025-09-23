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

                        {{-- Refund Status --}}
                        @if ($refundExists && $refund)
                            <div class="alert alert-info d-flex align-items-center mb-4">
                                <i class="fa fa-info-circle me-2"></i>
                                <div>
                                    Refund Status:
                                    @if ($refund->status == 'pending')
                                        <span class="badge bg-warning text-dark"><i class="fa fa-clock me-1"></i>
                                            Pending</span>
                                    @elseif($refund->status == 'approved')
                                        <span class="badge bg-success"><i class="fa fa-check-circle me-1"></i>
                                            Approved</span>
                                    @elseif($refund->status == 'rejected')
                                        <span class="badge bg-danger"><i class="fa fa-times-circle me-1"></i>
                                            Rejected</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fa fa-question-circle me-1"></i>
                                            Unknown</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Appointment & Billing --}}
                        <div class="row g-4 mb-4">

                            {{-- Appointment Details --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3 text-primary">
                                            <i class="fa fa-calendar-check me-2"></i>
                                            Appointment Details
                                        </h6>
                                        <p><i class="fa fa-user-md me-2 text-muted"></i><strong>Doctor:</strong>
                                            {{ $appointment->doctor->name ?? 'N/A' }}</p>
                                        <p><i class="fa fa-user me-2 text-muted"></i><strong>Patient:</strong>
                                            {{ $appointment->patient->name ?? 'N/A' }}</p>
                                        <p><i class="fa fa-calendar me-2 text-muted"></i><strong>Date:</strong>
                                            {{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}</p>
                                        <p><i class="fa fa-clock me-2 text-muted"></i><strong>Time:</strong>
                                            {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Billing Summary --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3 text-primary">
                                            <i class="fa fa-file-invoice-dollar me-2"></i>
                                            Billing Summary
                                        </h6>
                                        @php
                                            $totalServicesFee = $appointment->services->sum('price');
                                            $doctorFee = $appointment->fee;
                                            $finalFee = $doctorFee + $totalServicesFee;
                                        @endphp

                                        <p><i class="fa fa-user-md me-2 text-muted"></i><strong>Doctor Fee:</strong>
                                            {{ number_format($doctorFee, 2) }}</p>

                                        <p><i class="fa fa-stethoscope me-2 text-muted"></i><strong>Services Fee:</strong>
                                            {{ number_format($totalServicesFee, 2) }}</p>

                                        <hr>
                                        <p class="fw-bold text-success"><i class="fa fa-money-bill-wave me-2"></i>
                                            Final Amount: {{ number_format($finalFee, 2) }}</p>
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
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fa fa-list me-2"></i>
                                Services Included
                            </h6>
                            <div class="table-responsive shadow-sm mb-4">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th><i class="fa fa-cogs me-1"></i> Service Name</th>
                                            <th><i class="fa fa-dollar-sign me-1"></i> Fee</th>
                                            <th><i class="fa fa-check-square me-1"></i> Select</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($appointment->services as $index => $service)
                                            @php
                                                $isRefundedService =
                                                    $refundExists &&
                                                    $refund->services->pluck('id')->contains($service->id);
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $service->name }}</td>
                                                <td>{{ number_format($service->price, 2) }}</td>
                                                <td>
                                                    <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                                        class="form-check-input service-checkbox"
                                                        data-price="{{ $service->price }}"
                                                        @if ($isRefundedService) checked disabled @endif>
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
                            @php
                                $doctorFeeRefunded = $refundExists && $refund->doctor_fee_refund > 0;
                            @endphp

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="doctor_fee_refunded" id="doctorFeeRefunded"
                                        class="form-check-input" value="1" data-price="{{ $doctorFee }}"
                                        @if ($doctorFeeRefunded) checked disabled @endif>
                                    <label class="form-check-label" for="doctorFeeRefunded">
                                        <i class="fa fa-user-md me-1"></i> Include Doctor's Fee
                                        ({{ number_format($doctorFee, 2) }}) in Refund
                                    </label>
                                </div>
                            </div>

                            {{-- Requested Amount --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-money-check-alt me-2"></i>
                                    Requested Refund Amount <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" name="requested_amount" class="form-control shadow-sm"
                                    id="requestedAmount" readonly required>
                                <div class="invalid-feedback">Requested amount is required.</div>
                                <small class="form-text text-muted">
                                    <i class="fa fa-calculator me-1"></i>
                                    Total Refundable: <span id="totalRefundable">0.00</span>
                                </small>
                            </div>

                            {{-- Reason --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-comment-dots me-2"></i>
                                    Reason
                                </label>
                                <textarea name="reason" class="form-control shadow-sm" rows="3" placeholder="Enter reason for refund request"></textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                @if ($canSubmit)
                                    <button type="submit" id="submitBtn" class="btn btn-primary">
                                        <i class="fa fa-paper-plane me-1"></i>
                                        {{ $statusMessage }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger" disabled>
                                        <i class="fa fa-ban me-1"></i>
                                        {{ $statusMessage }}
                                    </button>
                                @endif
                            </div>
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
            const submitBtn = document.getElementById('submitBtn');

            function updateTotalRefundable() {
                let totalRefundable = 0;

                serviceCheckboxes.forEach(cb => {
                    if (cb.checked && !cb.disabled) {
                        totalRefundable += parseFloat(cb.dataset.price);
                    }
                });

                if (doctorFeeCheckbox && doctorFeeCheckbox.checked && !doctorFeeCheckbox.disabled) {
                    totalRefundable += parseFloat(doctorFeeCheckbox.dataset.price);
                }

                totalRefundableSpan.textContent = totalRefundable.toFixed(2);
                requestedAmountInput.value = totalRefundable.toFixed(2);

                checkRemaining();
            }

            function checkRemaining() {
                // Check how many are still refundable (not disabled)
                let remainingOptions = 0;

                serviceCheckboxes.forEach(cb => {
                    if (!cb.disabled) remainingOptions++;
                });

                if (doctorFeeCheckbox && !doctorFeeCheckbox.disabled) {
                    remainingOptions++;
                }

                if (remainingOptions === 0) {
                    // Matlab sab pehle hi refund ho chuke hain
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa fa-ban me-1"></i> All Refunds Already Requested';
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa fa-paper-plane me-1"></i> Submit Refund Request';
                }
            }

            // Events
            serviceCheckboxes.forEach(cb => cb.addEventListener('change', updateTotalRefundable));
            if (doctorFeeCheckbox) {
                doctorFeeCheckbox.addEventListener('change', updateTotalRefundable);
            }

            // Initial load
            updateTotalRefundable();
        });
    </script>

@endsection
