<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolUnit extends Model
{
    protected $table = 'tool_units';

    protected $primaryKey = 'code';

    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'code',
        'tool_id',
        'status',
        'notes',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_id', 'id');
    }

    /**
     * FIX: Ambil kondisi TERBARU berdasarkan recorded_at
     * Pakai ofMany() agar eager loading ($item->units->condition) tetap efisien
     */
    public function condition()
    {
        return $this->hasOne(UnitCondition::class, 'unit_code', 'code')
                    ->ofMany(
                        ['recorded_at' => 'max'],
                        fn ($q) => $q->whereNotNull('recorded_at')
                    );
    }

    /**
     * Semua riwayat kondisi unit (untuk history / detail)
     */
    public function conditions()
    {
        return $this->hasMany(UnitCondition::class, 'unit_code', 'code')
                    ->orderByDesc('recorded_at');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'unit_code', 'code');
    }
}