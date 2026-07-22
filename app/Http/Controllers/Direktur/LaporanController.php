<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('direktur.laporan', compact('user'));
    }
}
