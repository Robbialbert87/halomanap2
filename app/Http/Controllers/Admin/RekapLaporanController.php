<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekapPengaduan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapLaporanController extends Controller
{
    public function index(Request $request)
    {
        $semua = $request->boolean('semua');
        $bulan = $semua ? null : intval($request->input('bulan', now()->month));
        $tahun = $semua ? null : intval($request->input('tahun', now()->year));

        $query = RekapPengaduan::query();

        if (!$semua && $bulan && $tahun) {
            $awal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
            $akhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->toDateString();
            $query->where('tanggal', '>=', $awal)->where('tanggal', '<=', $akhir);
        }

        $data = $query->latest('tanggal')->get();

        $kategoriLabels = [
            'Tenaga Kesehatan',
            'Sarana & Prasarana',
            'Keluhan Lainnya',
            'Ketersediaan Obat',
        ];

        $chartData = collect($kategoriLabels)->mapWithKeys(fn($k) => [
            $k => $data->where('kategori', $k)->count(),
        ])->toArray();

        return view('admin.rekap-laporan.index', compact('data', 'bulan', 'tahun', 'chartData', 'semua'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'         => 'required|date',
            'nama'            => 'nullable|string|max:255',
            'nomor_pelapor'   => 'nullable|string|max:20',
            'via_pengaduan'   => 'required|string|max:255',
            'kategori'        => 'required|in:Tenaga Kesehatan,Sarana & Prasarana,Keluhan Lainnya,Ketersediaan Obat',
            'keluhan'         => 'required|string',
            'tindak_lanjut'   => 'nullable|string',
            'status'          => 'required|in:Baru,Diproses,Selesai',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();

        RekapPengaduan::create($validated);

        $tanggalDate = Carbon::parse($validated['tanggal']);

        return redirect()->route('admin.rekap-laporan', [
            'bulan' => $tanggalDate->month,
            'tahun' => $tanggalDate->year,
        ])->with('success', 'Data rekap pengaduan berhasil ditambahkan.');
    }

    public function update(Request $request, RekapPengaduan $rekapLaporan)
    {
        $validated = $request->validate([
            'tanggal'         => 'required|date',
            'nama'            => 'nullable|string|max:255',
            'nomor_pelapor'   => 'nullable|string|max:20',
            'via_pengaduan'   => 'required|string|max:255',
            'kategori'        => 'required|in:Tenaga Kesehatan,Sarana & Prasarana,Keluhan Lainnya,Ketersediaan Obat',
            'keluhan'         => 'required|string',
            'tindak_lanjut'   => 'nullable|string',
            'status'          => 'required|in:Baru,Diproses,Selesai',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $rekapLaporan->update($validated);

        $tanggalDate = Carbon::parse($validated['tanggal']);

        return redirect()->route('admin.rekap-laporan', [
            'bulan' => $tanggalDate->month,
            'tahun' => $tanggalDate->year,
        ])->with('success', 'Data rekap pengaduan berhasil diperbarui.');
    }

    public function destroy(RekapPengaduan $rekapLaporan, Request $request)
    {
        $bulan = intval($request->query('bulan', now()->month));
        $tahun = intval($request->query('tahun', now()->year));

        $rekapLaporan->delete();

        return redirect()->route('admin.rekap-laporan', [
            'bulan' => $bulan,
            'tahun' => $tahun,
        ])->with('success', 'Data rekap pengaduan berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $bulan = intval($request->input('bulan', now()->month));
        $tahun = intval($request->input('tahun', now()->year));

        $awal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $akhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->toDateString();

        $data = RekapPengaduan::where('tanggal', '>=', $awal)
            ->where('tanggal', '<=', $akhir)
            ->get();

        $kategoriLabels = [
            'Tenaga Kesehatan',
            'Sarana & Prasarana',
            'Keluhan Lainnya',
            'Ketersediaan Obat',
        ];

        $chartData = collect($kategoriLabels)->mapWithKeys(fn($k) => [
            $k => $data->where('kategori', $k)->count(),
        ])->toArray();

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        $values = [
            $chartData['Tenaga Kesehatan'] ?? 0,
            $chartData['Sarana & Prasarana'] ?? 0,
            $chartData['Keluhan Lainnya'] ?? 0,
            $chartData['Ketersediaan Obat'] ?? 0,
        ];

        $labels = ['Tenaga Kesehatan', 'Sarana & Prasarana', 'Keluhan Lainnya', 'Ketersediaan Obat'];

        $kategoriColors = [
            ['label' => 'Tenaga Kesehatan', 'border' => '#3874ff'],
            ['label' => 'Sarana & Prasarana', 'border' => '#f59e0b'],
            ['label' => 'Keluhan Lainnya', 'border' => '#f97316'],
            ['label' => 'Ketersediaan Obat', 'border' => '#22c55e'],
        ];

        $datasets = collect($kategoriColors)->map(function ($k) use ($values) {
            [$r, $g, $b] = sscanf($k['border'], '#%02x%02x%02x');

            return [
                'label' => $k['label'],
                'data' => $values,
                'borderColor' => $k['border'],
                'backgroundColor' => sprintf('rgba(%d,%d,%d,0.20)', $r, $g, $b),
                'fill' => true,
                'tension' => 0.4,
                'borderWidth' => 2.5,
                'pointRadius' => 5,
                'pointHoverRadius' => 6,
                'pointBackgroundColor' => '#ffffff',
                'pointBorderColor' => $k['border'],
                'pointBorderWidth' => 2.5,
            ];
        })->all();

        $chartConfig = [
            'version' => '2',
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets,
            ],
            'options' => [
                'legend' => ['display' => false],
                'animation' => false,
                'scales' => [
                    'xAxes' => [[
                        'ticks' => [
                            'fontColor' => '#6b7280',
                            'fontSize' => 11,
                            'maxRotation' => 30,
                        ],
                        'gridLines' => ['display' => false],
                    ]],
                    'yAxes' => [[
                        'ticks' => [
                            'fontColor' => '#9ca3af',
                            'fontSize' => 11,
                            'beginAtZero' => true,
                            'precision' => 0,
                            'suggestedMax' => max($values) + 1,
                        ],
                        'gridLines' => ['color' => 'rgba(0,0,0,0.04)'],
                    ]],
                ],
            ],
        ];

        $chartUrl = 'https://quickchart.io/chart?w=600&h=300&bkg=white&_=' . time() . '&c=' . urlencode(json_encode($chartConfig));

        $chartImage = null;
        $ch = curl_init($chartUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $imageData = curl_exec($ch);
        curl_close($ch);
        if ($imageData !== false && strlen($imageData) > 1000) {
            $chartImage = 'data:image/png;base64,' . base64_encode($imageData);
        }

        $pdf = Pdf::loadView('admin.rekap-laporan.pdf', compact('chartData', 'bulan', 'tahun', 'namaBulan', 'chartImage'))
            ->setPaper('a4', 'portrait');

        $filename = 'rekap-laporan-inm-' . $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->download($filename)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
