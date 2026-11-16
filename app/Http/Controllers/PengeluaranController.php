<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['bulan' => ['nullable', 'date_format:Y-m']]);

        $bulan = $request->input('bulan', now()->format('Y-m'));
        $awal  = Carbon::createFromFormat('Y-m-d', $bulan . '-01')->startOfMonth();
        $akhir = $awal->copy()->endOfMonth();

        $query = Pengeluaran::with('user')
            ->whereBetween('tanggal', [$awal->toDateString(), $akhir->toDateString()]);

        $total = (clone $query)->sum('nominal');

        $pengeluaran = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('pengeluaran.index', compact('pengeluaran', 'total', 'bulan'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $data['user_id'] = $request->user()->id;

        $p = Pengeluaran::create($data);

        LogAktivitas::catat('Mencatat pengeluaran: ' . $p->keterangan);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $this->izinkan($pengeluaran);

        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $this->izinkan($pengeluaran);

        $pengeluaran->update($this->validasi($request));

        LogAktivitas::catat('Mengubah pengeluaran: ' . $pengeluaran->keterangan);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Hanya admin yang dapat menghapus pengeluaran.');

        $ket = $pengeluaran->keterangan;
        $pengeluaran->delete();

        LogAktivitas::catat('Menghapus pengeluaran: ' . $ket);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'tanggal'    => ['required', 'date', 'before_or_equal:today'],
            'keterangan' => ['required', 'string', 'max:255'],
            'nominal'    => ['required', 'numeric', 'min:1', 'max:999999999999'],
        ], [
            'tanggal.required'        => 'Tanggal wajib diisi.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'keterangan.required'     => 'Keterangan wajib diisi.',
            'nominal.required'        => 'Nominal wajib diisi.',
            'nominal.numeric'         => 'Nominal harus berupa angka.',
            'nominal.min'             => 'Nominal minimal 1.',
        ]);
    }

    private function izinkan(Pengeluaran $pengeluaran): void
    {
        $user = auth()->user();

        abort_unless(
            $user->isAdmin() || $pengeluaran->user_id === $user->id,
            403,
            'Anda hanya dapat mengubah pengeluaran yang Anda catat sendiri.'
        );
    }
}