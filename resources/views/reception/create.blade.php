@extends('layouts.app')

@section('title')
    Patient Create
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
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white shadow p-4">
                    <form action="{{ route('reception.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter name"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password"
                                    >
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-6">
                                <label for="father_name" class="form-label">Father's / Husband's Name</label>
                                <input type="text" name="father_name" class="form-control"
                                    placeholder="Enter father's/husband's name" value="{{ old('father_name') }}">
                                @error('father_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-4">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" name="age" class="form-control" placeholder="Enter age"
                                    value="{{ old('age') }}">
                                @error('age')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-4">
                                <label for="cnic" class="form-label">CNIC</label>
                                <input type="number" name="cnic" class="form-control" placeholder="Enter CNIC"
                                    value="{{ old('cnic') }}">
                                @error('cnic')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-4">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="number" name="contact_number" class="form-control"
                                    placeholder="Enter contact number" value="{{ old('contact_number') }}">
                                @error('contact_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2" placeholder="Enter address">{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Patient </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
