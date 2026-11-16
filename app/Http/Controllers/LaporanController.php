<?php

namespace App\Http\Controllers;

use App\Services\LaporanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(private LaporanService $service)
    {
    }

    public function harian(Request $request)
    {
        $tanggal = $this->tanggal($request);

        return view('laporan.harian', $this->service->harian($tanggal) + ['tanggal' => $tanggal]);
    }

    public function harianPdf(Request $request)
    {
        $tanggal = $this->tanggal($request);

        return Pdf::loadView('laporan.pdf', $this->service->harian($tanggal) + [
                'judul' => 'Laporan Harian Transaksi — ' . $tanggal->translatedFormat('d F Y'),
            ])
            ->setPaper('a4', 'portrait')
            ->download('laporan-harian-' . $tanggal->toDateString() . '.pdf');
    }

    public function bulanan(Request $request)
    {
        $bulan = $this->bulan($request);

        return view('laporan.bulanan', $this->service->bulanan($bulan) + ['bulan' => $bulan]);
    }

    public function bulananPdf(Request $request)
    {
        $bulan = $this->bulan($request);

        return Pdf::loadView('laporan.pdf', $this->service->bulanan($bulan) + [
                'judul' => 'Laporan Bulanan Transaksi — ' . $bulan->translatedFormat('F Y'),
            ])
            ->setPaper('a4', 'portrait')
            ->download('laporan-bulanan-' . $bulan->format('Y-m') . '.pdf');
    }

    private function tanggal(Request $request): Carbon
    {
        $request->validate(['tanggal' => ['nullable', 'date']]);

        return Carbon::parse($request->input('tanggal', today()->toDateString()))->startOfDay();
    }

    private function bulan(Request $request): Carbon
    {
        $request->validate(['bulan' => ['nullable', 'date_format:Y-m']]);

        return Carbon::createFromFormat('Y-m-d', $request->input('bulan', now()->format('Y-m')) . '-01')->startOfDay();
    }
}