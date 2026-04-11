<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $data = Tool::with('category', 'location')->get();
        return view('item.index', compact('data'));
    }

    public function create()
    {
        $category = Category::all();
        $location   = Location::all();

        return view('item.create', compact('category', 'location'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'location_code' => 'nullable|exists:locations,location_code',
            'name'          => 'required|string|max:255',
            'item_type'     => 'required|in:single,bundle,bundle_tool',
            'description'   => 'nullable|string',
            'code_slug'     => 'required|string|unique:tools,code_slug',
            'photo_path'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['category_id', 'location_code', 'name', 'item_type', 'description', 'code_slug']);

        $data['created_at'] = now();

        if ($request->hasFile('photo_path')) {

            $file = $request->file('photo_path');

            $ext = $file->getClientOriginalExtension();

            $filename = strtolower(str_replace(' ', '-', $request->code_slug)) . '.' . $ext;

            $data['photo_path'] = $file->storeAs('item', $filename, 'public');
        }

        Tool::create($data);

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
            'description'   => 'nullable|string',
            'code_slug'     => 'required|string|unique:tools,code_slug,' . $id,
            'photo_path'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['category_id', 'location_code', 'name', 'item_type', 'description', 'code_slug']);

        if ($request->hasFile('photo_path')) {

            if ($item->photo_path && Storage::disk('public')->exists($item->photo_path)) {
                Storage::disk('public')->delete($item->photo_path);
            }

            $file = $request->file('photo_path');
            $ext = $file->getClientOriginalExtension();

            $filename = strtolower(str_replace(' ', '-', $request->code_slug))
                . '-' . time()
                . '.' . $ext;

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
        $item = Tool::with('category', 'location', 'units.condition')->findOrFail($id);
        return view('item.detail', compact('item'));
    }
}
