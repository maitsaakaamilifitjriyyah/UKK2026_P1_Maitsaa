<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Returns;
use App\Models\Loan;
use App\Models\UnitCondition;
use App\Models\ToolUnit;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = Returns::with(['loan.user', 'loan.item', 'loan.toolUnit'])
            ->whereNull('condition_id')
            ->latest()
            ->get();

        return view('returns.index', compact('returns'));
    }

    public function check(Request $request, $id)
    {
        $request->validate([
            'conditions'      => 'required|in:good,broken,maintenance',
            'notes'           => 'nullable|string|max:500',
            'fine_percentage' => 'nullable|integer|in:10,25,50,75,100',
        ]);

        $return = Returns::with(['loan.item', 'loan.toolUnit'])->findOrFail($id);

        $conditionId = (string) Str::uuid();

        UnitCondition::create([
            'id'          => $conditionId,
            'unit_code'   => $return->loan->unit_code,
            'return_id'   => $return->id,
            'conditions'  => $request->conditions,
            'notes'       => $request->notes ?? '-',
            'recorded_at' => now(),
        ]);

        $finePercentage = $request->fine_percentage ?? null;
        $fineAmount     = null;
        if ($finePercentage && $return->loan->item) {
            $fineAmount = ($return->loan->item->price * $finePercentage) / 100;
        }

        $return->update([
            'condition_id'    => $conditionId,
            'employee_id'     => auth()->id(),
            'fine_percentage' => $finePercentage,
            'fine_amount'     => $fineAmount,
        ]);

        $return->loan->update(['status' => 'returned']);

        $newUnitStatus = match($request->conditions) {
            'good'        => 'available',
            'maintenance' => 'nonactive',
            'broken'      => 'nonactive',
            default       => 'available',
        };
        ToolUnit::where('code', $return->loan->unit_code)->update(['status' => $newUnitStatus]);

        ActivityLog::record(
            'return.checked', 'returns',
            auth()->user()->email . ' mencatat kondisi ' . $request->conditions
                . ' untuk pengembalian return #' . $return->id
                . ' unit ' . $return->loan->unit_code
                . ($fineAmount ? ' — denda Rp ' . number_format($fineAmount, 0, ',', '.') : ''),
            [
                'return_id'      => $return->id,
                'loan_id'        => $return->loan_id,
                'unit_code'      => $return->loan->unit_code,
                'conditions'     => $request->conditions,
                'fine_percentage'=> $finePercentage,
                'fine_amount'    => $fineAmount,
            ]
        );

        return redirect()->back()->with('success', 'Kondisi barang berhasil dicatat.');
    }

    public function history()
    {
        $role   = strtolower(auth()->user()->role);
        $userId = auth()->id();
        $isUser = $role === 'user';

        // Rejected lebih dari 2 hari
        $rejectedQuery = Loan::with(['user', 'item', 'toolUnit'])
            ->where('status', 'rejected')
            ->where('updated_at', '<', Carbon::now()->subDays(2));
        if ($isUser) $rejectedQuery->where('user_id', $userId);

        $rejectedLoans = $rejectedQuery->latest('updated_at')->get()
            ->map(fn($loan) => [
                'type'        => 'rejected',
                'date'        => $loan->updated_at,
                'user'        => $loan->user->name ?? $loan->user->email ?? 'N/A',
                'item'        => $loan->item->name ?? 'N/A',
                'unit'        => $loan->unit_code ?? 'N/A',
                'notes'       => $loan->notes ?? '-',
                'condition'   => '-',
                'fine'        => '-',
                'loan_date'   => $loan->loan_date,
                'due_date'    => $loan->due_date,
                'return_date' => '-',
                'path_photo'  => null,
            ]);

        // Returns yang sudah dicek
        $returnsQuery = Returns::with(['loan.user', 'loan.item', 'loan.toolUnit', 'condition'])
            ->whereNotNull('condition_id');
        if ($isUser) {
            $returnsQuery->whereHas('loan', fn($q) => $q->where('user_id', $userId));
        }

        $checkedReturns = $returnsQuery->latest('updated_at')->get()
            ->map(function ($ret) {
                $cond = $ret->condition->conditions ?? '-';
                return [
                    'type'        => 'returned_' . $cond,
                    'date'        => $ret->updated_at,
                    'user'        => $ret->loan->user->name ?? $ret->loan->user->email ?? 'N/A',
                    'item'        => $ret->loan->item->name ?? 'N/A',
                    'unit'        => $ret->loan->unit_code ?? 'N/A',
                    'notes'       => $ret->notes ?? '-',
                    'condition'   => $cond,
                    'fine'        => $ret->fine_amount
                                        ? 'Rp ' . number_format($ret->fine_amount, 0, ',', '.')
                                        : '-',
                    'loan_date'   => $ret->loan->loan_date ?? '-',
                    'due_date'    => $ret->loan->due_date ?? '-',
                    'return_date' => $ret->return_date,
                    'path_photo'  => $ret->path_photo,
                ];
            });

        $history = $rejectedLoans->concat($checkedReturns)->sortByDesc('date')->values();

        return view('returns.history', compact('history', 'role'));
    }
}