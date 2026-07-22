<?php

namespace App\Http\Controllers\Kabid;

use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('kabid.profil', compact('user'));
    }
}
