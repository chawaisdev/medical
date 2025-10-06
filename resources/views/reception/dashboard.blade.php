@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xxl-4 col-lg-4 col-md-4 col-sm-12 mb-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top">
                            <div class="me-3">
                                <span class="avatar avatar-md p-3 bg-success text-white rounded-circle">
                                    <i class="fas fa-users fa-lg"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h5 class="fw-semibold mb-0 lh-1">{{ $totalDoctor }}</h5>
                                <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Total Patient</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Patient List</div>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            @foreach ($dashboard as $patient)
                                <li class="mb-3">
                                    <div class="d-flex align-items-top flex-wrap">
                                        <div class="flex-fill">
                                            <p class="fw-semibold mb-0">{{ $patient->name }}</p>
                                            <span class="text-muted fs-12">{{ $patient->contact_number }}</span>
                                        </div>
                                        <div class="fw-semibold fs-13">
                                            <span class="badge bg-primary text-white text-capitalize">Patient</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $dashboard->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
