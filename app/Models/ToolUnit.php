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

    public function condition()
    {
        return $this->hasOne(UnitCondition::class, 'unit_code', 'code');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'tool_unit_id', 'code');
    }

}
