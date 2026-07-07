<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitType;
use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::orderBy('jenis')->orderBy('nama');

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
        }

        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }

        $units = $query->paginate(7)->withQueryString()->onEachSide(2);
        
        $jenisList = UnitType::where('is_active', true)->orderBy('name')->pluck('name');
        
        return view('admin.units.index', compact('units', 'jenisList'));
    }

    public function create()
    {
        $jenisList = UnitType::where('is_active', true)->orderBy('name')->pluck('name');
        return view('admin.units.create', compact('jenisList'));
    }

    public function store(Request $request)
    {
        $jenisList = UnitType::where('is_active', true)->pluck('name')->toArray();

        $validated = $request->validate([
            'kode'   => 'required|string|max:125|unique:units,kode',
            'nama'   => 'required|string|max:255',
            'jenis'  => 'required|in:' . implode(',', $jenisList),
            'status' => 'required|in:active,inactive',
        ]);

        Unit::create($validated);
        return redirect()->route('admin.units.index')->with('success', 'Master Unit berhasil ditambahkan.');
    }

    public function edit(Unit $unit)
    {
        $jenisList = UnitType::where('is_active', true)->orderBy('name')->pluck('name');
        return view('admin.units.edit', compact('unit', 'jenisList'));
    }

    public function update(Request $request, Unit $unit)
    {
        $jenisList = UnitType::where('is_active', true)->pluck('name')->toArray();

        $validated = $request->validate([
            'kode'   => 'required|string|max:125|unique:units,kode,' . $unit->id,
            'nama'   => 'required|string|max:255',
            'jenis'  => 'required|in:' . implode(',', $jenisList),
            'status' => 'required|in:active,inactive',
        ]);

        $unit->update($validated);
        return redirect()->route('admin.units.index')->with('success', 'Master Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        $unit->forceDelete();
        return redirect()->route('admin.units.index')->with('success', 'Master Unit berhasil dihapus.');
    }
}
