<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';
    // karena location_code adalah primary key, kita harus memberitahu Eloquent untuk tidak menganggapnya sebagai auto-incrementing
    protected $primaryKey = 'location_code';
    public $incrementing = false; // karena location_code bukan auto-incrementing
    protected $keyType = 'string'; // karena location_code adalah string

    protected $fillable = [
        'location_code',
        'name',
        'detail',
    ];
}
