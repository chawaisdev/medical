@extends('layouts.app')

@section('title', 'Services')

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Services Index</li>
                </ol>
            </nav>
            <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="openCreateModal()" data-bs-toggle="modal"
                data-bs-target="#serviceModal">
                Add Service
            </a>

        </div>

        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title">All Services</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $index => $service)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $service->name }}</td>
                                        <td>{{ $service->price }}</td>
                                        <td>
                                            <!-- Edit Icon -->
                                            <button class="btn btn-sm btn-warning me-1" title="Edit"
                                                onclick="openEditModal({{ $service->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Delete Icon -->
                                            <form action="{{ route('services.destroy', $service->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No services found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form id="serviceForm" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Add Service</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="service_id">
                            <div class="mb-3">
                                <label for="name" class="form-label">Service Name</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" id="price" name="price" class="form-control" required
                                    step="0.01">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').innerText = "Add Service";
            document.getElementById('serviceForm').action = "{{ route('services.store') }}";
            document.getElementById('name').value = '';
            document.getElementById('price').value = '';
        }

        function openEditModal(id) {
            fetch(`/services/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('modalTitle').innerText = "Edit Service";
                    document.getElementById('serviceForm').action = `/services/${id}`;
                    document.getElementById('serviceForm').insertAdjacentHTML('beforeend', '@method('PUT')');
                    document.getElementById('name').value = data.name;
                    document.getElementById('price').value = data.price;
                    new bootstrap.Modal(document.getElementById('serviceModal')).show();
                });
        }
        document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
            button.addEventListener('click', () => {
                modal.hide();
            });
        });
    </script>
@endsection
