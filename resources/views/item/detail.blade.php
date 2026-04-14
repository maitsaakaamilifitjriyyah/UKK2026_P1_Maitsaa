@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-12 my-4">
                            @if (session('success'))
                                <div class="alert alert-success shadow">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="card shadow border-0">
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-stretch">
                                        <div class="flex-shrink-0 px-4 py-3">
                                            @if ($item->photo_path)
                                                <img src="{{ asset('storage/' . $item->photo_path) }}" alt="Tool Image"
                                                    style="width: 120px; height: 120px; object-fit: cover; display: block;">
                                            @else
                                                <img src="{{ asset('images/default-tool.png') }}" alt="Default Tool Image"
                                                    style="width: 120px; height: 120px; object-fit: cover; display: block;">
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 px-4 py-3 d-flex flex-column justify-content-center">
                                            <p class="text-muted mb-1"
                                                style="font-size: 12px; letter-spacing: 1px; text-transform: uppercase; font-weight: 600;">
                                                Detail Tool</p>
                                            <h2 class="h5 fw-semibold mb-3">{{ $item->name }}</h2>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <p class="mb-0"
                                                        style="font-size: 12px; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Code</p>
                                                    <p class="mb-0 fw-medium" style="font-size: 16px;">
                                                        {{ $item->code_slug }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-0"
                                                        style="font-size: 12px; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Item Type</p>
                                                    <p class="mb-0 fw-medium" style="font-size: 16px;">
                                                        {{ $item->item_type }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-0"
                                                        style="font-size: 12px; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Category</p>
                                                    <p class="mb-0 fw-medium" style="font-size: 16px;">
                                                        {{ $item->category->name }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-0"
                                                        style="font-size: 12px; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Total Unit</p>
                                                    <p class="mb-0 fw-medium" style="font-size: 16px;">
                                                        {{ $item->units->count() }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-0"
                                                        style="font-size: 12px; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Location</p>
                                                    <p class="mb-0 fw-medium" style="font-size: 16px;">
                                                        {{ $item->location->name . ' - ' . $item->location->detail }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-0"
                                                        style="font-size: 12px; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Description</p>
                                                    <p class="mb-0 fw-medium" style="font-size: 16px;">
                                                        {{ $item->description }}</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end section -->
                    <div class="row">
                        <!-- Striped rows -->
                        <div class="col-md-12 my-4">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="toolbar row mb-3">
                                        <div class="col ml-auto">
                                            <div class="dropdown float-right">
                                                <button class="btn btn-primary float-right ml-3" type="button"
                                                    data-toggle="modal" data-target="#verticalModal">Add Unit</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- table -->
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr role="row">
                                                <th>No</th>
                                                <th>Unit Code</th>
                                                <th>Status</th>
                                                <th>Condition</th>
                                                <th>Notes</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($item->units as $index => $unit)
                                                <tr role="row">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $unit->code }}</td>
                                                    <td>
                                                        @if ($unit->status == 'available')
                                                            <span class="badge badge-success">Available</span>
                                                        @elseif ($unit->status == 'nonactive')
                                                            <span class="badge badge-danger">Non-Active</span>
                                                        @elseif ($unit->status == 'lent')
                                                            <span class="badge badge-warning">Lent</span>
                                                        @else
                                                            <span class="badge badge-secondary">Unknown</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($unit->condition->conditions == 'good')
                                                            <span class="badge badge-success">Good</span>
                                                        @elseif ($unit->condition->conditions == 'broken')
                                                            <span class="badge badge-danger">Broken</span>
                                                        @elseif ($unit->condition->conditions == 'maintenance')
                                                            <span class="badge badge-warning">Maintenance</span>
                                                        @else
                                                            <span class="badge badge-secondary">Unknown</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $unit->notes ?? '-' }}</td>
                                                    <td><button class="btn btn-sm dropdown-toggle more-horizontal"
                                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <span class="text-muted sr-only">Action</span>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="#"
                                                                onclick="openEditModal('{{ $unit->code }}', '{{ $unit->status }}', '{{ $unit->condition->conditions ?? '' }}', '{{ $unit->notes ?? '' }}')">
                                                                Edit
                                                            </a>
                                                            <a class="dropdown-item" href="#"
                                                                onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this unit?')) { document.getElementById('delete-form-{{ $unit->code }}').submit(); }">
                                                                Delete
                                                            </a>
                                                            <form id="delete-form-{{ $unit->code }}"
                                                                action="{{ route('unit.destroy', $unit->code) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        There are no units available for this tool.
                                                    </td>
                                                </tr>
                                            @endforelse
                                            <tr>
                                                <td><span class="badge badge-warning">Pending</span></td>
                                            </tr>
                                            <tr role="group" class="bg-light">
                                                <td colspan="10"><strong>Shipped</strong></td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">Success</span></td>
                                            </tr>
                                            <tr role="group" class="bg-light">
                                                <td colspan="10"><strong>Return</strong></td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-danger">Hold</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-primary">Processing</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- simple table -->
                    </div> <!-- .col-12 -->
                </div> <!-- .row -->
            </div> <!-- .container-fluid -->
    </main>
    <div class="card-body">
        <div class="modal fade" id="verticalModal" tabindex="-1" role="dialog" aria-labelledby="verticalModalTitle"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verticalModalTitle">Form
                            Unit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('unit.store') }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <input type="hidden" name="tool_id" value="{{ $item->id }}">
                                        <label for="simpleinput">Code</label>
                                        <input type="text" name="code" class="form-control"
                                            value="{{ $item->code_slug }}-{{ str_pad($item->units->count() + 1, 3, '0', STR_PAD_LEFT) }}"
                                            readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="custom-select">Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="">-- Select Status --</option>
                                            <option value="available">Available</option>
                                            <option value="nonactive">Unavailable</option>
                                            <option value="lent">Lent</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="custom-select">Condition</label>
                                        <select id="editConditions" name="conditions" class="form-control" required>
                                            <option value="">-- Select Condition --</option>
                                            <option value="good">Good</option>
                                            <option value="broken">Broken</option>
                                            <option value="maintenance">Maintenance</option>
                                        </select>
                                        @error('condition')
                                            <div class="text-danger mt-1">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="simpleinput">Notes</label>
                                        <input type="text" name="notes" class="form-control" placeholder="Add notes">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn mb-2 btn-primary">Add Unit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Unit</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="editForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Code</label>
                                    <input type="text" id="editCode" name="code" class="form-control" readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Status</label>
                                    <select id="editStatus" name="status" class="form-control" required>
                                        <option value="">-- Select Status --</option>
                                        <option value="available">Available</option>
                                        <option value="nonactive">Unavailable</option>
                                        <option value="lent">Lent</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Condition</label>
                                    <select id="editConditions" name="conditions" class="form-control" required>
                                        <option value="">-- Select Condition --</option>
                                        <option value="good">Good</option>
                                        <option value="broken">Broken</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Notes</label>
                                    <input type="text" id="editNotes" name="notes" class="form-control"
                                        placeholder="Add notes">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function openEditModal(code, status, conditions, notes) {
            $('#editCode').val(code);
            $('#editStatus').val(status);
            $('#editConditions').val(conditions);
            $('#editNotes').val(notes);
            $('#editForm').attr('action', '/unit/' + code);
            $('#editModal').modal('show');
        }
    </script>
@endsection
