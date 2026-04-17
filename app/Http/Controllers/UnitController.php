<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToolUnit;
use App\Models\UnitCondition;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tool_id'    => 'required|exists:tools,id',
            'code'       => 'required|string|unique:tool_units,code',
            'status'     => 'required|in:available,nonactive,lent',
            'conditions' => 'required|in:good,broken,maintenance',
            'notes'      => 'nullable|string|max:255',
        ]);

        $autoStatus = $this->resolveStatus($request->conditions, $request->status);

        $unit = ToolUnit::create([
            'code'    => $request->code,
            'tool_id' => $request->tool_id,
            'status'  => $autoStatus,
            'notes'   => $request->notes,
        ]);

        UnitCondition::create([
            'id'          => (string) Str::uuid(),
            'unit_code'   => $unit->code,
            'return_id'   => null,
            'conditions'  => $request->conditions,
            'notes'       => $request->notes ?? '-',
            'recorded_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Unit berhasil ditambahkan.');
    }

    public function edit($code)
    {
        $unit = ToolUnit::with('condition')->findOrFail($code);
        return response()->json($unit);
    }

    public function update(Request $request, $code)
    {
        $request->validate([
            'status'     => 'required|in:available,nonactive,lent',
            'conditions' => 'required|in:good,broken,maintenance',
            'notes'      => 'nullable|string|max:255',
        ]);

        $unit = ToolUnit::findOrFail($code);

        $autoStatus = $this->resolveStatus($request->conditions, $request->status);

        $unit->update([
            'status' => $autoStatus,
            'notes'  => $request->notes,
        ]);

        UnitCondition::create([
            'id'          => (string) Str::uuid(),
            'unit_code'   => $code,
            'return_id'   => null,
            'conditions'  => $request->conditions,
            'notes'       => $request->notes ?? '-',
            'recorded_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy($code)
    {
        $unit = ToolUnit::findOrFail($code);
        UnitCondition::where('unit_code', $code)->delete();
        $unit->delete();

        return redirect()->back()->with('success', 'Unit berhasil dihapus.');
    }

    private function resolveStatus(string $conditions, string $requestedStatus): string
    {
        return match ($conditions) {
            'good'        => 'available',
            'broken'      => 'nonactive',
            'maintenance' => 'nonactive',
            default       => $requestedStatus,
        };
    }
}
