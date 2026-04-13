<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BundleTool extends Model
{
    protected $fillable = [
        'bundle_id',
        'tool_id',
        'qty',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_id', 'id');
    }
}
