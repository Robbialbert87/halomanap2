<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationHierarchy;
use App\Models\Unit;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrganizationHierarchyController extends Controller
{
    public function index(Request $request)
    {
        $units = Unit::where('status', 'active')->orderBy('nama')->get();
        
        $query = OrganizationHierarchy::with(['unit', 'jabatan', 'parentJabatan'])
            ->orderBy('unit_id')
            ->orderBy('workflow_level', 'desc');
            
        if ($request->has('unit_id') && $request->unit_id != '') {
            $query->where('unit_id', $request->unit_id);
        }
        
        $hierarchies = $query->paginate(20)->withQueryString();
        
        return view('admin.organization-hierarchies.index', compact('hierarchies', 'units'));
    }

    public function create()
    {
        $units = Unit::where('status', 'active')->orderBy('nama')->get();
        $jabatans = Jabatan::where('status', 'active')->orderBy('level')->orderBy('nama')->get();
        return view('admin.organization-hierarchies.create', compact('units', 'jabatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id'           => 'required|exists:units,id',
            'jabatan_id'        => [
                'required',
                'exists:jabatans,id',
                Rule::unique('organization_hierarchies')->where(function ($query) use ($request) {
                    return $query->where('unit_id', $request->unit_id)
                                 ->whereNull('deleted_at');
                })
            ],
            'parent_jabatan_id' => 'nullable|exists:jabatans,id',
            'workflow_level'    => [
                'required',
                'integer',
                'min:1',
                Rule::unique('organization_hierarchies')->where(function ($query) use ($request) {
                    return $query->where('unit_id', $request->unit_id)
                                 ->whereNull('deleted_at');
                })
            ],
            'is_workflow_start' => 'boolean',
            'is_workflow_end'   => 'boolean',
            'can_escalate'      => 'boolean',
            'status'            => 'required|in:active,inactive',
        ], [
            'jabatan_id.unique'     => 'Jabatan ini sudah ada di dalam Unit yang sama.',
            'workflow_level.unique' => 'Level eskalasi ini sudah digunakan di dalam Unit yang sama.',
        ]);

        if ($validated['jabatan_id'] == $validated['parent_jabatan_id']) {
            return back()->withErrors(['parent_jabatan_id' => 'Atasan tidak boleh mengacu ke diri sendiri.'])->withInput();
        }

        // Auto-calculate urutan_level: reversed from workflow_level
        $maxLevel = OrganizationHierarchy::where('unit_id', $validated['unit_id'])->max('workflow_level') ?? 0;
        $validated['urutan_level'] = $maxLevel + 1;

        OrganizationHierarchy::create($validated);
        
        return redirect()->route('admin.organization-hierarchies.index', ['unit_id' => $validated['unit_id']])
            ->with('success', 'Struktur Organisasi berhasil ditambahkan.');
    }

    public function edit(OrganizationHierarchy $organizationHierarchy)
    {
        $units = Unit::where('status', 'active')->orderBy('nama')->get();
        $jabatans = Jabatan::where('status', 'active')->orderBy('level')->orderBy('nama')->get();
        return view('admin.organization-hierarchies.edit', compact('organizationHierarchy', 'units', 'jabatans'));
    }

    public function update(Request $request, OrganizationHierarchy $organizationHierarchy)
    {
        $validated = $request->validate([
            'unit_id'           => 'required|exists:units,id',
            'jabatan_id'        => [
                'required',
                'exists:jabatans,id',
                Rule::unique('organization_hierarchies')->where(function ($query) use ($request) {
                    return $query->where('unit_id', $request->unit_id)
                                 ->whereNull('deleted_at');
                })->ignore($organizationHierarchy->id)
            ],
            'parent_jabatan_id' => 'nullable|exists:jabatans,id',
            'workflow_level'    => [
                'required',
                'integer',
                'min:1',
                Rule::unique('organization_hierarchies')->where(function ($query) use ($request) {
                    return $query->where('unit_id', $request->unit_id)
                                 ->whereNull('deleted_at');
                })->ignore($organizationHierarchy->id)
            ],
            'is_workflow_start' => 'boolean',
            'is_workflow_end'   => 'boolean',
            'can_escalate'      => 'boolean',
            'status'            => 'required|in:active,inactive',
        ], [
            'jabatan_id.unique'     => 'Jabatan ini sudah ada di dalam Unit yang sama.',
            'workflow_level.unique' => 'Level eskalasi ini sudah digunakan di dalam Unit yang sama.',
        ]);

        if ($validated['jabatan_id'] == $validated['parent_jabatan_id']) {
            return back()->withErrors(['parent_jabatan_id' => 'Atasan tidak boleh mengacu ke diri sendiri.'])->withInput();
        }

        $organizationHierarchy->update(array_merge($validated, [
            'urutan_level'      => $organizationHierarchy->urutan_level,
            'is_workflow_start' => $request->has('is_workflow_start'),
            'is_workflow_end'   => $request->has('is_workflow_end'),
            'can_escalate'      => $request->has('can_escalate'),
        ]));
        
        return redirect()->route('admin.organization-hierarchies.index', ['unit_id' => $validated['unit_id']])
            ->with('success', 'Struktur Organisasi berhasil diperbarui.');
    }

    public function destroy(OrganizationHierarchy $organizationHierarchy)
    {
        // Cek jika jadi parent
        $isParent = OrganizationHierarchy::where('unit_id', $organizationHierarchy->unit_id)
            ->where('parent_jabatan_id', $organizationHierarchy->jabatan_id)
            ->exists();
            
        if ($isParent) {
            return back()->with('error', 'Tidak dapat dihapus karena jabatan ini menjadi atasan dari jabatan lain di struktur ini.');
        }

        $organizationHierarchy->delete();
        
        return back()->with('success', 'Struktur Organisasi berhasil dihapus.');
    }
}
