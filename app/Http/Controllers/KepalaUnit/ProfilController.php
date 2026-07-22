<?php

namespace App\Http\Controllers\KepalaUnit;

use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('kepala_unit.profil', compact('user'));
    }
}
