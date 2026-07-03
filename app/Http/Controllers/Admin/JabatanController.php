<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::with('parent')->orderBy('level')->orderBy('nama')->paginate(15);
        return view('admin.jabatans.index', compact('jabatans'));
    }

    public function create()
    {
        $parents = Jabatan::whereIn('level', [1, 2, 3])->where('status', 'active')->orderBy('level')->orderBy('nama')->get();
        return view('admin.jabatans.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'level'      => 'required|integer|in:1,2,3,4',
            'parent_id'  => 'nullable|exists:jabatans,id',
            'keterangan' => 'nullable|string',
            'status'     => 'required|in:active,inactive',
        ]);

        // Auto-generate unique kode from nama
        $baseKode = strtoupper(Str::slug($validated['nama'], '_'));
        $kode = $baseKode;
        $counter = 1;
        while (Jabatan::withTrashed()->where('kode', $kode)->exists()) {
            $kode = $baseKode . '_' . $counter++;
        }
        $validated['kode'] = $kode;

        Jabatan::create($validated);

        return redirect()->route('admin.jabatans.index')->with('success', 'Master Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        $parents = Jabatan::whereIn('level', [1, 2, 3])->where('id', '!=', $jabatan->id)->where('status', 'active')->orderBy('level')->orderBy('nama')->get();
        return view('admin.jabatans.edit', compact('jabatan', 'parents'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'level'      => 'required|integer|in:1,2,3,4',
            'parent_id'  => 'nullable|exists:jabatans,id',
            'keterangan' => 'nullable|string',
            'status'     => 'required|in:active,inactive',
        ]);

        // Auto-generate unique kode from nama (kecuali nama tidak berubah)
        $baseKode = strtoupper(Str::slug($validated['nama'], '_'));
        if ($baseKode === $jabatan->kode) {
            // Nama tidak berubah, pakai kode lama
            $validated['kode'] = $jabatan->kode;
        } else {
            $kode = $baseKode;
            $counter = 1;
            while (Jabatan::withTrashed()->where('kode', $kode)->where('id', '!=', $jabatan->id)->exists()) {
                $kode = $baseKode . '_' . $counter++;
            }
            $validated['kode'] = $kode;
        }

        if (!empty($validated['parent_id']) && $validated['parent_id'] == $jabatan->id) {
            return back()->withErrors(['parent_id' => 'Parent Jabatan tidak boleh mengacu ke dirinya sendiri.'])->withInput();
        }

        $jabatan->update($validated);

        return redirect()->route('admin.jabatans.index')->with('success', 'Master Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        if ($jabatan->children()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus jabatan ini karena memiliki child jabatan.');
        }

        $jabatan->forceDelete();

        return redirect()->route('admin.jabatans.index')->with('success', 'Master Jabatan berhasil dihapus.');
    }
}
