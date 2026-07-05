<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Unit;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'unit', 'jabatan'])->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('unit_id') && $request->unit_id != '') {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->has('jabatan_id') && $request->jabatan_id != '') {
            $query->where('jabatan_id', $request->jabatan_id);
        }

        if ($request->has('role') && $request->role != '') {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->paginate(15)->withQueryString();
        $units = Unit::orderBy('nama')->get();
        $jabatans = Jabatan::where('status', 'active')->orderBy('kategori_jabatan')->orderBy('nama')->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'units', 'jabatans', 'roles'));
    }

    public function create()
    {
        $roles = Role::where('status', 'active')->orderBy('name')->get();
        $units = Unit::orderBy('nama')->get();
        $jabatans = Jabatan::where('status', 'active')->orderBy('kategori_jabatan')->orderBy('nama')->get();
        return view('admin.users.create', compact('roles', 'units', 'jabatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip'          => ['required', 'string', Rule::unique('users', 'nip')->withoutTrashed()],
            'nama'         => 'required|string|max:255',
            'email'        => ['nullable', 'email', Rule::unique('users', 'email')->withoutTrashed()],
            'phone_number' => 'required|string|max:20',
            'password'     => 'required|string|min:8|confirmed',
            'role'         => 'required|exists:roles,name',
            'unit_id'      => 'required|exists:units,id',
            'jabatan_id'   => 'required|exists:jabatans,id',
            'status'       => 'required|in:active,inactive,suspended',
        ]);

        $user = User::create([
            'nip'          => $validated['nip'],
            'nama'         => $validated['nama'],
            'email'        => $validated['email'] ?? null,
            'phone_number' => $validated['phone_number'],
            'password'     => Hash::make($validated['password']),
            'unit_id'      => $validated['unit_id'],
            'jabatan_id'   => $validated['jabatan_id'],
            'status'       => $validated['status'],
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Master Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::where('status', 'active')->orderBy('name')->get();
        $units = Unit::orderBy('nama')->get();
        $jabatans = Jabatan::where('status', 'active')->orderBy('kategori_jabatan')->orderBy('nama')->get();
        return view('admin.users.edit', compact('user', 'roles', 'units', 'jabatans'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nip'          => ['required', 'string', Rule::unique('users', 'nip')->withoutTrashed()->ignore($user->id)],
            'nama'         => 'required|string|max:255',
            'email'        => ['nullable', 'email', Rule::unique('users', 'email')->withoutTrashed()->ignore($user->id)],
            'phone_number' => 'required|string|max:20',
            'password'     => 'nullable|string|min:8|confirmed',
            'role'         => 'required|exists:roles,name',
            'unit_id'      => 'required|exists:units,id',
            'jabatan_id'   => 'required|exists:jabatans,id',
            'status'       => 'required|in:active,inactive,suspended',
        ]);

        $data = [
            'nip'          => $validated['nip'],
            'nama'         => $validated['nama'],
            'email'        => $validated['email'] ?? null,
            'phone_number' => $validated['phone_number'],
            'unit_id'      => $validated['unit_id'],
            'jabatan_id'   => $validated['jabatan_id'],
            'status'       => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Master Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === 1 || $user->hasRole('Super Admin')) {
            return back()->with('error', 'Super Admin utama tidak dapat dihapus.');
        }

        $user->forceDelete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
