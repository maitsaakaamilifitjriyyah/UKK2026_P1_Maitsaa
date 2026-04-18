<?php

namespace App\Http\Controllers;

use App\Models\BundleTool;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Location;
use App\Exports\ItemExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function index()
    {
        $role = strtolower(auth()->user()->role);
        $data = Tool::with('category', 'location', 'bundleTools')
            ->where('item_type', '!=', 'bundle_tool')
            ->get();
        return view('item.index', compact('data', 'role'));
    }

    /**
     * Export data item ke file Excel (.xlsx)
     */
    public function export()
    {
        $filename = 'items_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new ItemExport, $filename);
    }

    public function create()
    {
        $category = Category::all();
        $location = Location::all();
        $items    = Tool::where('item_type', 'single')->get();
        return view('item.create', compact('category', 'location', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'location_code' => 'nullable|exists:locations,location_code',
            'name'          => 'required|string|max:255',
            'item_type'     => 'required|in:single,bundle,bundle_tool',
            'price'         => 'required|numeric|min:0',
            'description'   => 'nullable|string',
            'code_slug'     => 'required|string|unique:tools,code_slug',
            'photo_path'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pathPoto = null;
        if ($request->hasFile('photo_path')) {
            $pathPoto = $request->file('photo_path')->store('item', 'public');
        }

        $item = Tool::create([
            'category_id'   => $request->category_id,
            'location_code' => $request->location_code,
            'name'          => $request->name,
            'item_type'     => $request->item_type,
            'price'         => $request->price,
            'description'   => $request->description,
            'code_slug'     => $request->item_type === 'bundle'
                ? 'BDL-' . $request->code_slug
                : $request->code_slug,
            'photo_path'    => $pathPoto,
            'created_at'    => now(),
        ]);

        if ($request->item_type === 'bundle' && $request->has('bundle_names')) {
            $names  = $request->bundle_names ?? [];
            $qtys   = $request->bundle_qty   ?? [];
            $prices = $request->bundle_price ?? [];
            $descs  = $request->bundle_desc  ?? [];

            foreach ($names as $i => $nama) {
                if (empty($nama)) continue;

                $subTool = Tool::create([
                    'category_id'   => $request->category_id,
                    'location_code' => $request->location_code,
                    'name'          => $nama,
                    'item_type'     => 'bundle_tool',
                    'price'         => $prices[$i] ?? 0,
                    'description'   => $descs[$i]  ?? null,
                    'code_slug'     => 'BDL-' . $request->code_slug . '-' . ($i + 1),
                    'photo_path'    => $pathPoto,
                    'created_at'    => now(),
                ]);

                BundleTool::create([
                    'bundle_id' => $item->id,
                    'tool_id'   => $subTool->id,
                    'qty'       => $qtys[$i] ?? 1,
                ]);
            }
        }

        return redirect()->route('item.index')->with('success', 'Tool successfully added!');
    }

    public function edit($id)
    {
        $item     = Tool::findOrFail($id);
        $category = Category::all();
        $location = Location::all();
        return view('item.create', compact('item', 'category', 'location'));
    }

    public function update(Request $request, $id)
    {
        $item = Tool::findOrFail($id);

        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'location_code' => 'nullable|exists:locations,location_code',
            'name'          => 'required|string|max:255',
            'item_type'     => 'required|in:single,bundle,bundle_tool',
            'price'         => 'required|numeric|min:0',
            'description'   => 'nullable|string',
            'code_slug'     => 'required|string|unique:tools,code_slug,' . $id,
            'photo_path'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'category_id', 'location_code', 'name',
            'item_type', 'price', 'description', 'code_slug'
        ]);

        if ($request->hasFile('photo_path')) {
            if ($item->photo_path && Storage::disk('public')->exists($item->photo_path)) {
                Storage::disk('public')->delete($item->photo_path);
            }
            $file     = $request->file('photo_path');
            $ext      = $file->getClientOriginalExtension();
            $filename = strtolower(str_replace(' ', '-', $request->code_slug)) . '-' . time() . '.' . $ext;
            $data['photo_path'] = $file->storeAs('item', $filename, 'public');
        }

        $item->update($data);
        return redirect()->route('item.index')->with('success', 'Tool successfully updated!');
    }

    public function destroy($id)
    {
        $item = Tool::findOrFail($id);
        if ($item->photo_path && Storage::disk('public')->exists($item->photo_path)) {
            Storage::disk('public')->delete($item->photo_path);
        }
        $item->delete();
        return redirect()->route('item.index')->with('success', 'Tool successfully deleted!');
    }

    public function detail($id)
    {
        $role = strtolower(auth()->user()->role);
        $item = Tool::with('category', 'location', 'units.condition')->findOrFail($id);
        return view('item.detail', compact('item', 'role'));
    }
}