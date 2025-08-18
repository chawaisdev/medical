@extends('layouts.app')

@section('title')
    Roles
@endsection

@section('body')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Roles</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Roles</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Roles List</h5>
                    <a href="javascript:void(0);" onclick="openRoleModal()" class="btn btn-primary btn-sm">Add Role</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
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
                                        @foreach ($role->permission as $perm)
                                            <span class="badge bg-primary">{{ $perm->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $role->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning"
                                            onclick='openRoleModal(@json([
                                                "id" => $role->id,
                                                "name" => $role->name,
                                                "permissions" => $role->permission->pluck("name")
                                            ]))'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No roles found.</td></tr>
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
                                        @foreach (['Dashboard', 'Manage Employees', 'Leave Requests', 'Sick Leave', 'Shift', 'Category', 'Roles'] as $perm)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm }}" id="perm_{{ $loop->index }}">
                                                <label class="form-check-label" for="perm_{{ $loop->index }}">{{ $perm }}</label>
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
function openRoleModal(role = null) {
    const modal = new bootstrap.Modal(document.getElementById('roleModal'));
    $('#roleForm')[0].reset();
    $('input[name="permissions[]"]').prop('checked', false);

    if (role) {
        $('#modalTitle').text('Edit Role');
        $('#formMethod').val('PUT');
        $('#roleForm').attr('action', `/roles/${role.id}`);
        $('#role_name').val(role.name);

        if (Array.isArray(role.permissions)) {
            role.permissions.forEach(function (perm) {
                $(`input[name="permissions[]"][value="${perm}"]`).prop('checked', true);
            });
        }
    } else {
        $('#modalTitle').text('Add Role');
        $('#formMethod').val('POST');
        $('#roleForm').attr('action', `{{ route('roles.store') }}`);
    }

    modal.show();
}
</script>
@endsection
