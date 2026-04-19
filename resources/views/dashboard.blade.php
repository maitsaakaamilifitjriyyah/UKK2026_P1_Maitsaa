@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">

                    <div class="row mt-4">

                        <div class="col-md-6 col-xl-3 mb-4">
                            <a href="{{ route('item.index') }}" class="text-decoration-none">
                                <div class="card shadow border-0 h-100">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-3 text-center">
                                                <span class="circle circle-sm bg-primary">
                                                    <i class="fe fe-16 fe-tool text-white mb-0"></i>
                                                </span>
                                            </div>
                                            <div class="col pr-0">
                                                <p class="small text-muted mb-0">Total Tools</p>
                                                <span class="h3 mb-0">{{ $totalTools }}</span>
                                                <p class="small text-muted mb-0">Tool types</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-0 h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-3 text-center">
                                            <span class="circle circle-sm bg-success">
                                                <i class="fe fe-16 fe-layers text-white mb-0"></i>
                                            </span>
                                        </div>
                                        <div class="col pr-0">
                                            <p class="small text-muted mb-0">Total Units</p>
                                            <span class="h3 mb-0">{{ $totalUnits }}</span>
                                            <p class="small text-muted mb-0">Physical units</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3 mb-4">
                            <a href="{{ route('loan.index') }}" class="text-decoration-none">
                                <div class="card shadow border-0 h-100">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-3 text-center">
                                                <span class="circle circle-sm bg-warning">
                                                    <i class="fe fe-16 fe-shopping-cart text-white mb-0"></i>
                                                </span>
                                            </div>
                                            <div class="col pr-0">
                                                <p class="small text-muted mb-0">On Loan</p>
                                                <span class="h3 mb-0">{{ $totalLent }}</span>
                                                <p class="small text-muted mb-0">Active units on loan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- Broken --}}
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-0 h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-3 text-center">
                                            <span class="circle circle-sm bg-danger">
                                                <i class="fe fe-16 fe-alert-triangle text-white mb-0"></i>
                                            </span>
                                        </div>
                                        <div class="col pr-0">
                                            <p class="small text-muted mb-0">Broken</p>
                                            <span class="h3 mb-0">{{ $totalBroken }}</span>
                                            <p class="small text-muted mb-0">Unit broken</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-4">

                        <div class="col-md-6 col-xl-3 mb-4">
                            <a href="{{ route('loan.index') }}" class="text-decoration-none">
                                <div class="card shadow border-left-warning border-0 h-100">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-3 text-center">
                                                <span class="circle circle-sm bg-secondary">
                                                    <i class="fe fe-16 fe-clock text-white mb-0"></i>
                                                </span>
                                            </div>
                                            <div class="col pr-0">
                                                <p class="small text-muted mb-0">Pending Loans</p>
                                                <span class="h3 mb-0">{{ $totalPending }}</span>
                                                <p class="small text-muted mb-0">Waiting for approval</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-xl-3 mb-4">
                            <a href="{{ route('loan.index') }}" class="text-decoration-none">
                                <div class="card shadow border-0 h-100">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-3 text-center">
                                                <span class="circle circle-sm bg-info">
                                                    <i class="fe fe-16 fe-check-circle text-white mb-0"></i>
                                                </span>
                                            </div>
                                            <div class="col pr-0">
                                                <p class="small text-muted mb-0">Active Loans</p>
                                                <span class="h3 mb-0">{{ $totalActive }}</span>
                                                <p class="small text-muted mb-0">Currently active</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">Status Unit</h6>
                                    @php
                                        $available   = \App\Models\ToolUnit::where('status', 'available')->count();
                                        $lent        = \App\Models\ToolUnit::where('status', 'lent')->count();
                                        $nonactive   = \App\Models\ToolUnit::where('status', 'nonactive')->count();
                                        $total       = $available + $lent + $nonactive;
                                        $pctAvail    = $total > 0 ? round($available / $total * 100) : 0;
                                        $pctLent     = $total > 0 ? round($lent    / $total * 100) : 0;
                                        $pctNonactive= $total > 0 ? round($nonactive/ $total * 100) : 0;
                                    @endphp
                                    <div class="row align-items-center mb-2">
                                        <div class="col-3 col-md-2">
                                            <small class="text-muted">Available</small>
                                        </div>
                                        <div class="col">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: {{ $pctAvail }}%"></div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <small><strong>{{ $available }}</strong> ({{ $pctAvail }}%)</small>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-2">
                                        <div class="col-3 col-md-2">
                                            <small class="text-muted">Lent</small>
                                        </div>
                                        <div class="col">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-warning" style="width: {{ $pctLent }}%"></div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <small><strong>{{ $lent }}</strong> ({{ $pctLent }}%)</small>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-3 col-md-2">
                                            <small class="text-muted">Non-Active</small>
                                        </div>
                                        <div class="col">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-danger" style="width: {{ $pctNonactive }}%"></div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <small><strong>{{ $nonactive }}</strong> ({{ $pctNonactive }}%)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Loan Terbaru ──────────────────────────────────────── --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">Recent Loans</h6>
                                    <table class="table table-borderless table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>Borrower</th>
                                                <th>Item</th>
                                                <th>Unit</th>
                                                <th>Loan Date</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(\App\Models\Loan::with(['user','item'])->latest()->take(7)->get() as $loan)
                                                <tr>
                                                    <td>{{ $loan->user->email ?? '-' }}</td>
                                                    <td>{{ $loan->item->name ?? '-' }}</td>
                                                    <td>{{ $loan->unit_code }}</td>
                                                    <td>{{ $loan->loan_date }}</td>
                                                    <td>{{ $loan->due_date }}</td>
                                                    <td>
                                                        @if ($loan->status === 'pending')
                                                            <span class="badge badge-warning">Pending</span>
                                                        @elseif ($loan->status === 'active')
                                                            <span class="badge badge-success">Active</span>
                                                        @elseif ($loan->status === 'rejected')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @elseif ($loan->status === 'closed')
                                                            <span class="badge badge-secondary">Closed</span>
                                                        @elseif ($loan->status === 'returned')
                                                            <span class="badge badge-info">Returned</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">There are no recent loans.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <a href="{{ route('loan.index') }}" class="small text-muted">See all→</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div> 
        </div> 
    </main>
@endsection