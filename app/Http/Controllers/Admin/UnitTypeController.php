<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitType;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    public function index()
    {
        $unitTypes = UnitType::orderBy('name')->paginate(10)->withQueryString();
        return view('admin.unit_types.index', compact('unitTypes'));
    }

    public function create()
    {
        return view('admin.unit_types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:unit_types,name',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        UnitType::create($validated);

        return redirect()->route('admin.unit-types.index')
            ->with('success', 'Jenis Unit berhasil ditambahkan.');
    }

    public function edit(UnitType $unitType)
    {
        return view('admin.unit_types.edit', compact('unitType'));
    }

    public function update(Request $request, UnitType $unitType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:unit_types,name,' . $unitType->id,
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $unitType->update($validated);

        return redirect()->route('admin.unit-types.index')
            ->with('success', 'Jenis Unit berhasil diperbarui.');
    }

    public function destroy(UnitType $unitType)
    {
        if ($unitType->units()->count() > 0) {
            return redirect()->route('admin.unit-types.index')
                ->with('error', 'Tidak dapat menghapus jenis unit yang masih digunakan oleh unit.');
        }

        $unitType->delete();

        return redirect()->route('admin.unit-types.index')
            ->with('success', 'Jenis Unit berhasil dihapus.');
    }
}
