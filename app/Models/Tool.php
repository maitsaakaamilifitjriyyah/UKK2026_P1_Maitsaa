<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $table = 'tools';

    protected $fillable = [
        'category_id',
        'location_code',
        'name',
        'item_type',
        'status',
        'description',
        'code_slug',
        'photo_path',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_code', 'location_code');
    }

    public function units()
    {
        return $this->hasMany(ToolUnit::class, 'tool_id', 'id');
    }
}
