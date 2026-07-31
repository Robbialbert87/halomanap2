<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::withCount('users')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->search);
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('kode', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        // Live filter: balas fragment (card + tabel) untuk fetch tanpa reload
        if ($request->header('X-Live-Filter') === '1') {
            return view('admin.roles._results', ['roles' => $roles]);
        }

        return view('admin.roles.index', [
            'roles' => $roles,
            'totalRoles' => Role::count(),
            'totalUsers' => User::count(),
        ]);
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.create', ['permissions' => $permissions]);
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

        return view('admin.roles.show', ['role' => $role]);
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', ['role' => $role, 'permissions' => $permissions, 'rolePermissions' => $rolePermissions]);
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
