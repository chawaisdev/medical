@extends('layouts.app')

@section('title')
    Edit User
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Edit</li>
                </ol>
            </nav>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white shadow p-4">
                    <form action="{{ route('adduser.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                                <label for="user_type" class="form-label">User Type</label>
                                <select name="user_type" id="user_type" class="form-select" required>
                                    <option value="">-- Select User Type --</option>
                                    <option value="doctor"
                                        {{ old('user_type', $user->user_type) == 'doctor' ? 'selected' : '' }}>Doctor
                                    </option>
                                    <option value="reception"
                                        {{ old('user_type', $user->user_type) == 'reception' ? 'selected' : '' }}>Reception
                                    </option>
                                    <option value="patient"
                                        {{ old('user_type', $user->user_type) == 'patient' ? 'selected' : '' }}>Patient
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="role_id" class="form-label">User Role</label>
                                <select name="role_id" id="role_id" class="form-select" required>
                                    <option value="">-- Select Role --</option>
                                    @foreach ($role as $r)
                                        <option value="{{ $r->id }}"
                                            {{ isset($user) && $user->role_id == $r->id ? 'selected' : '' }}>
                                            {{ $r->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="password" class="form-label">Password (optional)</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter new password">
                            </div>

                            <div class="mb-3 col-6">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="number" name="contact_number" id="contact_number" class="form-control"
                                    placeholder="Enter contact number" value="{{ old('fee', $user->contact_number) }}">
                            </div>

                            <div class="mb-3 col-6">
                                <label for="fee" class="form-label">Fee</label>
                                <input type="number" name="fee" id="fee" class="form-control"
                                    placeholder="Enter fee" value="{{ old('fee', $user->fee) }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
