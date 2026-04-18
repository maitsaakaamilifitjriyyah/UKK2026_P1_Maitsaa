<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use App\Models\ToolUnit;
use App\Models\Returns;
use App\Models\ActivityLog;
use App\Exports\LoanExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LoanController extends Controller
{
    public function index()
    {
        $role   = strtolower(auth()->user()->role);
        $userId = auth()->id();

        if ($role === 'user') {
            $pending  = Loan::where('status', 'pending')->where('user_id', $userId)->get();
            $active   = Loan::where('status', 'active')->where('user_id', $userId)->get();
            $rejected = Loan::where('status', 'rejected')
                ->where('user_id', $userId)
                ->where('updated_at', '>=', Carbon::now()->subDays(2))
                ->get();
        } else {
            $pending  = Loan::with(['user', 'item', 'toolUnit'])->where('status', 'pending')->get();
            $active   = Loan::with(['user', 'item', 'toolUnit'])->where('status', 'active')->get();
            $rejected = Loan::with(['user', 'item', 'toolUnit'])
                ->where('status', 'rejected')
                ->where('updated_at', '>=', Carbon::now()->subDays(2))
                ->get();
        }

        $items = Tool::with(['units' => function ($q) {
            $q->where('status', 'available');
        }])->get();
        $units = ToolUnit::where('status', 'available')->get();

        return view('loan.index', compact('pending', 'active', 'rejected', 'items', 'units', 'role'));
    }

    public function export()
    {
        return Excel::download(new LoanExport, 'loans_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tool_id'   => 'required|exists:tools,id',
            'unit_code' => 'required|exists:tool_units,code',
            'loan_date' => 'required|date',
            'due_date'  => 'required|date|after_or_equal:loan_date',
            'purpose'   => 'required|string|max:255',
        ]);

        $loan = Loan::create([
            'user_id'   => auth()->id(),
            'tool_id'   => $request->tool_id,
            'unit_code' => $request->unit_code,
            'status'    => 'pending',
            'loan_date' => $request->loan_date,
            'due_date'  => $request->due_date,
            'purpose'   => $request->purpose,
        ]);

        ActivityLog::record(
            'loan.created', 'loans',
            auth()->user()->email . ' mengajukan peminjaman unit ' . $request->unit_code,
            ['loan_id' => $loan->id, 'unit_code' => $request->unit_code]
        );

        return redirect()->back()->with('success', 'Loan berhasil diajukan.');
    }

    public function approve(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'status'      => 'active',
            'notes'       => $request->notes,
            'employee_id' => auth()->id(),
        ]);
        ToolUnit::where('code', $loan->unit_code)->update(['status' => 'lent']);

        ActivityLog::record(
            'loan.approved', 'loans',
            auth()->user()->email . ' menyetujui peminjaman loan #' . $loan->id . ' unit ' . $loan->unit_code,
            ['loan_id' => $loan->id, 'unit_code' => $loan->unit_code]
        );

        return redirect()->back()->with('success', 'Loan approved.');
    }

    public function reject(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'status'      => 'rejected',
            'notes'       => $request->notes,
            'employee_id' => auth()->id(),
        ]);

        ActivityLog::record(
            'loan.rejected', 'loans',
            auth()->user()->email . ' menolak peminjaman loan #' . $loan->id . ' unit ' . $loan->unit_code,
            ['loan_id' => $loan->id, 'unit_code' => $loan->unit_code, 'notes' => $request->notes]
        );

        return redirect()->back()->with('success', 'Loan rejected.');
    }

    public function return(Request $request, $id)
    {
        $request->validate([
            'path_photo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $loan = Loan::findOrFail($id);
        $path = $request->file('path_photo')->store('loan_returns', 'public');

        $ret = Returns::create([
            'loan_id'     => $loan->id,
            'return_date' => now()->toDateString(),
            'path_photo'  => $path,
            'notes'       => $request->notes ?? null,
        ]);

        $loan->update(['status' => 'closed']);

        ActivityLog::record(
            'return.submitted', 'returns',
            auth()->user()->email . ' mengajukan pengembalian loan #' . $loan->id . ' unit ' . $loan->unit_code,
            ['loan_id' => $loan->id, 'return_id' => $ret->id, 'unit_code' => $loan->unit_code]
        );

        return redirect()->back()->with('success', 'Pengembalian berhasil diajukan.');
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);

        ActivityLog::record(
            'loan.deleted', 'loans',
            auth()->user()->email . ' menghapus loan #' . $loan->id . ' unit ' . $loan->unit_code,
            ['loan_id' => $loan->id, 'unit_code' => $loan->unit_code]
        );

        $loan->delete();
        return redirect()->back()->with('success', 'Loan deleted.');
    }

    public function edit($id)
    {
        $loan  = Loan::findOrFail($id);
        $items = Tool::whereHas('units', function ($q) {
            $q->where('status', 'available');
        })->with(['units' => function ($q) {
            $q->where('status', 'available');
        }])->get();
        return view('loan.edit', compact('loan', 'items'));
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $request->validate([
            'tool_id'   => 'required|exists:tools,id',
            'unit_code' => 'required|exists:tool_units,code',
            'loan_date' => 'required|date',
            'due_date'  => 'required|date|after_or_equal:loan_date',
            'purpose'   => 'required|string|max:255',
        ]);
        $loan->update([
            'tool_id'   => $request->tool_id,
            'unit_code' => $request->unit_code,
            'loan_date' => $request->loan_date,
            'due_date'  => $request->due_date,
            'purpose'   => $request->purpose,
        ]);

        ActivityLog::record(
            'loan.updated', 'loans',
            auth()->user()->email . ' mengubah data loan #' . $loan->id,
            ['loan_id' => $loan->id, 'unit_code' => $request->unit_code]
        );

        return redirect()->route('loan.index')->with('success', 'Loan updated.');
    }
}