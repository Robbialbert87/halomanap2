<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('kasi.laporan', compact('user'));
    }
}
