@extends('layouts.app')

@section('title')
    Create User
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
                    <form action="{{ route('adduser.store') }}" method="POST" enctype="multipart/form-data">
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
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-6">
                                <label for="user_type" class="form-label">User Type</label>
                                <select name="user_type" class="form-select" required>
                                    <option value="">-- Select User Type --</option>
                                    <option value="doctor" {{ old('user_type') == 'doctor' ? 'selected' : '' }}>Doctor
                                    </option>
                                    <option value="reception" {{ old('user_type') == 'reception' ? 'selected' : '' }}>
                                        Reception</option>
                                    <option value="patient" {{ old('user_type') == 'patient' ? 'selected' : '' }}>Patient
                                    </option>
                                </select>
                                @error('user_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password"
                                    required>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
