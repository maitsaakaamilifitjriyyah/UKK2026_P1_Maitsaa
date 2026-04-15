<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'loan_id',
        'condition_id',
        'return_date',
        'path_photo',
        'notes',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'id');
    }

    public function condition()
    {
        return $this->belongsTo(UnitCondition::class, 'condition_id', 'id');
    }
}
