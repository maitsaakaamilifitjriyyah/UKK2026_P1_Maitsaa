<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\LaporanPeminjamExport;
use App\Exports\LaporanPeriodeExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        $role = strtolower(auth()->user()->role);
        if ($role === 'user') abort(403);

        // Hanya tampilkan user dengan role 'user' (peminjam)
        $users = User::with('detail')
            ->where('role', 'user')
            ->orderBy('id')
            ->get();

        // Buat daftar tahun dari data loan yang ada
        $years = \App\Models\Loan::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        // Kalau belum ada data loan, pakai tahun sekarang
        if ($years->isEmpty()) {
            $years = collect([now()->year]);
        }

        return view('laporan.index', compact('users', 'years', 'role'));
    }

    /**
     * Export laporan per peminjam
     */
    public function exportPeminjam(Request $request)
    {
        $role = strtolower(auth()->user()->role);
        if ($role === 'user') abort(403);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user     = User::with('detail')->findOrFail($request->user_id);
        $nama     = $user->detail->name ?? $user->email;
        $filename = 'laporan_peminjam_' . \Str::slug($nama) . '_' . now()->format('Ymd') . '.xlsx';

        return Excel::download(new LaporanPeminjamExport($user), $filename);
    }

    /**
     * Export laporan per periode (bulan & tahun)
     */
    public function exportPeriode(Request $request)
    {
        $role = strtolower(auth()->user()->role);
        if ($role === 'user') abort(403);

        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2000|max:' . now()->year,
        ]);

        $bulan    = (int) $request->bulan;
        $tahun    = (int) $request->tahun;
        $filename = 'laporan_periode_' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '_' . $tahun . '.xlsx';

        return Excel::download(new LaporanPeriodeExport($bulan, $tahun), $filename);
    }
}