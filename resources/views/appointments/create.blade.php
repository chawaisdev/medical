@extends('layouts.app')

@section('title')
    Create Appointment
@endsection

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
                            <div class="mb-3 col-6">
                                <label for="doctor_id" class="form-label">Select Doctor</label>
                                <select name="doctor_id" class="form-control" required>
                                    <option value="">-- Select Doctor --</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="patient_id" class="form-label">Select Patient</label>
                                </div>
                                <select name="patient_id" class="form-control select2" required>
                                    <option value="">-- Select Patient --</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}">
                                            {{ $patient->name }} - {{ $patient->contact_number ?? 'N/A' }}
                                        </option>
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
                        </div>
                        <button type="submit" class="btn btn-primary">Save Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Patient Modal --}}
    <div class="modal fade" id="patientModal" tabindex="-1" aria-labelledby="patientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl"><!-- Changed to modal-xl for larger size -->
            <div class="modal-content">
                <form action="{{ route('patients.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="patientModalLabel">Add New Patient</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <!-- Name -->
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <!-- Father's / Husband's Name -->
                        <div class="col-md-6">
                            <label class="form-label">Father's / Husband's Name</label>
                            <input type="text" name="father_name" class="form-control" required>
                        </div>

                        <!-- Age -->
                        <div class="col-md-4">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" required>
                        </div>

                        <!-- CNIC -->
                        <div class="col-md-4">
                            <label class="form-label">CNIC</label>
                            <input type="text" name="cnic" class="form-control">
                        </div>

                        <!-- Contact Number -->
                        <div class="col-md-4">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" required>
                        </div>

                        <!-- Address -->
                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>

                        <!-- Patient ID / MR Number -->
                        <div class="col-md-6">
                            <label class="form-label">Patient ID / MR Number</label>
                            <input type="text" name="mr_number" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Patient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
