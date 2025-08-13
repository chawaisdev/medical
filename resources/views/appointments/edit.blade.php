@extends('layouts.app')

@section('title')
    Edit Appointment
@endsection

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

                            <div class="mb-3 col-6">
                                <label for="doctor_id" class="form-label">Select Doctor</label>
                                <select name="doctor_id" class="form-control" required>
                                    <option value="">-- Select Doctor --</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}"
                                            {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="patient_id" class="form-label">Select Patient</label>
                                <select name="patient_id" class="form-control" required>
                                    <option value="">-- Select Patient --</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}"
                                            {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="time" class="form-label">Time</label>
                                <input type="time" name="time" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}" required>
                            </div>


                        </div>

                        <button type="submit" class="btn btn-primary">Update Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
