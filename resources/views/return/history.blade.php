@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-12 my-4">
                            <h2 class="h4 mb-1">History</h2>

                            @if (session('success'))
                                <div class="alert alert-success shadow">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="card shadow">
                                <div class="card-body">
                                    @if ($role !== 'user')
                                    <div class="toolbar mb-3">
                                        <div class="float-right">
                                            <a href="{{ route('return.history.export') }}" class="btn btn-primary btn-sm">
                                                Export Excel
                                                <span class="fe fe-download fe-16"></span>
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    @endif

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Date</th>
                                                @if ($role !== 'user')
                                                    <th>Peminjam</th>
                                                @endif
                                                <th>Item</th>
                                                <th>Unit</th>
                                                <th>Loan Date</th>
                                                <th>Due Date</th>
                                                <th>Return Date</th>
                                                <th>Activity</th>
                                                <th>Condition</th>
                                                <th>Fine</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($history as $index => $h)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($h['date'])->format('d M Y') }}</td>
                                                    @if ($role !== 'user')
                                                        <td>{{ $h['user'] }}</td>
                                                    @endif
                                                    <td>{{ $h['item'] }}</td>
                                                    <td>{{ $h['unit'] }}</td>
                                                    <td>{{ $h['loan_date'] }}</td>
                                                    <td>{{ $h['due_date'] }}</td>
                                                    <td>{{ $h['return_date'] !== '-' ? \Carbon\Carbon::parse($h['return_date'])->format('d M Y') : '-' }}</td>
                                                    <td>
                                                        @if ($h['type'] === 'rejected')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @elseif ($h['type'] === 'returned_good')
                                                            <span class="badge badge-success">Returned — Good</span>
                                                        @elseif ($h['type'] === 'returned_maintenance')
                                                            <span class="badge badge-warning">Returned — Maintenance</span>
                                                        @elseif ($h['type'] === 'returned_broken')
                                                            <span class="badge badge-danger">Returned — Broken</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ $h['type'] }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($h['condition'] === 'good')
                                                            <span class="badge badge-success">Good</span>
                                                        @elseif ($h['condition'] === 'maintenance')
                                                            <span class="badge badge-warning">Maintenance</span>
                                                        @elseif ($h['condition'] === 'broken')
                                                            <span class="badge badge-danger">Broken</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($h['fine'] !== '-')
                                                            <span class="text-danger font-weight-bold">{{ $h['fine'] }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $h['notes'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12" class="text-center text-muted">
                                                        There is no return history yet.
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
@endsection