<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LoanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Loan::with(['user', 'item', 'toolUnit', 'employee'])->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Peminjam', 'Item', 'Unit', 'Status',
            'Purpose', 'Loan Date', 'Due Date', 'Employee', 'Notes',
        ];
    }

    public function map($loan): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $loan->user->email          ?? '-',
            $loan->item->name           ?? '-',
            $loan->unit_code            ?? '-',
            strtoupper($loan->status)   ?? '-',
            $loan->purpose              ?? '-',
            $loan->loan_date            ?? '-',
            $loan->due_date             ?? '-',
            $loan->employee->email      ?? '-',
            $loan->notes                ?? '-',
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