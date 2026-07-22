<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appreciation;

class ApresiasiController extends Controller
{
    public function index()
    {
        $appreciations = Appreciation::latest()->paginate(20);

        return view('admin.apresiasi.index', compact('appreciations'));
    }
}
