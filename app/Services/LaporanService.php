<?php

namespace App\Services;

use App\Models\Pengeluaran;
use App\Models\Saldo;
use App\Models\Transaksi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class LaporanService
{
    /** Rekap total dan per jenis transaksi untuk rentang tanggal. */
    public function rekap(Carbon $dari, Carbon $sampai): array
    {
        $range = [$dari->toDateString(), $sampai->toDateString()];

        $perJenis = Transaksi::query()
            ->join('jenis_transaksi', 'jenis_transaksi.id', '=', 'transaksi.jenis_transaksi_id')
            ->whereBetween('transaksi.tanggal', $range)
            ->groupBy('jenis_transaksi.id', 'jenis_transaksi.nama', 'jenis_transaksi.efek_kas')
            ->selectRaw('jenis_transaksi.nama, jenis_transaksi.efek_kas,
                         COUNT(*) as jumlah,
                         SUM(transaksi.nominal) as total_nominal,
                         SUM(transaksi.biaya_admin) as total_biaya')
            ->orderBy('jenis_transaksi.nama')
            ->get();

        $pendapatan  = (float) $perJenis->sum('total_biaya');
        $pengeluaran = (float) Pengeluaran::whereBetween('tanggal', $range)->sum('nominal');

        return [
            'per_jenis'     => $perJenis,
            'jumlah'        => (int) $perJenis->sum('jumlah'),
            'total_nominal' => (float) $perJenis->sum('total_nominal'),
            'nominal_masuk' => (float) $perJenis->where('efek_kas', 'masuk')->sum('total_nominal'),
            'nominal_keluar'=> (float) $perJenis->where('efek_kas', 'keluar')->sum('total_nominal'),
            'pendapatan'    => $pendapatan,
            'pengeluaran'   => $pengeluaran,
            'laba'          => $pendapatan - $pengeluaran,
        ];
    }

    public function harian(Carbon $tanggal): array
    {
        $rekap = $this->rekap($tanggal, $tanggal);

        $transaksi = Transaksi::with(['jenisTransaksi', 'user'])
            ->whereDate('tanggal', $tanggal->toDateString())
            ->orderBy('id')
            ->get();

        $saldo = Saldo::whereDate('tanggal', $tanggal->toDateString())->first();

        // Saldo kas = saldo awal + nominal masuk - nominal keluar + biaya admin - pengeluaran
        $saldoSistem = $saldo
            ? (float) $saldo->saldo_awal
                + $rekap['nominal_masuk'] - $rekap['nominal_keluar']
                + $rekap['pendapatan'] - $rekap['pengeluaran']
            : null;

        return compact('rekap', 'transaksi', 'saldo', 'saldoSistem');
    }

    public function bulanan(Carbon $bulan): array
    {
        $dari   = $bulan->copy()->startOfMonth();
        $sampai = $bulan->copy()->endOfMonth();
        $range  = [$dari->toDateString(), $sampai->toDateString()];

        $trx = DB::table('transaksi')
            ->selectRaw('tanggal, COUNT(*) as jumlah, SUM(nominal) as total_nominal, SUM(biaya_admin) as pendapatan')
            ->whereBetween('tanggal', $range)
            ->groupBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $peng = DB::table('pengeluaran')
            ->selectRaw('tanggal, SUM(nominal) as total')
            ->whereBetween('tanggal', $range)
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $perHari = [];
        foreach (CarbonPeriod::create($dari, $sampai) as $hari) {
            $key = $hari->toDateString();
            $t = $trx->get($key);

            $pendapatan  = (float) ($t->pendapatan ?? 0);
            $pengeluaran = (float) $peng->get($key, 0);

            $perHari[] = [
                'tanggal'       => $hari->copy(),
                'jumlah'        => (int) ($t->jumlah ?? 0),
                'total_nominal' => (float) ($t->total_nominal ?? 0),
                'pendapatan'    => $pendapatan,
                'pengeluaran'   => $pengeluaran,
                'laba'          => $pendapatan - $pengeluaran,
            ];
        }

        return [
            'rekap'   => $this->rekap($dari, $sampai),
            'perHari' => $perHari,
        ];
    }
}