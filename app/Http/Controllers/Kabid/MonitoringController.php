<?php

namespace App\Http\Controllers\Kabid;

use App\Http\Controllers\Controller;

class MonitoringController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('kabid.monitoring', compact('user'));
    }
}
