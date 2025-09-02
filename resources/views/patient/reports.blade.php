@extends('layouts.app')

@section('title')
    Patient Reports
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Patient Reports</li>
                </ol>
            </nav>
        </div>

        <div class="row g-3">
            @if ($reports->count() > 0)
                @foreach ($reports as $report)
                    @php
                        $extension = pathinfo($report->reports, PATHINFO_EXTENSION);
                        $isPdf = strtolower($extension) === 'pdf';
                    @endphp
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm border-0 report-card">
                            <div class="card-body d-flex flex-column align-items-center text-center p-3">
                                <div class="report-image-wrapper mb-3">
                                    @if ($isPdf)
                                        <img src="{{ asset('assets/images/Pdf.png') }}" alt="PDF File" class="img-fluid">
                                    @else
                                        <img src="{{ asset($report->reports) }}" alt="Report Image" class="img-fluid">
                                    @endif
                                </div>
                                <h6 class="fw-bold mb-1">{{ $report->title }}</h6>
                                <small class="text-muted mb-2">
                                    {{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}
                                </small>
                                <a href="{{ asset($report->reports) }}" download class="btn btn-sm btn-primary w-100">
                                    <i class="fa fa-download me-1"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        No reports available.
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .report-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 10px;
            overflow: hidden;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .report-image-wrapper img {
            width: 100%;
            height: 140px;
            object-fit: contain;
            border-radius: 5px;
        }
    </style>
@endsection
