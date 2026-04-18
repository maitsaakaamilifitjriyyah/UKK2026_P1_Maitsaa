<?php

namespace App\Exports;

use App\Models\ToolUnit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UnitExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return ToolUnit::with(['tool', 'condition'])->get();
    }

    public function headings(): array
    {
        return ['No', 'Unit Code', 'Item', 'Status', 'Condition', 'Notes'];
    }

    public function map($unit): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $unit->code,
            $unit->tool->name               ?? '-',
            strtoupper($unit->status)       ?? '-',
            strtoupper($unit->condition->conditions ?? '-'),
            $unit->notes                    ?? '-',
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