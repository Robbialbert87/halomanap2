<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('name')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'        => 'required|string|max:125|unique:roles,kode',
            'name'        => 'required|string|max:125|unique:roles,name',
            'deskripsi'   => 'nullable|string',
            'status'      => 'nullable|in:active,inactive',
        ]);

        Role::create([
            'kode'       => strtoupper(str_replace(' ', '_', $validated['kode'])),
            'name'       => $validated['name'],
            'deskripsi'  => $validated['deskripsi'] ?? null,
            'status'     => $validated['status'] ?? 'active',
            'guard_name' => 'web'
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function show(Role $role)
    {
        $role->load('users');
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'kode'        => 'required|string|max:125|unique:roles,kode,' . $role->id,
            'name'        => 'required|string|max:125|unique:roles,name,' . $role->id,
            'deskripsi'   => 'nullable|string',
            'status'      => 'nullable|in:active,inactive',
        ]);

        $role->update([
            'kode'      => strtoupper(str_replace(' ', '_', $validated['kode'])),
            'name'      => $validated['name'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status'    => $validated['status'] ?? 'active',
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role Super Admin tidak dapat dihapus.');
        }
        $role->forceDelete();
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}
