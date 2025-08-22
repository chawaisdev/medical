@extends('layouts.app')

@section('title')
    Setting Index
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Setting Index</li>
                </ol>
            </nav>
        </div>
        <form action="{{ route('settings.update', ['setting' => Auth::id()]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mb-5">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane show active" id="personal-info" role="tabpanel">
                                    <div class="p-sm-3 p-0">
                                        <div class="row gy-4 mb-4">
                                            <div class="col-xl-6">
                                                <label for="first-name" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="first-name" name="name"
                                                    value="{{ $user->name }}" placeholder="Name">
                                            </div>
                                            <div class="col-xl-12">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email"
                                                    value="{{ $user->email }}" placeholder="Email">
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-label">Password</label>
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="Leave blank to keep current">
                                            <small class="text-muted">Leave password fields blank if you don't want to change your password.</small>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-label">Confirm Password</label>
                                                <input type="password" class="form-control" name="password_confirmation"
                                                    placeholder="Confirm Password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary m-1">Update Setting</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- JS for live preview -->
    <script>
        document.getElementById('profile-img').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById('profile-img-preview').src = URL.createObjectURL(file);
            }
        });
    </script>
@endsection
