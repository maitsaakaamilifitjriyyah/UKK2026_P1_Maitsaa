<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Hanya admin & employee yang boleh akses menu log.
     * User biasa tidak perlu lihat log sistem.
     */
    public function index(Request $request)
    {
        $role = strtolower(auth()->user()->role);

        if ($role === 'user') {
            abort(403, 'Unauthorized');
        }

        $query = ActivityLog::with('user')->latest();

        // Filter by module
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();

        // Untuk dropdown filter
        $modules = ActivityLog::select('module')->distinct()->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('activity.index', compact('logs', 'modules', 'actions', 'role'));
    }
}