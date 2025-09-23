@extends('layouts.app')

@section('title')
    Clinic Schedules
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Clinic Schedules</li>
                </ol>
            </nav>
            <button class="btn btn-primary btn btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">+ Add
                Schedule</button>
        </div>

        <div class="card custom-card">
            <div class="card-header">
                <h6 class="card-title">Clinic Schedule List</h6>
            </div>
            <div class="card-body table-responsive">
                <table  id="example" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clinics as $index => $clinic)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $clinic->day }}</td>
                                <td> <i>{{ $clinic->start_time ?? 'No set time' }}</i></td>
                                <td><i>{{ $clinic->end_time ?? 'No set time' }}</i></td>
                                <td>
                                    <span class="badge {{ $clinic->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $clinic->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('clinic.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Weekly Clinic Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Day</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Off</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                        @php
                                            $entry = $clinics->firstWhere('day', $day);
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="hidden" name="days[]" value="{{ $day }}">
                                                <input type="hidden" name="should_save[{{ $day }}]"
                                                    id="save_{{ $day }}" value="0">
                                                <strong>{{ $day }}</strong>
                                            </td>
                                            <td>
                                                <input type="time" class="form-control"
                                                    name="start_time[{{ $day }}]" id="start_{{ $day }}"
                                                    value="{{ old("start_time.$day", optional($entry)->start_time) }}"
                                                    onchange="enableDay('{{ $day }}')">
                                            </td>
                                            <td>
                                                <input type="time" class="form-control"
                                                    name="end_time[{{ $day }}]" id="end_{{ $day }}"
                                                    value="{{ old("end_time.$day", optional($entry)->end_time) }}"
                                                    onchange="enableDay('{{ $day }}')">
                                            </td>
                                            <td>
                                                <input type="checkbox" class="form-check-input" name="off_days[]"
                                                    value="{{ $day }}" id="off_{{ $day }}"
                                                    onchange="toggleDayFields('{{ $day }}', this)"
                                                    @if (optional($entry)->is_active === 0) checked @endif>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save Weekly Schedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleDayFields(day, checkbox) {
            const startInput = document.getElementById(`start_${day}`);
            const endInput = document.getElementById(`end_${day}`);
            const saveInput = document.getElementById(`save_${day}`);

            if (checkbox.checked) {
                startInput.disabled = true;
                endInput.disabled = true;
                startInput.value = '';
                endInput.value = '';
                saveInput.value = '1'; // Mark as off-day to be saved
            } else {
                startInput.disabled = false;
                endInput.disabled = false;
                saveInput.value = (startInput.value && endInput.value) ? '1' : '0';
            }
        }

        function enableDay(day) {
            const startInput = document.getElementById(`start_${day}`);
            const endInput = document.getElementById(`end_${day}`);
            const offInput = document.getElementById(`off_${day}`);
            const saveInput = document.getElementById(`save_${day}`);

            if (offInput.checked) {
                startInput.disabled = true;
                endInput.disabled = true;
                startInput.value = '';
                endInput.value = '';
                saveInput.value = '1';
            } else if (startInput.value && endInput.value) {
                saveInput.value = '1';
            } else {
                saveInput.value = '0';
            }
        }

        window.addEventListener('load', () => {
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            days.forEach(day => enableDay(day));
        });
    </script>
@endsection
