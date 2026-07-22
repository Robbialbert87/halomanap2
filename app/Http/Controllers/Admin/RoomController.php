<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Unit;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::with('unit');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('unit', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $rooms = $query->orderBy('name')->paginate(7)->withQueryString()->onEachSide(2);

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $units = Unit::orderBy('nama')->get();

        return view('admin.rooms.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
        ]);
        Room::create($request->all());

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $room = Room::findOrFail($id);
        $units = Unit::orderBy('nama')->get();

        return view('admin.rooms.edit', compact('room', 'units'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
        ]);
        $room = Room::findOrFail($id);
        $room->update($request->all());

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
