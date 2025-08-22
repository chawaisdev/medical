@extends('layouts.app')

@section('title')
    Edit Patient
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Patient Edit</li>
                </ol>
            </nav>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white shadow p-4">
                    <form action="{{ route('reception.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Change this to PUT for update --}}

                        <div class="row">
                            <div class="mb-3 col-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter name"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="password" class="form-label">Password (optional)</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter new password">
                            </div>
                                <div class="mb-3 col-6">
                                    <label for="father_name" class="form-label">Father's / Husband's Name</label>
                                    <input type="text" name="father_name" class="form-control"
                                        value="{{ old('father_name', $user->father_name) }}">
                                </div>

                                <div class="mb-3 col-4">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="number" name="age" class="form-control"
                                        value="{{ old('age', $user->age) }}">
                                </div>

                                <div class="mb-3 col-4">
                                    <label for="cnic" class="form-label">CNIC</label>
                                    <input type="text" name="cnic" class="form-control"
                                        value="{{ old('cnic', $user->cnic) }}">
                                </div>

                                <div class="mb-3 col-4">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control"
                                        value="{{ old('contact_number', $user->contact_number) }}">
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address) }}</textarea>
                                </div>
                            </div>
                        <button type="submit" class="btn btn-primary">Update Patient</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
