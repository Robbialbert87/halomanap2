<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('kasi.profil', compact('user'));
    }
}
