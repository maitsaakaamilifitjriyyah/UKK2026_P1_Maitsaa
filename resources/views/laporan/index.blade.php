@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="col-md-12 my-4">
                        <h2 class="h4 mb-4">Cetak Laporan</h2>

                        @if (session('success'))
                            <div class="alert alert-success shadow">{{ session('success') }}</div>
                        @endif

                        <div class="row">

                            <div class="col-md-6 mb-4">
                                <div class="card shadow h-100">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <span class="fe fe-user fe-16 mr-1"></span>
                                            Laporan Per Peminjam
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small mb-3">
                                            Rekap seluruh riwayat peminjaman dari satu peminjam
                                        </p>
                                        <form action="{{ route('laporan.peminjam') }}" method="GET">
                                            <div class="form-group">
                                                <label>Choose Borrower</label>
                                                <select name="user_id" class="form-control" required>
                                                    <option value="">-- Choose Borrower --</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">
                                                            {{ $user->detail->name ?? $user->email }}
                                                            ({{ $user->email }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-block mt-3">
                                                <span class="fe fe-download fe-16 mr-1"></span>
                                                Download Excel
                                            </button>
                                        </form>
                                    </div>
                                    <div class="card-footer text-muted small">
                                        Kolom: Item, Unit, Tujuan, Tgl Pinjam, Tgl Kembali, Status, Kondisi, Denda
                                    </div>
                                </div>
                            </div>

                            
                            <div class="col-md-6 mb-4">
                                <div class="card shadow h-100">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <span class="fe fe-calendar fe-16 mr-1"></span>
                                            Laporan Per Periode
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small mb-3">
                                            Rekap semua aktivitas peminjaman dalam bulan dan tahun tertentu.
                                        </p>
                                        <form action="{{ route('laporan.periode') }}" method="GET">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>Month</label>
                                                        <select name="bulan" class="form-control" required>
                                                            <option value="">-- Month --</option>
                                                            @foreach ([
                                                                1  => 'Januari',   2  => 'Februari',
                                                                3  => 'Maret',     4  => 'April',
                                                                5  => 'Mei',       6  => 'Juni',
                                                                7  => 'Juli',      8  => 'Agustus',
                                                                9  => 'September', 10 => 'Oktober',
                                                                11 => 'November',  12 => 'Desember',
                                                            ] as $num => $nama)
                                                                <option value="{{ $num }}"
                                                                    {{ now()->month == $num ? 'selected' : '' }}>
                                                                    {{ $nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>Year</label>
                                                        <select name="tahun" class="form-control" required>
                                                            @foreach ($years as $y)
                                                                <option value="{{ $y }}"
                                                                    {{ now()->year == $y ? 'selected' : '' }}>
                                                                    {{ $y }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-block mt-2">
                                                <span class="fe fe-download fe-16 mr-1"></span>
                                                Download Excel
                                            </button>
                                        </form>
                                    </div>
                                    <div class="card-footer text-muted small">
                                        Kolom: Peminjam, Item, Unit, Tujuan, Tgl Pinjam, Tgl Kembali, Status, Kondisi, Denda
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection