<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('direktur.profil', compact('user'));
    }
}
