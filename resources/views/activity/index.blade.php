@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-12 my-4">
                            <h2 class="h4 mb-1">Activity Log</h2>
                            @if (session('success'))
                                <div class="alert alert-success shadow">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="toolbar">
                                        <form method="GET" action="{{ route('log.index') }}" class="form-inline flex-wrap"
                                        style="gap:8px;">
                                        <select name="module" class="form-control form-control-sm">
                                            <option value="">-- All Module --</option>
                                            @foreach ($modules as $mod)
                                                <option value="{{ $mod }}"
                                                    {{ request('module') == $mod ? 'selected' : '' }}>
                                                    {{ ucfirst($mod) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <select name="action" class="form-control form-control-sm">
                                            <option value="">-- All Action --</option>
                                            @foreach ($actions as $act)
                                                <option value="{{ $act }}"
                                                    {{ request('action') == $act ? 'selected' : '' }}>
                                                    {{ $act }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <input type="date" name="date_from" class="form-control form-control-sm"
                                            value="{{ request('date_from') }}" placeholder="Dari tanggal">

                                        <input type="date" name="date_to" class="form-control form-control-sm"
                                            value="{{ request('date_to') }}" placeholder="Sampai tanggal">

                                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                        <a href="{{ route('log.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                                    </form>
                                    </div>
                                    <!-- table -->
                                    <table class="table table-borderless table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Waktu</th>
                                                <th>User</th>
                                                <th>Module</th>
                                                <th>Action</th>
                                                <th>Description</th>
                                                <th>IP Address</th>
                                                <th>Meta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($logs as $log)
                                                <tr>
                                                    <td>{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td style="white-space:nowrap;">
                                                        {{ $log->created_at ? $log->created_at->format('d M Y H:i') : '-' }}
                                                    </td>
                                                    <td>{{ $log->user->email ?? '<i class="text-muted">system</i>' }}</td>
                                                    <td>
                                                        @php
                                                            $moduleColor = match ($log->module) {
                                                                'loans' => 'primary',
                                                                'returns' => 'info',
                                                                'tools' => 'secondary',
                                                                'users' => 'dark',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="badge badge-{{ $moduleColor }}">{{ $log->module }}</span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $actionColor = match (true) {
                                                                str_contains($log->action, 'approved') => 'success',
                                                                str_contains($log->action, 'rejected') => 'danger',
                                                                str_contains($log->action, 'created') => 'primary',
                                                                str_contains($log->action, 'deleted') => 'danger',
                                                                str_contains($log->action, 'updated') => 'warning',
                                                                str_contains($log->action, 'checked') => 'info',
                                                                str_contains($log->action, 'submitted') => 'secondary',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="badge badge-{{ $actionColor }}">{{ $log->action }}</span>
                                                    </td>
                                                    <td>{{ $log->description }}</td>
                                                    <td>{{ $log->ip_address ?? '-' }}</td>
                                                    <td>
                                                        @if ($log->meta)
                                                            @php $meta = json_decode($log->meta, true); @endphp
                                                            <button class="btn btn-xs btn-outline-secondary"
                                                                onclick="showMeta({{ $log->id }}, {{ $log->meta }})">
                                                                Detail
                                                            </button>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        There is no activity log yet.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="mt-3">
                                        {{ $logs->links() }}
                                    </div>
                                    <nav aria-label="Table Paging" class="mb-0 text-muted">
                                        <ul class="pagination justify-content-center mb-0">
                                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div> <!-- customized table -->
                    </div> <!-- end section -->
                </div> <!-- .col-12 -->
            </div> <!-- .row -->
        </div> <!-- .container-fluid -->
    </main> <!-- main -->
    <div class="modal fade" id="metaModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Meta</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <pre id="metaContent" style="background:#f8f9fa; padding:12px; border-radius:6px; font-size:13px;"></pre>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showMeta(id, meta) {
            document.getElementById('metaContent').textContent = JSON.stringify(meta, null, 2);
            $('#metaModal').modal('show');
        }
    </script>
@endsection
