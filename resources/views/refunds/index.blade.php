@extends('layouts.app')

@section('title', 'Refund Approval')

@section('body')
    <div class="container-fluid">
        {{-- Breadcrumb --}}
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Refund Approval</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card overflow-hidden">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">All Refunds</div>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table id="example" class="table table-hover text-nowrap table-bordered">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Doctor</th>
                                        <th>Patient</th>
                                        <th>Created By</th>
                                        <th>Total Amount</th>
                                        <th>Requested Amount</th>
                                        <th>Doctor Fee Refund</th>
                                        <th>Refunded Services</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @forelse($refunds as $index => $refund)
                                        {{-- yahan $refund hamesha defined hoga --}}
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $refund->appointment->doctor->name ?? 'N/A' }}</td>
                                            <td>{{ $refund->appointment->patient->name ?? 'N/A' }}</td>
                                            <td>{{ $refund->creator->name ?? 'N/A' }}</td>
                                            <td/>{{ number_format($refund->appointment->fee + $refund->appointment->services->sum('price'), 2) }}
                                            </td>
                                            <td>{{ number_format($refund->requested_amount, 2) }}</td>
                                            <td>{{ number_format($refund->doctor_fee_refund, 2) }}</td>
                                            <td>
                                                @forelse ($refund->services as $service)
                                                    <span class="badge bg-success">{{ $service->name }},{{ $service->price }}</span>
                                                @empty
                                                    <span class="text-muted">No Services</span>
                                                @endforelse
                                            </td>
                                            <td>{{ ucfirst($refund->status) }}</td>
                                            <td>
                                                @if ($refund->status === 'pending')
                                                    <div class="dropdown"> <button
                                                            class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                                            id="actionDropdown{{ $refund->id }}" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false"> Action </button>
                                                        <div class="dropdown-menu"
                                                            aria-labelledby="actionDropdown{{ $refund->id }}">
                                                            <form action="{{ route('refunds.approve', $refund->id) }}"
                                                                method="POST" class="px-3 py-1 m-0"> @csrf <input
                                                                    type="hidden" name="approved_amount"
                                                                    value="{{ $refund->requested_amount }}"> <input
                                                                    type="hidden" name="doctor_fee_refund"
                                                                    value="{{ $refund->doctor_fee_refund }}"> <button
                                                                    type="submit"
                                                                    class="dropdown-item text-success">Approve</button>
                                                            </form>
                                                            <form action="{{ route('refunds.reject', $refund->id) }}"
                                                                method="POST" class="px-3 py-1 m-0"> @csrf <button
                                                                    type="submit"
                                                                    class="dropdown-item text-danger">Reject</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-dark">Action Done</span>
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
    </div>
@endsection
