<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appreciation;
use Illuminate\Http\Request;

class ApresiasiController extends Controller
{
    public function index(Request $request)
    {
        $appreciations = Appreciation::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->search);
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('rating'), function ($q) use ($request) {
                $q->where('rating', (int) $request->rating);
            })
            ->latest()
            ->paginate(20);

        // Live filter: balas fragment (list + tabel) untuk fetch tanpa reload
        if ($request->header('X-Live-Filter') === '1') {
            return view('admin.apresiasi._table', ['appreciations' => $appreciations]);
        }

        return view('admin.apresiasi.index', ['appreciations' => $appreciations]);
    }
}
