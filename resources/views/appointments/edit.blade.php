@extends('layouts.app')

@section('title', 'Edit Appointment')

@section('body')
<div class="container-fluid">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Appointment</li>
            </ol>
        </nav>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-white shadow p-4">
                <form action="{{ route('appointment.update', $appointment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">

                        <!-- Doctor Select -->
                        <div class="mb-3 col-6">
                            <label for="doctor_id" class="form-label">Select Doctor</label>
                            <select name="doctor_id" id="doctor_id" class="form-control select2" required>
                                <option value="">-- Select Doctor --</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        data-fee="{{ $doctor->fee ?? 0 }}"
                                        {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Patient Select -->
                        <div class="mb-3 col-6">
                            <label for="patient_id" class="form-label">Select Patient</label>
                            <select name="patient_id" id="patient_id" class="form-control select2" required>
                                <option value="">-- Select Patient --</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date -->
                        <div class="mb-3 col-6">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" class="form-control"
                                value="{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}" required>
                        </div>

                        <!-- Time -->
                        <div class="mb-3 col-6">
                            <label for="time" class="form-label">Time</label>
                            <input type="time" name="time" class="form-control"
                                value="{{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}" required>
                        </div>

                        <!-- Services -->
                        <div class="mb-3 col-12">
                            <label for="services" class="form-label">Select Services</label>
                            <select name="services[]" id="services" class="form-select select2" multiple required>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ in_array($service->id, $appointment->services->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $service->name }} - {{ $service->price }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <!-- Doctor Fee Section -->
                    <div id="doctor_fields" style="display:none; margin-top:20px;">
                        <div class="row">
                            <div class="mb-3 col-4">
                                <label for="fee" class="form-label">Doctor Fee</label>
                                <input type="number" name="fee" id="fee" class="form-control" readonly
                                    value="{{ $appointment->fee ?? 0 }}">
                            </div>
                            <div class="mb-3 col-4">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="number" name="discount" id="discount" class="form-control" step="0.01"
                                    value="{{ $appointment->discount ?? 0 }}">
                            </div>
                            <div class="mb-3 col-4">
                                <label for="final_fee" class="form-label">Final Fee</label>
                                <input type="number" name="final_fee" id="final_fee" class="form-control" readonly
                                    value="{{ $appointment->final_fee ?? 0 }}">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Appointment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

        // Show/hide doctor fields
        const doctorSelect = $('#doctor_id');
        const doctorFields = $('#doctor_fields');
        const feeInput = $('#fee');
        const discountInput = $('#discount');
        const finalFeeInput = $('#final_fee');

        function updateFinalFee() {
            const fee = parseFloat(feeInput.val() || 0);
            const discount = parseFloat(discountInput.val() || 0);
            finalFeeInput.val(fee - (fee * discount / 100));
        }

        // Show div if a doctor is already selected
        if (doctorSelect.val() !== '') {
            doctorFields.show();
        }

        doctorSelect.on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const fee = parseFloat(selectedOption.data('fee') || 0);

            if ($(this).val() !== '') {
                doctorFields.show();
                feeInput.val(fee);
            } else {
                doctorFields.hide();
                feeInput.val('');
                discountInput.val(0);
                finalFeeInput.val('');
            }

            updateFinalFee();
        });

        discountInput.on('input', updateFinalFee);
    });
</script>
@endsection
