<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Jabatan;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::with('parent')->orderBy('jenis')->orderBy('nama');

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
        }

        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }

        $units = $query->paginate(15)->withQueryString();
        
        $jenisList = ['Instalasi', 'Bidang', 'Bagian', 'Sub Bagian', 'Komite', 'Tim', 'Pelayanan', 'Penunjang', 'Lainnya'];
        
        return view('admin.units.index', compact('units', 'jenisList'));
    }

    public function create()
    {
        $parents = Unit::where('status', 'active')->orderBy('nama')->get();
        $jenisList = ['Instalasi', 'Bidang', 'Bagian', 'Sub Bagian', 'Komite', 'Tim', 'Pelayanan', 'Penunjang', 'Lainnya'];
        $jabatans = Jabatan::where('status', 'active')->orderBy('level')->orderBy('nama')->get();
        return view('admin.units.create', compact('parents', 'jenisList', 'jabatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'             => 'required|string|max:125|unique:units,kode',
            'nama'             => 'required|string|max:255',
            'jenis'            => 'required|in:Instalasi,Bidang,Bagian,Sub Bagian,Komite,Tim,Pelayanan,Penunjang,Lainnya',
            'is_public'        => 'boolean',
            'parent_id'        => 'nullable|exists:units,id',
            'entry_jabatan_id' => 'nullable|exists:jabatans,id',
            'keterangan'       => 'nullable|string',
            'status'           => 'required|in:active,inactive',
        ]);
        
        $validated['is_public'] = $request->has('is_public');

        Unit::create($validated);
        return redirect()->route('admin.units.index')->with('success', 'Master Unit berhasil ditambahkan.');
    }

    public function edit(Unit $unit)
    {
        $parents = Unit::where('id', '!=', $unit->id)->where('status', 'active')->orderBy('nama')->get();
        $jenisList = ['Instalasi', 'Bidang', 'Bagian', 'Sub Bagian', 'Komite', 'Tim', 'Pelayanan', 'Penunjang', 'Lainnya'];
        $jabatans = Jabatan::where('status', 'active')->orderBy('level')->orderBy('nama')->get();
        return view('admin.units.edit', compact('unit', 'parents', 'jenisList', 'jabatans'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'kode'             => 'required|string|max:125|unique:units,kode,' . $unit->id,
            'nama'             => 'required|string|max:255',
            'jenis'            => 'required|in:Instalasi,Bidang,Bagian,Sub Bagian,Komite,Tim,Pelayanan,Penunjang,Lainnya',
            'is_public'        => 'boolean',
            'parent_id'        => 'nullable|exists:units,id',
            'entry_jabatan_id' => 'nullable|exists:jabatans,id',
            'keterangan'       => 'nullable|string',
            'status'           => 'required|in:active,inactive',
        ]);
        
        $validated['is_public'] = $request->has('is_public');

        if ($validated['parent_id'] == $unit->id) {
            return back()->withErrors(['parent_id' => 'Parent Unit tidak boleh mengacu ke dirinya sendiri.'])->withInput();
        }

        $unit->update($validated);
        return redirect()->route('admin.units.index')->with('success', 'Master Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->children()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus unit ini karena memiliki child unit.');
        }

        $unit->forceDelete();
        return redirect()->route('admin.units.index')->with('success', 'Master Unit berhasil dihapus.');
    }
}
