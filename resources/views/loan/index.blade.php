@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row">
                        <!-- Striped rows -->
                        <div class="col-md-12 my-4">
                            <h2 class="h4 mb-1">Table Loan</h2>
                            @if (session('success'))
                                <div class="alert alert-success shadow">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="card shadow">
                                <div class="card-body">
                                    @if ($role === 'user')
                                        <div class="toolbar row mb-3">
                                            <div class="col ml-auto">
                                                <div class="dropdown float-right">
                                                    <button class="btn btn-primary float-right ml-3" type="button"
                                                        data-toggle="modal" data-target="#verticalModal">Add Loan</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- table -->
                                    @php $userRole = strtolower(auth()->user()->role); @endphp
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr role="row">
                                                <th>No</th>
                                                <th>Item</th>
                                                <th>Unit</th>
                                                <th>Status</th>
                                                <th>Purpose</th>
                                                <th>Loan Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pending as $index => $loan)
                                                <tr role="row">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $loan->item->name ?? 'N/A' }}</td>
                                                    <td>{{ $loan->toolUnit->code ?? 'N/A' }}</td>
                                                    <td>
                                                        @if ($loan->status === 'pending')
                                                            <span class="badge badge-warning">Pending</span>
                                                        @elseif ($loan->status === 'approved')
                                                            <span class="badge badge-success">Approved</span>
                                                        @elseif ($loan->status === 'rejected')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @elseif ($loan->status === 'returned')
                                                            <span class="badge badge-info">Returned</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $loan->purpose ?? 'N/A' }}</td>
                                                    <td>{{ $loan->loan_date }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">There are no loan
                                                        data.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    @if ($active->isNotEmpty())
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr role="group" class="bg-light">
                                                    <td colspan="10"><strong>Approved</strong></td>
                                                </tr>
                                                <tr role="row">
                                                    <th>No</th>
                                                    <th>Item</th>
                                                    <th>Unit</th>
                                                    <th>Status</th>
                                                    <th>Purpose</th>
                                                    <th>Loan Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($active as $index => $loan)
                                                    <tr role="row">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $loan->item->name ?? 'N/A' }}</td>
                                                        <td>{{ $loan->toolUnit->code ?? 'N/A' }}</td>
                                                        <td>
                                                            @if ($loan->status === 'pending')
                                                                <span class="badge badge-warning">Pending</span>
                                                            @elseif ($loan->status === 'approved')
                                                                <span class="badge badge-success">Approved</span>
                                                            @elseif ($loan->status === 'rejected')
                                                                <span class="badge badge-danger">Rejected</span>
                                                            @elseif ($loan->status === 'returned')
                                                                <span class="badge badge-info">Returned</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $loan->purpose ?? 'N/A' }}</td>
                                                        <td>{{ $loan->loan_date }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">There are no loan
                                                            data.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    @endif
                                    @if ($rejected->isNotEmpty())
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr role="group" class="bg-light">
                                                    <td colspan="10"><strong>Rejected</strong></td>
                                                </tr>
                                                <tr role="row">
                                                    <th>No</th>
                                                    <th>Item</th>
                                                    <th>Unit</th>
                                                    <th>Status</th>
                                                    <th>Purpose</th>
                                                    <th>Loan Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($rejected as $index => $loan)
                                                    <tr role="row">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $loan->item->name ?? 'N/A' }}</td>
                                                        <td>{{ $loan->toolUnit->code ?? 'N/A' }}</td>
                                                        <td>
                                                            @if ($loan->status === 'pending')
                                                                <span class="badge badge-warning">Pending</span>
                                                            @elseif ($loan->status === 'approved')
                                                                <span class="badge badge-success">Approved</span>
                                                            @elseif ($loan->status === 'rejected')
                                                                <span class="badge badge-danger">Rejected</span>
                                                            @elseif ($loan->status === 'returned')
                                                                <span class="badge badge-info">Returned</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $loan->purpose ?? 'N/A' }}</td>
                                                        <td>{{ $loan->loan_date }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">There are no
                                                            loan
                                                            data.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    @endif
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
                        <h5 class="modal-title" id="verticalModalTitle">Application Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('loan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="custom-select">Item</label>
                                        <select name="tool_id" class="form-control" required>
                                            <option value="">-- Select Item --</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('tool_id')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="simpleinput">Purpose</label>
                                        <input type="text" name="purpose" class="form-control"
                                            placeholder="Add purpose">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="simpleinput">Return Date</label>
                                        <input type="date" name="due_date" class="form-control"
                                            placeholder="Add return date">
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="custom-select">Unit</label>
                                        <select id="editUnit" name="unit_code" class="form-control" required>
                                            <option value="">-- Select Unit --</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->code }}">{{ $unit->code }}</option>
                                            @endforeach
                                        </select>
                                        @error('unit_id')
                                            <div class="text-danger mt-1">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="simpleinput">Loan Date</label>
                                        <input type="date" name="loan_date" class="form-control"
                                            placeholder="Add loan date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn mb-2 btn-primary">Apply</button>
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
                                    <label>Purpose</label>
                                    <input type="text" id="editPurpose" name="purpose" class="form-control"
                                        placeholder="Add purpose">
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
