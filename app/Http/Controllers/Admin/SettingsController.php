<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = [
            'barcode_url' => Setting::getValue('barcode_url', config('app.url')),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'barcode_url' => 'required|url|max:255',
        ]);

        Setting::setValue('barcode_url', $validated['barcode_url'], 'URL untuk QR Code barcode pengaduan');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
