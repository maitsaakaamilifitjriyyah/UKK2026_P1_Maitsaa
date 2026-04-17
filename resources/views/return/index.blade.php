@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-12 my-4">
                            <h2 class="h4 mb-1">Table Returns</h2>

                            @if (session('success'))
                                <div class="alert alert-success shadow">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="card shadow">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Peminjam</th>
                                                <th>Item</th>
                                                <th>Unit</th>
                                                <th>Return Date</th>
                                                <th>Photo</th>
                                                <th>Notes</th>
                                                <th>Status</th>
                                                @if (strtolower(auth()->user()->role) === 'employee')
                                                    <th>Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($returns as $index => $ret)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $ret->loan->user->email ?? 'N/A' }}</td>
                                                    <td>{{ $ret->loan->item->name ?? 'N/A' }}</td>
                                                    <td>{{ $ret->loan->unit_code ?? 'N/A' }}</td>
                                                    <td>{{ $ret->return_date }}</td>
                                                    <td>
                                                        @if ($ret->path_photo)
                                                            <a href="{{ asset('storage/' . $ret->path_photo) }}"
                                                                target="_blank">
                                                                <img src="{{ asset('storage/' . $ret->path_photo) }}"
                                                                    width="60" class="rounded">
                                                            </a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $ret->notes ?? '-' }}</td>
                                                    <td>
                                                        @if ($ret->condition_id)
                                                            @php $cond = $ret->condition->conditions ?? '-'; @endphp
                                                            @if ($cond === 'good')
                                                                <span class="badge badge-success">Good</span>
                                                            @elseif ($cond === 'maintenance')
                                                                <span class="badge badge-warning">Maintenance</span>
                                                            @elseif ($cond === 'broken')
                                                                <span class="badge badge-danger">Broken</span>
                                                            @else
                                                                <span class="badge badge-secondary">-</span>
                                                            @endif
                                                        @else
                                                            <span class="badge badge-secondary">Haven't Checked</span>
                                                        @endif
                                                    </td>
                                                    @if (strtolower(auth()->user()->role) === 'employee')
                                                        <td>
                                                            @if (!$ret->condition_id)
                                                                <button class="btn btn-sm dropdown-toggle more-horizontal"
                                                                    type="button" data-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    <span class="text-muted sr-only">Action</span>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a class="dropdown-item" href="#"
                                                                        onclick="openCheckModal({{ $ret->id }}, '{{ $ret->path_photo }}', {{ $ret->loan->item->price ?? 0 }})">
                                                                        Check Condition
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <span class="text-muted">The Return has been checked</span>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted">
                                                        There are no returns yet. Please check back later.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Cek Kondisi (Employee) --}}
    <div class="modal fade" id="checkConditionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="checkConditionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Proof Photo of Borrower</label><br>
                                    <img id="modalPhoto" src="" alt="Return Photo"
                                        style="max-width: 100%; max-height: 200px; border-radius: 6px; display:none;">
                                    <p id="noPhoto" class="text-muted" style="display:none;">
                                        There is no photo available.
                                    </p>
                                </div>

                                {{-- Denda: hanya muncul jika kondisi = broken --}}
                                <div class="form-group mb-3" id="fineWrapper" style="display: none;">
                                    <label>Persentase Denda</label>
                                    {{-- FIX: pakai name="fine_percentage" supaya terkirim ke controller --}}
                                    <select id="finePercent" name="fine_percentage" class="form-control">
                                        <option value="">-- Select Percentage --</option>
                                        <option value="10">10%</option>
                                        <option value="25">25%</option>
                                        <option value="50">50%</option>
                                        <option value="75">75%</option>
                                        <option value="100">100%</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Condition</label>
                                    <select id="conditionSelect" name="conditions" class="form-control" required>
                                        <option value="">-- Select Condition --</option>
                                        <option value="good">Good</option>
                                        <option value="broken">Broken</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3" id="notesWrapper" style="display: none;">
                                    <label>Notes / Denda</label>
                                    <textarea id="notesField" name="notes" class="form-control"
                                        placeholder="Masukkan catatan..." rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var currentPrice = 0;

        document.getElementById('conditionSelect').addEventListener('change', function () {
            var selected     = this.value;
            var notesWrapper = document.getElementById('notesWrapper');
            var fineWrapper  = document.getElementById('fineWrapper');

            if (selected === 'maintenance' || selected === 'broken') {
                notesWrapper.style.display = 'block';
            } else {
                notesWrapper.style.display = 'none';
                document.getElementById('notesField').value = '';
            }

            if (selected === 'broken') {
                fineWrapper.style.display = 'block';
            } else {
                fineWrapper.style.display = 'none';
                document.getElementById('finePercent').value = '';
            }
        });

        document.getElementById('finePercent').addEventListener('change', function () {
            var percent    = parseFloat(this.value);
            var notesField = document.getElementById('notesField');

            if (!percent || !currentPrice) return;

            var fine = (currentPrice * percent) / 100;
            notesField.value = 'Denda ' + percent + '% dari harga barang: Rp ' + fine.toLocaleString('id-ID');
        });

        function openCheckModal(returnId, photoPath, price) {
            currentPrice = price;

            document.getElementById('checkConditionForm').action = '/returns/' + returnId + '/check';

            var img     = document.getElementById('modalPhoto');
            var noPhoto = document.getElementById('noPhoto');
            if (photoPath) {
                img.src               = '/storage/' + photoPath;
                img.style.display     = 'block';
                noPhoto.style.display = 'none';
            } else {
                img.style.display     = 'none';
                noPhoto.style.display = 'block';
            }

            // Reset semua field
            document.getElementById('conditionSelect').value      = '';
            document.getElementById('finePercent').value          = '';
            document.getElementById('notesField').value           = '';
            document.getElementById('notesWrapper').style.display = 'none';
            document.getElementById('fineWrapper').style.display  = 'none';

            $('#checkConditionModal').modal('show');
        }
    </script>
@endsection