<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return User::with('detail')->get();
    }

    public function headings(): array
    {
        return ['No', 'NIK', 'Name', 'Email', 'Role', 'No HP', 'Address', 'Birth Date'];
    }

    public function map($user): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $user->detail->nik      ?? '-',
            $user->detail->name     ?? '-',
            $user->email,
            $user->role             ?? '-',
            $user->detail->no_hp    ?? '-',
            $user->detail->address  ?? '-',
            $user->detail->birth_date ?? '-',
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