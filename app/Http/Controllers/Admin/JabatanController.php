<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jabatan::orderBy('kategori_jabatan')->orderBy('nama');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('kategori_jabatan', 'like', "%{$search}%");
            });
        }

        $jabatans = $query->paginate(7)->withQueryString()->onEachSide(2);

        return view('admin.jabatans.index', compact('jabatans'));
    }

    public function create()
    {
        $kategoriList = ['Direktur', 'Kabid', 'Kabag', 'Kasi', 'Kasubbag', 'Kepala Unit', 'Petugas'];

        return view('admin.jabatans.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_jabatan' => 'required|in:Direktur,Kabid,Kabag,Kasi,Kasubbag,Kepala Unit,Petugas',
            'status' => 'required|in:active,inactive',
        ]);

        $baseKode = strtoupper(Str::slug($validated['nama'], '_'));
        $kode = $baseKode;
        $counter = 1;
        while (Jabatan::withTrashed()->where('kode', $kode)->exists()) {
            $kode = $baseKode.'_'.$counter++;
        }
        $validated['kode'] = $kode;

        Jabatan::create($validated);

        return redirect()->route('admin.jabatans.index')->with('success', 'Master Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        $kategoriList = ['Direktur', 'Kabid', 'Kabag', 'Kasi', 'Kasubbag', 'Kepala Unit', 'Petugas'];

        return view('admin.jabatans.edit', compact('jabatan', 'kategoriList'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_jabatan' => 'required|in:Direktur,Kabid,Kabag,Kasi,Kasubbag,Kepala Unit,Petugas',
            'status' => 'required|in:active,inactive',
        ]);

        $baseKode = strtoupper(Str::slug($validated['nama'], '_'));
        if ($baseKode === $jabatan->kode) {
            $validated['kode'] = $jabatan->kode;
        } else {
            $kode = $baseKode;
            $counter = 1;
            while (Jabatan::withTrashed()->where('kode', $kode)->where('id', '!=', $jabatan->id)->exists()) {
                $kode = $baseKode.'_'.$counter++;
            }
            $validated['kode'] = $kode;
        }

        $jabatan->update($validated);

        return redirect()->route('admin.jabatans.index')->with('success', 'Master Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->forceDelete();

        return redirect()->route('admin.jabatans.index')->with('success', 'Master Jabatan berhasil dihapus.');
    }
}
