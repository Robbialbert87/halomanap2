<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonitoringWorkflowController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('direktur.monitoring_workflow', compact('user'));
    }
}
