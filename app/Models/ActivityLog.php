<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'meta',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function record(string $action, string $module, string $description, array $meta = []): void
    {
        self::create([
            'user_id'     => auth()->id() ?? null,
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'meta'        => !empty($meta) ? json_encode($meta) : null,
            'ip_address'  => Request::ip(),
        ]);
    }
}