<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Exports\ActivityLogExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $role = strtolower(auth()->user()->role);
        if ($role === 'user') abort(403, 'Unauthorized');

        $query = ActivityLog::with('user')->latest();

        if ($request->filled('module'))    $query->where('module', $request->module);
        if ($request->filled('action'))    $query->where('action', $request->action);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('created_at', '<=', $request->date_to);

        $logs    = $query->paginate(20)->withQueryString();
        $modules = ActivityLog::select('module')->distinct()->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('activity.index', compact('logs', 'modules', 'actions', 'role'));
    }

    public function export(Request $request)
    {
        $role = strtolower(auth()->user()->role);
        if ($role === 'user') abort(403);

        $filename = 'activity_log_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new ActivityLogExport(
                $request->module    ?? null,
                $request->action    ?? null,
                $request->date_from ?? null,
                $request->date_to   ?? null,
            ),
            $filename
        );
    }
}