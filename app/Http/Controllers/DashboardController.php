<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Services\LaporanService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(LaporanService $service)
    {
        $hariIni = today();
        $data = $service->harian($hariIni);

        // Pendapatan (biaya admin) 7 hari terakhir untuk grafik batang
        $dari = today()->subDays(6);
        $per = DB::table('transaksi')
            ->selectRaw('tanggal, SUM(biaya_admin) as pendapatan')
            ->whereBetween('tanggal', [$dari->toDateString(), $hariIni->toDateString()])
            ->groupBy('tanggal')
            ->pluck('pendapatan', 'tanggal');

        $grafik = collect(range(6, 0))->map(function ($i) use ($per) {
            $tgl = today()->subDays($i);

            return [
                'label'   => $tgl->translatedFormat('D'),
                'tanggal' => $tgl->format('d/m'),
                'nilai'   => (float) $per->get($tgl->toDateString(), 0),
            ];
        });

        $maks = max(1, $grafik->max('nilai'));

        $terbaru = Transaksi::with(['jenisTransaksi', 'user'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return view('dashboard', [
            'rekap'       => $data['rekap'],
            'saldo'       => $data['saldo'],
            'saldoSistem' => $data['saldoSistem'],
            'grafik'      => $grafik,
            'maks'        => $maks,
            'terbaru'     => $terbaru,
        ]);
    }
}