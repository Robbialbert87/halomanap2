<?php

namespace App\Http\Controllers\Kabid;

use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('kabid.laporan', compact('user'));
    }
}
