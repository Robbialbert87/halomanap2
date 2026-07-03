<?php

namespace App\Http\Controllers\KepalaUnit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('kepala_unit.riwayat', compact('user'));
    }
}
