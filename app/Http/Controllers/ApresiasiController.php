<?php

namespace App\Http\Controllers;

use App\Models\Appreciation;
use Illuminate\Http\Request;

class ApresiasiController extends Controller
{
    public function create()
    {
        return view('apresiasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'rating' => 'required|integer|between:1,5',
            'message' => 'nullable|string|max:1000',
        ]);

        Appreciation::create([
            'name' => $request->name ?: 'Anonim',
            'rating' => $request->rating,
            'message' => $request->message,
        ]);

        return redirect()->route('apresiasi.sukses');
    }

    public function success()
    {
        return view('apresiasi.success');
    }
}
