@extends('layouts.app')

@section('title')
    Patient Index
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Patient Index</li>
                </ol>
            </nav>
            <a href="{{ route('reception.create') }}" class="btn btn-primary btn-sm">Add Patient</a>
        </div>

        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title">All Patient</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Name</th>
                                    <th>Father's / Husband's Name</th>
                                    <th>Age</th>
                                    <th>CNIC</th>
                                    <th>Contact Number</th>
                                    <th>Address</th>
                                    <th>Patient ID / MR Number</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->father_name ?? 'N/A' }}</td>
                                        <td>{{ $user->age ?? 'N/A' }}</td>
                                        <td>{{ $user->cnic ?? 'N/A' }}</td>
                                        <td>{{ $user->contact_number ?? 'N/A' }}</td>
                                        <td>{{ $user->address ?? 'N/A' }}</td>
                                        <td>{{ $user->id ?? 'N/A' }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <form action="{{ route('reception.destroy', $user->id) }}" method="POST"
                                                style="display: inline;"
                                                onsubmit="return confirm('Are you sure you want to delete?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('reception.edit', $user->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fa fa-pen-to-square"></i>
                                            </a>
                                            @if (auth()->check() && auth()->user()->hasPermission('Patients Reports'))
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#patientModal-{{ $user->id }}">
                                                    <i class="fa fa-file-medical"></i> Reports
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($users as $user)
        <div class="modal fade" id="patientModal-{{ $user->id }}" tabindex="-1"
            aria-labelledby="patientModalLabel-{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="patientModalLabel-{{ $user->id }}">Reports for {{ $user->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    @if ($user->reports->count() > 0)
                        <div class="mb-3" style="padding-left:20px;">
                            <h6>Existing Reports</h6>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach ($user->reports as $report)
                                    <div class="card text-center"
                                        style="width: 150px; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}
                                        </small>

                                        @php
                                            $extension = pathinfo($report->reports, PATHINFO_EXTENSION);
                                        @endphp

                                        @if (strtolower($extension) === 'pdf')
                                            <img src="{{ asset('assets/images/Pdf.png') }}" alt="PDF Report"
                                                style="width: 100%; height: auto; max-height: 120px; object-fit: contain; margin-top: 5px;">
                                        @else
                                            <img src="{{ asset($report->reports) }}" alt="Report Image"
                                                style="width: 100%; height: auto; max-height: 120px; object-fit: contain; margin-top: 5px;">
                                        @endif

                                        <div class="mt-2 d-flex justify-content-center gap-2">
                                            <form action="{{ route('patient-reports.destroy', $report->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this report?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p style="padding-left:20px;">No reports found.</p>
                    @endif




                    <div class="modal-body">

                        <form action="{{ route('patients.patientReports') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <div id="report-rows-{{ $user->id }}">
                                <div class="row g-3 align-items-end report-row">
                                    <div class="col-md-4">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Report Date</label>
                                        <input type="date" name="report_date[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Upload Report</label>
                                        <input type="file" name="report_file[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" class="btn btn-success btn-add-row"
                                            data-user="{{ $user->id }}">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Upload Report</button>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
    <script>
        document.addEventListener('click', function(e) {
            // Add new row at the end
            if (e.target.closest('.btn-add-row')) {
                const userId = e.target.closest('.btn-add-row').dataset.user;
                const container = document.getElementById('report-rows-' + userId);
                const firstRow = container.querySelector('.report-row');
                const clone = firstRow.cloneNode(true);

                // Clear inputs
                clone.querySelectorAll('input').forEach(input => input.value = '');

                // Change cloned row's button to minus
                const btn = clone.querySelector('.btn-add-row');
                btn.classList.remove('btn-success', 'btn-add-row');
                btn.classList.add('btn-danger', 'btn-remove-row');
                btn.removeAttribute('data-user');
                btn.innerHTML = '<i class="fa fa-minus"></i>';

                // Append at the bottom
                container.appendChild(clone);
            }

            // Remove a row
            if (e.target.closest('.btn-remove-row')) {
                e.target.closest('.report-row').remove();
            }
        });
    </script>
@endsection
