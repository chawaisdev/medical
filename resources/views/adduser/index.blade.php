@extends('layouts.app')

@section('title')
    User Index
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">User Index</li>
                </ol>
            </nav>
            <a href="{{ route('adduser.create') }}" class="btn btn-primary btn-sm">Add User</a>
        </div>

        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title">All Users</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>User Type</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->user_type }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <a href="{{ route('adduser.show', $user->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <form action="{{ route('adduser.destroy', $user->id) }}" method="POST"
                                                style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i
                                                        class="fa fa-trash"></i></button>
                                            </form>
                                            <a href="{{ route('adduser.edit', $user->id) }}"
                                                class="btn btn-sm btn-warning"><i class="fa fa-pen-to-square"></i></a>
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                data-bs-target="#assignScheduleModal"
                                                onclick="openScheduleAssignModal({{ $user->id }}, '{{ $user->name }}')">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </button>
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

        <!-- Assign Schedule Modal -->
        <div class="modal fade" id="assignScheduleModal" tabindex="-1" aria-labelledby="assignScheduleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('schedule.assign') }}" id="scheduleAssignForm">
                    @csrf
                    <input type="hidden" name="user_id" id="assign_user_id">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Assign Schedule to <span id="employeeName"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Day</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Off</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="days[]" value="{{ $day }}"
                                                        id="day_{{ $day }}">
                                                    <strong>{{ $day }}</strong>
                                                </td>
                                                <td>
                                                    <input type="time" class="form-control"
                                                        name="start_time[{{ $day }}]"
                                                        id="start_{{ $day }}"
                                                        onchange="updateStatus('{{ $day }}')">
                                                </td>
                                                <td>
                                                    <input type="time" class="form-control"
                                                        name="end_time[{{ $day }}]" id="end_{{ $day }}"
                                                        onchange="updateStatus('{{ $day }}')">
                                                </td>
                                                <td>
                                                    <input type="checkbox" class="form-check-input" name="off_days[]"
                                                        value="{{ $day }}" id="off_{{ $day }}"
                                                        onchange="toggleDayFields('{{ $day }}', this)">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary" type="submit">Assign</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openScheduleAssignModal(userId, name) {
            // Set modal header and user ID
            document.getElementById('assign_user_id').value = userId;
            document.getElementById('employeeName').textContent = name;

            const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

            // Clear previous data and enable fields
            days.forEach(day => {
                const start = document.getElementById('start_' + day);
                const end = document.getElementById('end_' + day);
                const off = document.getElementById('off_' + day);

                start.value = '';
                end.value = '';
                start.disabled = false;
                end.disabled = false;
                off.checked = false;
            });

            // Fetch existing schedules
            const url = `/schedule.assign/${userId}/schedules?ts=${new Date().getTime()}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    data.forEach(schedule => {
                        const day = schedule.day;
                        const startInput = document.getElementById('start_' + day);
                        const endInput = document.getElementById('end_' + day);
                        const offCheckbox = document.getElementById('off_' + day);

                        if (schedule.is_active === '0') {
                            offCheckbox.checked = true;
                            startInput.disabled = true;
                            endInput.disabled = true;
                            startInput.value = '';
                            endInput.value = '';
                        } else {
                            offCheckbox.checked = false;
                            startInput.disabled = false;
                            endInput.disabled = false;
                            startInput.value = schedule.start_time?.substring(0, 5) || '';
                            endInput.value = schedule.end_time?.substring(0, 5) || '';
                        }

                    });
                })
                .catch(error => {
                    console.error('Error fetching schedules:', error);
                });
        }

        function toggleDayFields(day, checkbox) {
            const startInput = document.getElementById('start_' + day);
            const endInput = document.getElementById('end_' + day);

            if (checkbox.checked) {
                startInput.disabled = true;
                endInput.disabled = true;
                startInput.value = '';
                endInput.value = '';
            } else {
                startInput.disabled = false;
                endInput.disabled = false;
            }
        }

        function updateStatus(day) {
            const startInput = document.getElementById('start_' + day);
            const endInput = document.getElementById('end_' + day);
            const offCheckbox = document.getElementById('off_' + day);

            if (startInput.value && endInput.value) {
                offCheckbox.checked = false;
                startInput.disabled = false;
                endInput.disabled = false;
            }
        }
    </script>
@endsection
