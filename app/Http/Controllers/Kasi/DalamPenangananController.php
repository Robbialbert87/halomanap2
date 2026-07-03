<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DalamPenangananController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('kasi.dalam_penanganan', compact('user'));
    }
}
