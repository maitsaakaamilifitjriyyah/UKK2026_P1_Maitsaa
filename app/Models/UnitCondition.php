<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitCondition extends Model
{
    protected $table = 'unit_conditions';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $timestamps = true;

    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'id',
        'unit_code',
        'return_id',
        'conditions',
        'notes',
        'recorded_at',
    ];

    // Relasi ke ToolUnit (FK = unit_code)
    public function unit()
    {
        return $this->belongsTo(ToolUnit::class, 'unit_code', 'code');
    }

    // Relasi ke Returns
    public function returnRecord()
    {
        return $this->belongsTo(Returns::class, 'return_id', 'id');
    }
}