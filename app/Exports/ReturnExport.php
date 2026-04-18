<?php

namespace App\Exports;

use App\Models\Returns;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReturnExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Returns::with(['loan.user', 'loan.item', 'loan.toolUnit', 'condition', 'employee'])->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Peminjam', 'Item', 'Unit',
            'Return Date', 'Condition', 'Fine (%)', 'Fine Amount', 'Notes', 'Checked By',
        ];
    }

    public function map($ret): array
    {
        static $no = 0;
        $no++;
        $cond = $ret->condition->conditions ?? '-';
        return [
            $no,
            $ret->loan->user->email        ?? '-',
            $ret->loan->item->name         ?? '-',
            $ret->loan->unit_code          ?? '-',
            $ret->return_date              ?? '-',
            strtoupper($cond),
            $ret->fine_percentage          ? $ret->fine_percentage . '%' : '-',
            $ret->fine_amount              ? 'Rp ' . number_format($ret->fine_amount, 0, ',', '.') : '-',
            $ret->notes                    ?? '-',
            $ret->employee->email          ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF4472C4']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}