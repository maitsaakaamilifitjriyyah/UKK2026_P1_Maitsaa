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
                                    <div class="toolbar row mb-3">
                                        <div class="col ml-auto">
                                            <div class="dropdown float-right">
                                                @if ($role !== 'user')
                                                <a href="{{ route('loan.export') }}" class="btn mb-2 btn-primary">
                                                    Export Excel
                                                    <span class="fe fe-download fe-16"></span>
                                                </a>
                                                @else ($role === 'user')
                                                    <button class="btn btn-primary float-right ml-3" type="button"
                                                        data-toggle="modal" data-target="#verticalModal">Add Loan</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

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
                                                <th>Due Date</th>
                                                @if ($userRole != 'user')
                                                    <th>Action</th>
                                                @endif
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
                                                    <td>{{ $loan->due_date }}</td>
                                                    @if ($userRole === 'admin')
                                                        <td><button class="btn btn-sm dropdown-toggle more-horizontal"
                                                                type="button" data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <span class="text-muted sr-only">Action</span>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="openEditLoanModal({{ $loan->id }}, '{{ $loan->tool_id }}', '{{ $loan->toolUnit->code ?? '' }}', '{{ $loan->purpose ?? '' }}', '{{ $loan->loan_date }}', '{{ $loan->due_date }}')">
                                                                    Edit
                                                                </a>
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="event.preventDefault(); if(confirm('Are you sure?')) { document.getElementById('delete-form-{{ $loan->id }}').submit(); }">
                                                                    Delete
                                                                </a>
                                                                <form id="delete-form-{{ $loan->id }}"
                                                                    action="{{ route('loan.destroy', $loan->id) }}"
                                                                    method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </div>
                                                        </td>
                                                    @endif

                                                    @if ($userRole === 'employee')
                                                        <td><button class="btn btn-sm dropdown-toggle more-horizontal"
                                                                type="button" data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <span class="text-muted sr-only">Action</span>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="openAcceptLoanModal({{ $loan->id }})">
                                                                    Accept
                                                                </a>
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="openRejectLoanModal({{ $loan->id }})">
                                                                    Reject
                                                                </a>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">There are no loan
                                                        data.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    @if ($role === 'user')
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
                                                                @elseif ($loan->status === 'active')
                                                                    <span class="badge badge-success">Approved</span>
                                                                @elseif ($loan->status === 'rejected')
                                                                    <span class="badge badge-danger">Rejected</span>
                                                                @elseif ($loan->status === 'returned')
                                                                    <span class="badge badge-info">Returned</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $loan->purpose ?? 'N/A' }}</td>
                                                            <td>{{ $loan->loan_date }}</td>
                                                            <td><button class="btn btn-sm dropdown-toggle more-horizontal"
                                                                    type="button" data-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    <span class="text-muted sr-only">Action</span>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a class="dropdown-item" href="#"
                                                                        onclick="openReturnLoanModal({{ $loan->id }})">
                                                                        Return
                                                                    </a>
                                                                </div>
                                                            </td>
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
                                    @endif
                                    @if ($role === 'user')
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
                                    @endif
                                </div>
                            </div>
                        </div> <!-- simple table -->
                    </div> <!-- .col-12 -->
                </div> <!-- .row -->
            </div> <!-- .container-fluid -->
    </main>
    {{-- form pengajuan peminjam --}}
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
    {{-- end form pengajuan peminjam --}}

    {{-- modal accept (employee) --}}
    <div class="card-body">
        <div class="modal fade" id="acceptLoanModal" tabindex="-1" role="dialog" aria-labelledby="verticalModalTitle"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verticalModalTitle">Confirm Accept</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="acceptLoanForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="simpleinput">Notes</label>
                                        <textarea name="notes" class="form-control" placeholder="Add notes" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn mb-2 btn-primary">Accept</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal accept --}}

    {{-- modal reject (employee) --}}
    <div class="card-body">
        <div class="modal fade" id="rejectLoanModal" tabindex="-1" role="dialog" aria-labelledby="verticalModalTitle"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verticalModalTitle">Confirm Reject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="rejectLoanForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="simpleinput">Notes</label>
                                        <textarea name="notes" class="form-control" placeholder="Add notes" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn mb-2 btn-primary">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal reject --}}

    {{-- modal retun (employee) --}}
    <div class="card-body">
        <div class="modal fade" id="returnLoanModal" tabindex="-1" role="dialog" aria-labelledby="verticalModalTitle"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verticalModalTitle">Confirm Return</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="returnLoanForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="simpleinput">Bukti</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile"
                                                name="path_photo">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn mb-2 btn-primary">Return</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal return --}}

    <div class="modal fade" id="editLoanModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Loan</h5>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>
                <form id="editLoanForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Item</label>
                                    <select id="editLoanItem" name="tool_id" class="form-control" required>
                                        <option value="">-- Select Item --</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Purpose</label>
                                    <input type="text" id="editLoanPurpose" name="purpose"
                                        class="form-control" placeholder="Add purpose">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Return Date</label>
                                    <input type="date" id="editLoanDueDate" name="due_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Unit</label>
                                    <select id="editLoanUnit" name="unit_code" class="form-control" required>
                                        <option value="">-- Select Unit --</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->code }}">{{ $unit->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Loan Date</label>
                                    <input type="date" id="editLoanDate" name="loan_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAcceptLoanModal(id) {
            $('#acceptLoanForm').attr('action', '/loan/' + id + '/approve');
            $('#acceptLoanModal').modal('show');
        }

        function openRejectLoanModal(id) {
            $('#rejectLoanForm').attr('action', '/loan/' + id + '/reject');
            $('#rejectLoanModal').modal('show');
        }

        function openReturnLoanModal(id) {
            $('#returnLoanForm').attr('action', '/loan/' + id + '/return');
            $('#returnLoanModal').modal('show');
        }

        function openEditLoanModal(id, toolId, unitCode, purpose, loanDate, dueDate) {
            $('#editLoanItem').val(toolId);
            $('#editLoanUnit').val(unitCode);
            $('#editLoanPurpose').val(purpose);
            $('#editLoanDate').val(loanDate);
            $('#editLoanDueDate').val(dueDate);
            $('#editLoanForm').attr('action', '/loan/' + id);
            $('#editLoanModal').modal('show');
        }
    </script>
@endsection
