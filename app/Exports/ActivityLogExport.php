<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActivityLogExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected ?string $module;
    protected ?string $action;
    protected ?string $dateFrom;
    protected ?string $dateTo;

    public function __construct(?string $module, ?string $action, ?string $dateFrom, ?string $dateTo)
    {
        $this->module   = $module;
        $this->action   = $action;
        $this->dateFrom = $dateFrom;
        $this->dateTo   = $dateTo;
    }

    public function query()
    {
        $query = ActivityLog::with('user')->latest();

        if ($this->module)   $query->where('module', $this->module);
        if ($this->action)   $query->where('action', $this->action);
        if ($this->dateFrom) $query->whereDate('created_at', '>=', $this->dateFrom);
        if ($this->dateTo)   $query->whereDate('created_at', '<=', $this->dateTo);

        return $query;
    }

    public function headings(): array
    {
        return ['No', 'Waktu', 'User', 'Module', 'Action', 'Description', 'IP Address', 'Meta'];
    }

    public function map($log): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $log->created_at ? $log->created_at->format('d M Y H:i') : '-',
            $log->user->email ?? 'system',
            $log->module,
            $log->action,
            $log->description,
            $log->ip_address ?? '-',
            $log->meta ?? '-',
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