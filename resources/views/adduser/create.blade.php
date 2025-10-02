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
                            </div>

                            <div class="mb-3 col-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email"
                                    value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="user_type" class="form-label">User Type</label>
                                <select name="user_type" id="user_type" class="form-select" required>
                                    <option value="">-- Select User Type --</option>
                                    <option value="doctor">Doctor</option>
                                    <option value="reception">Reception</option>
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="role_id" class="form-label">User Role</label>
                                <select name="role_id" id="role_id" class="form-select" required>
                                    <option value="">-- Select Role --</option>
                                    @foreach ($role as $r)
                                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password"
                                    required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" name="contact_number" class="form-control" placeholder="Enter contact number"
                                    required>
                            </div>
                        </div>

                        <div id="doctor_fields" style="display: none;">
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="fee" class="form-label">Doctor Fee</label>
                                    <input type="number" name="fee" class="form-control" placeholder="Fee">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('user_type').addEventListener('change', function() {
            let doctorFields = document.getElementById('doctor_fields');
            if (this.value === 'doctor') {
                doctorFields.style.display = 'block';
            } else {
                doctorFields.style.display = 'none';
            }
        });
    </script>
@endsection
