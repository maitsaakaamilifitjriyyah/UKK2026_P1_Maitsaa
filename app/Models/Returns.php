<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'loan_id',
        'employee_id',
        'condition_id',
        'return_date',
        'path_photo',
        'fine_percentage',
        'fine_amount',
        'notes',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }

    public function condition()
    {
        return $this->belongsTo(UnitCondition::class, 'condition_id');
    }
}