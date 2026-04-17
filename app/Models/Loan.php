<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'loans';

    protected $fillable = [
        'user_id',
        'tool_id',
        'unit_code',
        'employee_id',
        'status',
        'loan_date',
        'due_date',
        'purpose',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Tool::class, 'tool_id', 'id');
    }

    public function toolUnit()
    {
        return $this->belongsTo(ToolUnit::class, 'unit_code', 'code');
    }

    public function returnRecord()
    {
        return $this->hasOne(Returns::class, 'loan_id', 'id');
    }
}