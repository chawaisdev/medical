@extends('layouts.app')

@section('title', 'Create Appointment')

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#patientModal">
                Add Patient
            </button>

        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white shadow p-4">
                    <form action="{{ route('appointment.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Doctor Select -->
                            <div class="mb-3 col-6">
                                <label for="doctor_id" class="form-label">Select Doctor</label>
                                <select name="doctor_id" id="doctor_id" class="form-control select2" required>
                                    <option value="">-- Select Doctor --</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" data-fee="{{ $doctor->fee ?? 0 }}">
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Patient Select -->
                            <div class="mb-3 col-6">
                                <label for="patient_id" class="form-label fw-bold">Select Patient</label>
                                <select name="patient_id" id="patient_id" class="form-control select2" required>
                                    <option value="">-- Select Patient --</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}">
                                            {{ $patient->name }} - {{ $patient->contact_number ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date -->
                            <div class="mb-3 col-6">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>

                            <!-- Time -->
                            <div class="mb-3 col-6">
                                <label for="time" class="form-label">Time</label>
                                <input type="time" name="time" class="form-control" required>
                            </div>

                            <!-- Services -->
                            <div class="mb-3 col-12">
                                <label for="services" class="form-label">Select Services</label>
                                <select name="services[]" id="services" class="form-select select2" multiple required>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }} - {{ $service->price }}
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
                                    <input type="number" name="fee" id="fee" class="form-control" readonly>
                                </div>
                                <div class="mb-3 col-4">
                                    <label for="discount" class="form-label">Discount (%)</label>
                                    <input type="number" name="discount" id="discount" class="form-control" step="0.01"
                                        value="0">
                                </div>
                                <div class="mb-3 col-4">
                                    <label for="final_fee" class="form-label">Final Fee</label>
                                    <input type="number" name="final_fee" id="final_fee" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Patient Modal -->
    <div class="modal fade" id="patientModal" tabindex="-1" aria-labelledby="patientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patientModalLabel">Add Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPatientForm" action="{{ route('patients.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="patient_name" class="form-label">Patient Name</label>
                            <input type="text" name="name" id="patient_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="patient_contact" class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" id="patient_contact" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="patient_email" class="form-label">Email</label>
                            <input type="email" name="email" id="patient_email" class="form-control">
                        </div>
                         <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="text" name="password" id="password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Patient</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

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
