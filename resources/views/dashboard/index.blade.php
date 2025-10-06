@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
        @if (auth()->user()->user_type === 'admin')
            <div class="row">
                {{-- Total Users --}}
                <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-top">
                                <div class="me-3">
                                    <span class="avatar avatar-md p-3 bg-primary text-white rounded-circle">
                                        <i class="fas fa-users fa-lg"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h5 class="fw-semibold mb-0 lh-1">{{ number_format($totalUsers) }}</h5>
                                    <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Total Users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Doctors --}}
                <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-top">
                                <div class="me-3">
                                    <span class="avatar avatar-md p-3 bg-info text-white rounded-circle">
                                        <i class="fas fa-user-md fa-lg"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h5 class="fw-semibold mb-0 lh-1">{{ number_format($totalDoctors) }}</h5>
                                    <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Total Doctors</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Receptions --}}
                <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-top">
                                <div class="me-3">
                                    <span class="avatar avatar-md p-3 bg-warning text-white rounded-circle">
                                        <i class="fas fa-concierge-bell fa-lg"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h5 class="fw-semibold mb-0 lh-1">{{ number_format($totalReceptions) }}</h5>
                                    <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Total Receptions</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Patients --}}
                <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-top">
                                <div class="me-3">
                                    <span class="avatar avatar-md p-3 bg-secondary text-white rounded-circle">
                                        <i class="fas fa-user-injured fa-lg"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h5 class="fw-semibold mb-0 lh-1">{{ number_format($totalPatients) }}</h5>
                                    <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Total Patients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @endif

        {{-- Total Sales --}}
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-top">
                        <div class="me-3">
                            <span class="avatar avatar-md p-3 bg-primary text-white rounded-circle">
                                <i class="fas fa-chart-line fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <h5 class="fw-semibold mb-0 lh-1">{{ number_format($totalSales) }}</h5>
                            <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Total Sales</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Doctor Refund --}}
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-top">
                        <div class="me-3">
                            <span class="avatar avatar-md p-3 bg-danger text-white rounded-circle">
                                <i class="fas fa-stethoscope fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <h5 class="fw-semibold mb-0 lh-1">{{ number_format($totalDoctorRefund) }}</h5>
                            <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Doctor Refund</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Service Refund --}}
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-top">
                        <div class="me-3">
                            <span class="avatar avatar-md p-3 bg-danger text-white rounded-circle">
                                <i class="fas fa-concierge-bell fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <h5 class="fw-semibold mb-0 lh-1">{{ number_format($totalServiceRefund) }}</h5>
                            <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Service Refund</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profit --}}
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-top">
                        <div class="me-3">
                            <span class="avatar avatar-md p-3 bg-success text-white rounded-circle">
                                <i class="fas fa-dollar-sign fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <h5 class="fw-semibold mb-0 lh-1">{{ number_format($profit) }}</h5>
                            <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Profit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                @if (auth()->user()->user_type === 'admin')
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="fw-bold mb-3">Today’s Overview</h5>
                </div>

                {{-- Today’s Sales --}}
                <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-top">
                                <div class="me-3">
                                    <span class="avatar avatar-md p-3 bg-primary text-white rounded-circle">
                                        <i class="fas fa-chart-line fa-lg"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h5 class="fw-semibold mb-0 lh-1">{{ number_format($todaySales) }}</h5>
                                    <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Today’s Sales</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Today’s Doctor Refund --}}
                <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-top">
                                <div class="me-3">
                                    <span class="avatar avatar-md p-3 bg-danger text-white rounded-circle">
                                        <i class="fas fa-stethoscope fa-lg"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h5 class="fw-semibold mb-0 lh-1">{{ number_format($todayDoctorRefund) }}</h5>
                                    <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Today’s Doctor Refund</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Today’s Service Refund --}}
                <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-top">
                                <div class="me-3">
                                    <span class="avatar avatar-md p-3 bg-warning text-white rounded-circle">
                                        <i class="fas fa-concierge-bell fa-lg"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h5 class="fw-semibold mb-0 lh-1">{{ number_format($todayServiceRefund) }}</h5>
                                    <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Today’s Service Refund</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Today’s Profit --}}
                <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-top">
                                <div class="me-3">
                                    <span class="avatar avatar-md p-3 bg-success text-white rounded-circle">
                                        <i class="fas fa-dollar-sign fa-lg"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <h5 class="fw-semibold mb-0 lh-1">{{ number_format($todayProfit) }}</h5>
                                    <p class="mb-0 fs-10 op-7 text-muted fw-semibold">Today’s Profit</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row">
        {{-- Top Doctors List --}}
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Top Doctors List</div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach ($topDoctors as $doctor)
                            <li class="mb-3">
                                <div class="d-flex align-items-top flex-wrap">
                                    <div class="flex-fill">
                                        <p class="fw-semibold mb-0">{{ $doctor->name }}</p>
                                        <span class="text-muted fs-12">{{ $doctor->email }}</span>
                                    </div>
                                    <div class="fw-semibold fs-13">
                                        <span class="badge bg-primary text-white text-capitalize">Doctor</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">User Distribution</div>
                </div>
                <div class="card-body">
                    <canvas id="userChart" height="320"></canvas>
                </div>
            </div>
        </div>

    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('userChart').getContext('2d');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Doctors', 'Patients', 'Receptionists'],
                datasets: [{
                    data: [{{ $totalDoctors }}, {{ $totalPatients }}, {{ $totalReceptions }}],
                    backgroundColor: [
                        '#BB9351', // green
                        '#2196F3', // blue
                        '#FFC107' // yellow
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        enabled: true
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 15,
                            padding: 15
                        }
                    }
                },
                animation: {
                    animateScale: false, // disables zoom effect
                    animateRotate: true
                },
                hover: {
                    mode: null // disables hover zoom
                }
            }
        });
    </script>
@endsection
