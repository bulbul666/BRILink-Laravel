<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiRequest;
use App\Models\JenisTransaksi;
use App\Models\LogAktivitas;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{

    public function index(Request $request)
{
    $query = $this->filter($request);

    $ringkasan = [
        'jumlah'  => (clone $query)->count(),
        'nominal' => (clone $query)->sum('nominal'),
        'biaya'   => (clone $query)->sum('biaya_admin'),
    ];

    $transaksi = $query
        ->with(['jenisTransaksi', 'user', 'pelanggan'])
        ->orderByDesc('tanggal')
        ->orderByDesc('id')
        ->paginate(15)
        ->withQueryString();

    $jenisList = JenisTransaksi::orderBy('nama')->get();

    return view('transaksi.index', compact('transaksi', 'jenisList', 'ringkasan'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transaksi.create', [
            'jenisList'     => JenisTransaksi::where('aktif', true)->orderBy('nama')->get(),
            'pelangganList' => Pelanggan::orderBy('nama')->get(),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $transaksi = DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;
            $data['kode_transaksi'] = Transaksi::buatKode($data['tanggal']);

            return Transaksi::create($data);
             });

        LogAktivitas::catat('Menambah transaksi ' . $transaksi->kode_transaksi);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi ' . $transaksi->kode_transaksi . ' berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        $this->izinkan($transaksi);

        return view('transaksi.edit', [
            'transaksi'     => $transaksi,
            'jenisList'     => JenisTransaksi::orderBy('nama')->get(),
            'pelangganList' => Pelanggan::orderBy('nama')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $this->izinkan($transaksi);

        $transaksi->update($request->validated());

        LogAktivitas::catat('Mengubah transaksi ' . $transaksi->kode_transaksi);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi ' . $transaksi->kode_transaksi . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Hanya admin yang dapat menghapus transaksi.');

        $kode = $transaksi->kode_transaksi;
        $transaksi->delete();

        LogAktivitas::catat('Menghapus transaksi ' . $kode);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi ' . $kode . ' berhasil dihapus.');
    }

    private function filter(Request $request)
    {
        return Transaksi::query()
            ->when($request->filled('tanggal'), fn ($q) => $q->whereDate('tanggal', $request->tanggal))
            ->when($request->filled('jenis'), fn ($q) => $q->where('jenis_transaksi_id', $request->jenis))
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(function ($w) use ($request) {
                    $w->where('kode_transaksi', 'like', '%' . $request->q . '%')
                      ->orWhere('keterangan', 'like', '%' . $request->q . '%');
                });
            });
    }

    private function izinkan(Transaksi $transaksi): void
    {
        $user = auth()->user();

        abort_unless(
            $user->isAdmin() || $transaksi->user_id === $user->id,
            403,
            'Anda hanya dapat mengubah transaksi yang Anda input sendiri.'
        );
    }
}
