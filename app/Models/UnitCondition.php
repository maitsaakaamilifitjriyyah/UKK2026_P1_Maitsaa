<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitCondition extends Model
{
    protected $table = 'unit_conditions';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'unit_code',
        'return_id',
        'conditions',
        'notes',
        'recorded_at',
    ];

    public function unit()
    {
        return $this->belongsTo(ToolUnit::class, 'unit_id', 'id');
    }
}
