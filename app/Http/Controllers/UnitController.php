<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ToolUnit;
use App\Models\UnitCondition;
use App\Models\Tool;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'status' => 'required|in:available,nonactive,lent',
            'notes' => 'nullable|string'
        ]);

        $tool = Tool::findOrFail($request->tool_id);

        $prefix = $tool->code_slug;

        $lastUnit = ToolUnit::where('tool_id', $tool->id)
            ->orderBy('code', 'desc')
            ->first();
        if ($lastUnit) {
            $lastNumber = (int) substr($lastUnit->code, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $number = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $code = $prefix . '-' . $number;

        ToolUnit::create([
            'code'       => $code,
            'tool_id'    => $request->tool_id,
            'status'     => $request->status,
            'notes'      => $request->notes,
            'created_at' => now(),
        ]);

        UnitCondition::create([
            'id'          => Str::uuid(),
            'unit_code'   => $code,
            'conditions'  => 'good',
            'notes'       => 'Kondisi awal unit',
            'recorded_at' => now(),
        ]);

        return redirect()->route('item.detail', $request->tool_id)
            ->with('success', 'Unit added successfully!');
    }

    // public function edit($code)
    // {
    //     $unit = ToolUnit::where('code', $code)->firstOrFail();
    //     $item = $unit->tool; // ambil tool terkait
    //     return view('ite.store', compact('unit', 'item'));
    // }

    public function update(Request $request, $code)
    {
        $unit = ToolUnit::findOrFail($code);

        $request->validate([
            'status' => 'required|in:available,nonactive,lent',
            'notes'  => 'nullable|string',
        ]);

        $unit->update([
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);

        // Simpan kondisi baru kalau diisi
        if ($request->filled('conditions')) {
            UnitCondition::create([
                'id'          => Str::uuid(),
                'unit_code'   => $unit->code,
                'conditions'  => $request->conditions,
                'notes'       => $request->notes,
                'recorded_at' => now(),
            ]);
        }

        return redirect()->route('item.detail', $unit->tool_id)
            ->with('success', 'Unit updated successfully!');
    }

    public function destroy($code)
    {
        $unit = ToolUnit::findOrFail($code);
        $tool_id = $unit->tool_id;

        UnitCondition::where('unit_code', $code)->delete();
        $unit->delete();

        return redirect()->route('item.detail', $tool_id)
            ->with('success', 'Unit deleted successfully!');
    }
}
