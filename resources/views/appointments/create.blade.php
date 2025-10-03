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
                    <form action="{{ route('appointment.store') }}" method="POST" id="appointmentForm">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label for="doctor_id" class="form-label">Select Doctor</label>
                                <select name="doctor_id" id="doctor_id" class="form-control select2" required>
                                    <option value="">-- Select Doctor --</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" data-fee="{{ $doctor->fee ?? 0 }}">
                                            {{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="patient_id" class="form-label fw-bold">Select Patient</label>
                                <select name="patient_id" id="patient_id" class="form-control select2" required>
                                    <option value="">-- Select Patient --</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }} -
                                            {{ $patient->contact_number ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="time" class="form-label">Time</label>
                                <input type="time" name="time" class="form-control" required>
                            </div>

                            <div class="mb-3 col-12">
                                <label for="services" class="form-label">Select Services</label>
                                <select name="services[]" id="services" class="form-select select2" multiple>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                            {{ $service->name }} - {{ $service->price }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

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


                        <div class="row mt-3">
                            <div class="col-4 ms-auto">
                                <div class="card p-3 border-secondary">
                                    <h5 class="mb-3">Billing Summary</h5>
                                    <div class="mb-2 d-flex justify-content-between">
                                        <span>Services Subtotal</span>
                                        <strong id="services_subtotal">0.00</strong>
                                    </div>
                                    <div class="mb-2 d-flex justify-content-between">
                                        <span>Doctor Fee</span>
                                        <strong id="display_fee">0.00</strong>
                                    </div>
                                    <div class="mb-2 d-flex justify-content-between">
                                        <span>Doctor Discount (%)</span>
                                        <strong id="display_discount">0.00</strong>
                                    </div>
                                    <div class="mb-2 d-flex justify-content-between">
                                        <span>Final Doctor Fee</span>
                                        <strong id="display_final_fee">0.00</strong>
                                    </div>
                                    <hr>

                                    <hr>
                                    <div class="d-flex justify-content-between fs-5">
                                        <span>Total Amount</span>
                                        <strong id="grand_total">0.00</strong>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="patient_name" class="form-label">Patient Name</label>
                                <input type="text" name="name" id="patient_name" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="patient_age" class="form-label">Age</label>
                                <input type="number" name="age" id="patient_age" class="form-control"
                                    placeholder="Enter age">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="patient_cnic" class="form-label">CNIC</label>
                                <input type="text" name="cnic" id="patient_cnic" class="form-control"
                                    placeholder="Enter CNIC">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="patient_contact" class="form-label">Contact Number</label>
                                <input type="text" name="contact_number" id="patient_contact" class="form-control"
                                    placeholder="Enter contact number">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="patient_email" class="form-label">Email</label>
                                <input type="email" name="email" id="patient_email" class="form-control"
                                    placeholder="Enter email">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" name="password" id="password" class="form-control"
                                    placeholder="Enter password">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="patient_address" class="form-label">Address</label>
                                <textarea name="address" id="patient_address" class="form-control" rows="2" placeholder="Enter address"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Patient</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            const doctorSelect = $('#doctor_id');
            const doctorFields = $('#doctor_fields');
            const feeInput = $('#fee');
            const discountInput = $('#discount');
            const finalFeeInput = $('#final_fee');

            function toNumber(v) {
                const n = parseFloat(String(v).replace(/[^0-9.-]+/g, ''));
                return isNaN(n) ? 0 : n;
            }

            function format(n) {
                return (Math.round((n + Number.EPSILON) * 100) / 100).toFixed(2);
            }

            function updateFinalFee() {
                const fee = toNumber(feeInput.val());
                const discount = toNumber(discountInput.val());
                const finalFee = fee - (fee * discount / 100);
                finalFeeInput.val(format(finalFee));
                $('#display_fee').text(format(fee));
                $('#display_discount').text(format(discount));
                $('#display_final_fee').text(format(finalFee));
                updateGrandTotal();
            }

            function getSelectedServices() {
                const selected = [];
                $('#services option:selected').each(function() {
                    const id = $(this).val();
                    const text = $(this).text();
                    const price = toNumber($(this).data('price'));
                    selected.push({
                        id,
                        text,
                        price
                    });
                });
                return selected;
            }

            function renderSelectedServices() {
                const list = $('#selectedServicesList');
                list.empty();
                const services = getSelectedServices();
                let subtotal = 0;
                services.forEach(s => {
                    subtotal += s.price;
                    const li = $(
                        '<li class="list-group-item d-flex justify-content-between align-items-center"></li>'
                    );
                    li.text(s.text);
                    li.append($('<span class="badge rounded-pill"></span>').text(format(s.price)));
                    list.append(li);
                });
                $('#services_subtotal').text(format(subtotal));
                updateGrandTotal();
            }

            function updateGrandTotal() {
                const servicesSubtotal = toNumber($('#services_subtotal').text());
                const finalFee = toNumber($('#final_fee').val() || $('#display_final_fee').text());
                const additional = toNumber($('#additional_charges').val()); // NEW

                // Update display
                $('#display_additional').text(format(additional));

                const grand = servicesSubtotal + finalFee + additional; // FIX
                $('#grand_total').text(format(grand));
            }

            // Watch additional charges input
            $('#additional_charges').on('input', function() {
                updateGrandTotal();
            });

            doctorSelect.on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const fee = toNumber(selectedOption.data('fee'));
                if ($(this).val() !== '') {
                    doctorFields.show();
                    feeInput.val(format(fee));
                } else {
                    doctorFields.hide();
                    feeInput.val('');
                    discountInput.val(0);
                    finalFeeInput.val('');
                    $('#display_fee').text('0.00');
                    $('#display_discount').text('0.00');
                    $('#display_final_fee').text('0.00');
                }
                updateFinalFee();
            });

            discountInput.on('input', updateFinalFee);
            $('#services').on('change', function() {
                renderSelectedServices();
            });

            renderSelectedServices();
            updateFinalFee();

            $('#appointmentForm').on('submit', function() {
                const feeVal = toNumber(feeInput.val());
                const finalFeeVal = toNumber(finalFeeInput.val());
                if (!doctorSelect.val()) {
                    alert('Please select a doctor.');
                    return false;
                }
                $('input[name="final_fee"]').val(format(finalFeeVal));
                return true;
            });
        });
    </script>
@endsection
