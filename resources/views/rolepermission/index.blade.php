@extends('layouts.app')

@section('title')
    Jobs Category
@endsection

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Job Category</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboards</a></li>
                            <li class="breadcrumb-item active">Job Category</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Jobs Category</h5>
                        <a href="javascript:void(0);" onclick="openRoleModal()" class="btn btn-primary btn-sm">Add Category</a>
                    </div>
                    <div class="card-body">
                        <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead class="bg-light">
                                <tr>
                                    <th>Sr #</th>
                                    <th>Name</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rolepermission as $category)
                                    <tr>
                                        <td class="p-3">{{ $loop->iteration }}</td>
                                        <td class="p-3">{{ $category->name }}</td>
                                        <td class="p-3">{{ $category->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td class="p-3">
                                            <button class="btn btn-sm btn-warning"
                                                onclick='openRoleModal(@json($category))'>
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('rolepermission.destroy', $category->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this category?')">
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
            </div>
        </div>
    </div>

    <!-- Add/Edit Job Category Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" id="roleForm">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Job Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="role_name">Name</label>
                            <input type="text" class="form-control" name="name" id="role_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Category</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function openRoleModal(role = null) {
            const modal = new bootstrap.Modal(document.getElementById('roleModal'));
            $('#roleForm')[0].reset();

            if (role) {
                $('#modalTitle').text('Edit Job Category');
                $('#formMethod').val('PUT');
                $('#roleForm').attr('action', `/rolepermission/${role.id}`);
                $('#role_name').val(role.name);
            } else {
                $('#modalTitle').text('Add Job Category');
                $('#formMethod').val('POST');
                $('#roleForm').attr('action', `{{ route('rolepermission.store') }}`);
            }

            modal.show();
        }
    </script>
@endsection
