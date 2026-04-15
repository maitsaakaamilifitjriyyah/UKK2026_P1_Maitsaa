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
        $items = Tool::with(['units' => function ($q) {
            $q->whereIn('status', ['available', 'maintenance', 'broken']);
        }])->get();

        $units = ToolUnit::whereIn('status', ['available', 'maintenance', 'broken'])->get();

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

    public function approve(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'status'      => 'active',
            'notes'       => $request->notes,
            'employee_id' => auth()->id(),
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

    public function return(Request $request, $id)
{
    $loan = Loan::findOrFail($id);

    $path = null;
    if ($request->hasFile('path_photo')) {
        $path = $request->file('path_photo')->store('loan_returns', 'public');
    }

    $loan->update([
        'status'     => 'returned',
        'notes'      => $request->notes,
        'path_photo' => $path,
    ]);

    return redirect()->back()->with('success', 'Loan returned successfully.');
}
    public function destroy($id)
    {
        Loan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Loan deleted.');
    }

    public function edit($id)
    {
        $loan = Loan::findOrFail($id);
        $items = Tool::whereHas('units', function ($q) {
            $q->where('status', '!=', 'lent');
        })->with(['units' => function ($q) {
            $q->where('status', '!=', 'lent');
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

        return redirect()->route('loan.index')->with('success', 'Loan updated successfully!');
    }
}
