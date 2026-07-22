<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:125|unique:roles,kode',
            'name' => 'required|string|max:125|unique:roles,name',
            'deskripsi' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'kode' => strtoupper(str_replace(' ', '_', $validated['kode'])),
            'name' => $validated['name'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'guard_name' => 'web',
        ]);

        if ($request->filled('permissions')) {
            $role->syncPermissions(Permission::whereIn('id', $request->permissions)->get());
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function show(Role $role)
    {
        $role->load('users', 'permissions');

        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:125|unique:roles,kode,'.$role->id,
            'name' => 'required|string|max:125|unique:roles,name,'.$role->id,
            'deskripsi' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'kode' => strtoupper(str_replace(' ', '_', $validated['kode'])),
            'name' => $validated['name'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions(Permission::whereIn('id', $request->permissions)->get());
        } else {
            $role->syncPermissions([]);
        }

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
