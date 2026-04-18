<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Exports\LocationExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LocationController extends Controller
{
    public function index()
    {
        $data = Location::latest()->get();
        return view('location.index', compact('data'));
    }

    public function export()
    {
        return Excel::download(new LocationExport, 'locations_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function create()
    {
        return view('location.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_code' => 'required|unique:locations,location_code',
            'name' => 'nullable|string|max:100',
            'detail' => 'nullable|string|max:100',
        ]);

        Location::create($request->all());

        return redirect()->route('location.index')->with('success', 'Location created successfully.');
    }

    public function edit($location_code)
    {
        $location = Location::findOrFail($location_code);
        return view('location.edit', compact('location'));
    }

    public function update(Request $request, $location_code)
    {
        $request->validate([
            'name' => 'nullable|string|max:100',
            'detail' => 'nullable|string|max:100',
        ]);

        $location = Location::findOrFail($location_code);
        $location->update($request->all());

        return redirect()->route('location.index')->with('success', 'Location updated successfully.');
    }

    public function destroy($location_code)
    {
        $location = Location::findOrFail($location_code);
        $location->delete();

        return redirect()->route('location.index')->with('success', 'Location deleted successfully.');
    }
}
