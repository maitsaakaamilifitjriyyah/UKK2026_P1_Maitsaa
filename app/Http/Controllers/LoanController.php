<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\ToolUnit;

class LoanController extends Controller
{
    public function index()
    {
        $role     = strtolower(auth()->user()->role);
        $pending  = Loan::where('status', 'pending')->get();
        $active   = Loan::where('status', 'active')->get();
        $rejected = Loan::where('status', 'rejected')->get();
        $items    = Tool::whereHas('units', function ($q) {
            $q->where('status', '!=', 'lent');
        })->with(['units' => function ($q) {
            $q->where('status', '!=', 'lent');
        }])->get();
        $units = ToolUnit::where('status', '!=', 'lent')->get();

        return view('loan.index', compact('pending', 'active', 'rejected', 'items', 'units', 'role'));
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

        Loan::create([
            'user_id'   => auth()->id(),
            'tool_id'   => $request->tool_id,
            'unit_code' => $request->unit_code,
            'status'    => 'pending',
            'loan_date' => $request->loan_date,
            'due_date'  => $request->due_date,
            'purpose'   => $request->purpose,
        ]);

        return redirect()->back()->with('success', 'Loan berhasil diajukan.');
    }

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'status'      => 'active',
            'employee_id' => auth()->id(), // catat siapa yang approve
        ]);
        return redirect()->back()->with('success', 'Loan approved.');
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'status'      => 'rejected',
            'employee_id' => auth()->id(), // catat siapa yang reject
        ]);
        return redirect()->back()->with('success', 'Loan rejected.');
    }

    public function destroy($id)
    {
        Loan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Loan deleted.');
    }
}
