<?php

namespace App\Exports;

use App\Models\Tool;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Tool::with(['category', 'location', 'units'])
            ->where('item_type', '!=', 'bundle_tool')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Code',
            'Name',
            'Price',
            'Category',
            'Type',
            'Location',
            'Total Unit',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->code_slug ?? '-',
            $item->name ?? '-',
            $item->price ? 'Rp ' . number_format($item->price, 0, ',', '.') : '-',
            $item->category->name ?? '-',
            $item->item_type ?? '-',
            isset($item->location) ? $item->location->name . ' - ' . $item->location->detail : '-',
            $item->units->count(),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row: bold + background biru muda
            1 => [
                'font'    => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'    => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF4472C4']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}