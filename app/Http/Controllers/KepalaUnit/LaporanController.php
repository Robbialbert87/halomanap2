<?php

namespace App\Http\Controllers\KepalaUnit;

use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('kepala_unit.laporan', compact('user'));
    }
}
