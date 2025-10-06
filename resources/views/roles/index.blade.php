@extends('layouts.app')

@section('title')
    Roles
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Roles Index</li>
                </ol>
            </nav>
            <button type="button" class="btn btn-primary btn-sm" onclick="openRoleModal()">
                Add Roles
            </button>
        </div>

        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title">All Roles</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Name</th>
                                    <th>Permissions</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jobroles as $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @foreach ($role->permissions as $perm)
                                                <span class="badge bg-primary">{{ $perm->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $role->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-role-id="{{ $role->id }}"
                                                onclick="openRoleModal(this)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @php
                                                $hasPatientUsers = \App\Models\User::where('role_id', $role->id)
                                                    ->where('user_type', 'patient')
                                                    ->exists();
                                            @endphp
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')"
                                                    {{ $hasPatientUsers ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Role Modal -->
                <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" id="roleForm">
                            @csrf
                            <input type="hidden" name="_method" id="formMethod" value="POST">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitle">Add Role</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="role_name">Role Name</label>
                                        <input type="text" class="form-control" name="name" id="role_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Assign Permissions</label>
                                        <div class="border rounded p-2">
                                            @foreach (['Dashboard', 'User Management', 'Roles', 'Clinics','Patients Reports', 'Services', 'All Patients List', 'Refunds'] as $perm)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                                        value="{{ $perm }}" id="perm_{{ $perm }}">
                                                    <label class="form-check-label"
                                                        for="perm_{{ $perm }}">{{ $perm }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                                    <button type="submit" class="btn btn-primary">Save Role</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openRoleModal(button = null) {
            const modal = new bootstrap.Modal(document.getElementById('roleModal'));
            const form = $('#roleForm');
            form[0].reset();
            $('input[name="permissions[]"]').prop('checked', false);
            $('input[name="dashboard_access"]').prop('checked', false);

            if (button) {
                const roleId = button.getAttribute('data-role-id');
                $('#modalTitle').text('Edit Role');
                $('#formMethod').val('PUT');
                form.attr('action', `{{ route('roles.update', ':id') }}`.replace(':id', roleId));

                $.ajax({
                    url: `/roles/${roleId}/edit`,
                    method: 'GET',
                    success: function(response) {
                        $('#role_name').val(response.name);
                        // Auto-fill permissions checkboxes
                        response.permissions.forEach(function(permission) {
                            $(`input[name="permissions[]"][value="${permission}"]`).prop('checked',
                                true);
                        });
                        // Auto-fill dashboard access radio button
                        if (response.dashboard_access) {
                            $(`input[name="dashboard_access"][value="${response.dashboard_access}"]`).prop(
                                'checked', true);
                        }
                        modal.show();
                    },
                    error: function() {
                        alert('Failed to load role data.');
                    }
                });
            } else {
                $('#modalTitle').text('Add Role');
                $('#formMethod').val('POST');
                form.attr('action', `{{ route('roles.store') }}`);
                modal.show();
            }
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
                button.addEventListener('click', () => {
                    modal.hide();
                });
            });

        }
    </script>
@endsection
