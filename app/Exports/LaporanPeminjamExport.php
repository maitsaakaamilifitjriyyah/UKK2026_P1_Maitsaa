<?php

namespace App\Exports;

use App\Models\Loan;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanPeminjamExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function collection()
    {
        return Loan::with(['item', 'toolUnit', 'employee', 'returnRecord.condition'])
            ->where('user_id', $this->user->id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function title(): string
    {
        return 'Laporan Peminjam';
    }

    public function headings(): array
    {
        return [
            ['LAPORAN PEMINJAMAN PER PEMINJAM'],
            ['Nama', $this->user->detail->name ?? $this->user->email],
            ['Email', $this->user->email],
            ['Tanggal Cetak', now()->format('d M Y H:i')],
            [''],
            [
                'No', 'Item', 'Unit', 'Tujuan', 'Tgl Pinjam',
                'Tgl Kembali (Rencana)', 'Status', 'Kondisi Kembali', 'Denda', 'Keterangan',
            ],
        ];
    }

    public function map($loan): array
    {
        static $no = 0;
        $no++;

        $ret   = $loan->returnRecord;
        $cond  = $ret?->condition?->conditions ?? '-';
        $fine  = $ret?->fine_amount
                    ? 'Rp ' . number_format($ret->fine_amount, 0, ',', '.')
                    : '-';

        $statusLabel = match($loan->status) {
            'pending'  => 'Pending',
            'active'   => 'Dipinjam',
            'rejected' => 'Ditolak',
            'closed'   => 'Menunggu Pengecekan',
            'returned' => 'Dikembalikan',
            default    => $loan->status,
        };

        return [
            $no,
            $loan->item->name ?? '-',
            $loan->unit_code  ?? '-',
            $loan->purpose    ?? '-',
            $loan->loan_date  ?? '-',
            $loan->due_date   ?? '-',
            $statusLabel,
            ucfirst($cond),
            $fine,
            $loan->notes      ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            6 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF4472C4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:J1');
            },
        ];
    }
}