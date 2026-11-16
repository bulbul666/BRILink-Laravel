<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Saldo;
use App\Services\LaporanService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    public function index(Request $request, LaporanService $service)
    {
        $request->validate(['tanggal' => ['nullable', 'date']]);

        $tanggal = Carbon::parse($request->input('tanggal', today()->toDateString()))->startOfDay();
        $harian  = $service->harian($tanggal);

        // Saran saldo awal: saldo akhir terakhir sebelum tanggal ini
        $saranAwal = Saldo::where('tanggal', '<', $tanggal->toDateString())
            ->whereNotNull('saldo_akhir')
            ->orderByDesc('tanggal')
            ->value('saldo_akhir');

        return view('saldo.index', [
            'tanggal'     => $tanggal,
            'saldo'       => $harian['saldo'],
            'saldoSistem' => $harian['saldoSistem'],
            'saranAwal'   => $saranAwal,
            'daftar'      => Saldo::orderByDesc('tanggal')->limit(31)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'     => ['required', 'date', 'before_or_equal:today'],
            'saldo_awal'  => ['required', 'numeric', 'min:0'],
            'saldo_akhir' => ['nullable', 'numeric', 'min:0'],
            'catatan'     => ['nullable', 'string', 'max:255'],
        ], [
            'tanggal.required'        => 'Tanggal wajib diisi.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'saldo_awal.required'     => 'Saldo awal wajib diisi.',
            'saldo_awal.numeric'      => 'Saldo awal harus berupa angka.',
            'saldo_awal.min'          => 'Saldo awal tidak boleh negatif.',
            'saldo_akhir.numeric'     => 'Saldo akhir harus berupa angka.',
            'saldo_akhir.min'         => 'Saldo akhir tidak boleh negatif.',
        ]);

        Saldo::updateOrCreate(
            ['tanggal' => $data['tanggal']],
            $data + ['user_id' => $request->user()->id]
        );

        LogAktivitas::catat('Menyimpan saldo harian tanggal ' . $data['tanggal']);

        return redirect()->route('saldo.index', ['tanggal' => $data['tanggal']])
            ->with('success', 'Saldo harian berhasil disimpan.');
    }

    public function destroy(Saldo $saldo)
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Hanya admin yang dapat menghapus saldo harian.');

        $tgl = $saldo->tanggal->toDateString();
        $saldo->delete();

        LogAktivitas::catat('Menghapus saldo harian tanggal ' . $tgl);

        return redirect()->route('saldo.index')->with('success', 'Saldo harian berhasil dihapus.');
    }
}