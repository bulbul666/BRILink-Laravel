<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Saldo Harian</h1>
    </x-slot>

    @php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp

    <div class="mx-auto max-w-7xl space-y-6">

        @if (session('success'))
            <div class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-800 ring-1 ring-blue-100">{{ session('success') }}</div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Form --}}
            <div class="space-y-4 lg:col-span-2">
                <form method="GET" class="flex items-end gap-3 rounded-2xl bg-white p-5 ring-1 ring-slate-200">
                    <div>
                        <label class="block text-xs text-slate-500">Pilih tanggal</label>
                        <input type="date" name="tanggal" value="{{ $tanggal->toDateString() }}" max="{{ now()->toDateString() }}"
                               class="mt-1 rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                    </div>
                    <button class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Tampilkan</button>
                </form>

                <form method="POST" action="{{ route('saldo.store') }}"
                      x-data class="rounded-2xl bg-white p-5 ring-1 ring-slate-200">
                    @csrf
                    <input type="hidden" name="tanggal" value="{{ $tanggal->toDateString() }}">

                    <h2 class="text-sm font-semibold text-slate-700">
                        Saldo tanggal {{ $tanggal->translatedFormat('d F Y') }}
                        @if ($saldo)
                            <span class="ml-2 rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">Sudah diisi</span>
                        @endif
                    </h2>

                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Saldo Awal (Rp)</label>
                            <input type="number" name="saldo_awal" min="0" step="1"
                                   value="{{ old('saldo_awal', $saldo ? (int) $saldo->saldo_awal : ($saranAwal !== null ? (int) $saranAwal : '')) }}"
                                   class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            @if (! $saldo && $saranAwal !== null)
                                <p class="mt-1 text-xs text-slate-400">Terisi dari saldo akhir hari sebelumnya.</p>
                            @endif
                            @error('saldo_awal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Saldo Akhir (Rp)</label>
                            <input type="number" name="saldo_akhir" min="0" step="1" x-ref="akhir"
                                   value="{{ old('saldo_akhir', $saldo && $saldo->saldo_akhir !== null ? (int) $saldo->saldo_akhir : '') }}"
                                   class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            <p class="mt-1 text-xs text-slate-400">Isi di akhir hari berdasarkan uang di laci.</p>
                            @error('saldo_akhir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Catatan (opsional)</label>
                            <input type="text" name="catatan" maxlength="255"
                                   value="{{ old('catatan', $saldo->catatan ?? '') }}"
                                   class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            @error('catatan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        <button class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">Simpan</button>
                        @if ($saldoSistem !== null)
                            <button type="button" @click="$refs.akhir.value = {{ (int) round($saldoSistem) }}"
                                    class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                Pakai hitungan sistem
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Panel hitungan sistem --}}
            <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200">
                <h3 class="text-sm font-semibold text-slate-700">Hitungan Sistem</h3>

                @if ($saldoSistem !== null)
                    <div class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">{{ $rp($saldoSistem) }}</div>
                    <p class="mt-1 text-xs text-slate-500">Saldo akhir menurut transaksi yang tercatat.</p>

                    @if ($saldo->saldo_akhir !== null)
                        @php $selisih = (float) $saldo->saldo_akhir - $saldoSistem; @endphp
                        <div class="mt-4 rounded-lg px-3 py-2 text-sm {{ abs($selisih) < 0.01 ? 'bg-blue-50 text-blue-800' : 'bg-red-50 text-red-700' }}">
                            Selisih {{ $rp($selisih) }}
                            {{ abs($selisih) < 0.01 ? '— sesuai' : '— perlu dicek' }}
                        </div>
                    @endif
                @else
                    <p class="mt-4 text-sm text-slate-500">
                        Simpan saldo awal terlebih dahulu. Setelah itu sistem akan menghitung saldo akhir dari transaksi dan pengeluaran hari ini.
                    </p>
                @endif

                <p class="mt-5 border-t border-slate-100 pt-4 text-xs leading-relaxed text-slate-400">
                    Rumus: saldo awal + nominal masuk &minus; nominal keluar + biaya admin &minus; pengeluaran.
                </p>
            </div>
        </div>

        {{-- Riwayat --}}
        <div class="overflow-hidden rounded-2xl bg-white ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-700">Riwayat 31 Hari Terakhir</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium">Tanggal</th>
                            <th class="px-5 py-3 text-right font-medium">Saldo Awal</th>
                            <th class="px-5 py-3 text-right font-medium">Saldo Akhir</th>
                            <th class="px-5 py-3 text-left font-medium">Catatan</th>
                            <th class="px-5 py-3 text-center font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($daftar as $s)
                            <tr>
                                <td class="px-5 py-3">{{ $s->tanggal->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 text-right">{{ number_format($s->saldo_awal, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-right">
                                    {{ $s->saldo_akhir !== null ? number_format($s->saldo_akhir, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-5 py-3 text-slate-500">{{ $s->catatan ?: '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-center">
                                    <a href="{{ route('saldo.index', ['tanggal' => $s->tanggal->toDateString()]) }}"
                                       class="font-medium text-blue-700 hover:underline">Ubah</a>
                                    @if (auth()->user()->isAdmin())
                                        <form action="{{ route('saldo.destroy', $s) }}" method="POST" class="ml-3 inline"
                                              onsubmit="return confirm('Hapus saldo tanggal {{ $s->tanggal->format('d/m/Y') }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="font-medium text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Belum ada data saldo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>