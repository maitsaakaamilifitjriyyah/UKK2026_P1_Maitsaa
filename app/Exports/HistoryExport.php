<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class HistoryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected Collection $history;
    protected string $role;

    public function __construct(Collection $history, string $role)
    {
        $this->history = $history;
        $this->role    = $role;
    }

    public function collection(): Collection
    {
        return $this->history;
    }

    public function headings(): array
    {
        $heads = ['No', 'Date'];

        if ($this->role !== 'user') {
            $heads[] = 'Peminjam';
        }

        array_push($heads,
            'Item', 'Unit', 'Loan Date', 'Due Date',
            'Return Date', 'Activity', 'Condition', 'Fine', 'Notes'
        );

        return $heads;
    }

    public function map($h): array
    {
        static $no = 0;
        $no++;

        $activityLabel = match($h['type']) {
            'rejected'             => 'Rejected',
            'returned_good'        => 'Returned - Good',
            'returned_maintenance' => 'Returned - Maintenance',
            'returned_broken'      => 'Returned - Broken',
            default                => $h['type'],
        };

        $returnDate = $h['return_date'] !== '-'
            ? \Carbon\Carbon::parse($h['return_date'])->format('d M Y')
            : '-';

        $row = [
            $no,
            \Carbon\Carbon::parse($h['date'])->format('d M Y'),
        ];

        if ($this->role !== 'user') {
            $row[] = $h['user'];
        }

        array_push($row,
            $h['item'],
            $h['unit'],
            $h['loan_date'],
            $h['due_date'],
            $returnDate,
            $activityLabel,
            ucfirst($h['condition'] === '-' ? '-' : $h['condition']),
            $h['fine'],
            $h['notes'],
        );

        return $row;
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