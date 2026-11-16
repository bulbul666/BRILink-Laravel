<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Dashboard</h1>
    </x-slot>

    @php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp

    <div class="mx-auto max-w-7xl space-y-6">

        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Halo, {{ auth()->user()->name }}</h2>
            <p class="text-sm text-slate-500">Ringkasan aktivitas hari ini.</p>
        </div>

        {{-- Kartu ringkasan --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200">
                <div class="text-sm text-slate-500">Transaksi Hari Ini</div>
                <div class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($rekap['jumlah'], 0, ',', '.') }}</div>
                <div class="mt-1 text-xs text-slate-500">Nominal {{ $rp($rekap['total_nominal']) }}</div>
            </div>

            <div class="rounded-2xl bg-blue-600 p-5 text-white">
                <div class="text-sm text-blue-100">Pendapatan (Biaya Admin)</div>
                <div class="mt-2 text-2xl font-semibold">{{ $rp($rekap['pendapatan']) }}</div>
                <div class="mt-1 text-xs text-blue-100">Laba bersih {{ $rp($rekap['laba']) }}</div>
            </div>

            <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200">
                <div class="text-sm text-slate-500">Pengeluaran</div>
                <div class="mt-2 text-2xl font-semibold text-slate-900">{{ $rp($rekap['pengeluaran']) }}</div>
                <div class="mt-1 text-xs text-slate-500">Biaya operasional hari ini</div>
            </div>

            <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200">
                <div class="text-sm text-slate-500">Saldo Kas (hitung sistem)</div>
                @if ($saldoSistem !== null)
                    <div class="mt-2 text-2xl font-semibold text-slate-900">{{ $rp($saldoSistem) }}</div>
                    <div class="mt-1 text-xs text-slate-500">Saldo awal {{ $rp($saldo->saldo_awal) }}</div>
                @else
                    <div class="mt-2 text-lg font-semibold text-slate-400">Belum diisi</div>
                    <a href="{{ route('saldo.index') }}" class="mt-1 inline-block text-xs font-medium text-blue-700 hover:underline">
                        Isi saldo awal &rarr;
                    </a>
                @endif
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">

            {{-- Grafik 7 hari --}}
            <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200 lg:col-span-2">
                <div class="flex items-baseline justify-between">
                    <h3 class="text-sm font-semibold text-slate-700">Pendapatan 7 Hari Terakhir</h3>
                    <span class="text-xs text-slate-400">Biaya admin</span>
                </div>

                <div class="mt-6 flex h-44 items-end gap-3">
                    @foreach ($grafik as $g)
                        <div class="flex h-full flex-1 items-end justify-center" title="{{ $g['tanggal'] }}: {{ $rp($g['nilai']) }}">
                            <div class="w-full max-w-10 rounded-t-lg {{ $g['nilai'] > 0 ? 'bg-blue-600' : 'bg-slate-200' }}"
                                 style="height: {{ max(3, round($g['nilai'] / $maks * 100)) }}%"></div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-2 flex gap-3">
                    @foreach ($grafik as $g)
                        <div class="flex-1 text-center">
                            <div class="text-xs font-medium text-slate-600">{{ $g['label'] }}</div>
                            <div class="text-[10px] text-slate-400">{{ $g['tanggal'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Aksi cepat --}}
            <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200">
                <h3 class="text-sm font-semibold text-slate-700">Aksi Cepat</h3>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('transaksi.create') }}"
                       class="block rounded-lg bg-blue-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-blue-700">
                        + Tambah Transaksi
                    </a>
                    <a href="{{ route('pengeluaran.index') }}"
                       class="block rounded-lg border border-slate-200 px-4 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Catat Pengeluaran
                    </a>
                    <a href="{{ route('saldo.index') }}"
                       class="block rounded-lg border border-slate-200 px-4 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Isi Saldo Harian
                    </a>
                    <a href="{{ route('laporan.harian') }}"
                       class="block rounded-lg border border-slate-200 px-4 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Lihat Laporan Hari Ini
                    </a>
                </div>
            </div>
        </div>

        {{-- Transaksi terbaru --}}
        <div class="overflow-hidden rounded-2xl bg-white ring-1 ring-slate-200">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-700">Transaksi Terbaru</h3>
                <a href="{{ route('transaksi.index') }}" class="text-xs font-medium text-blue-700 hover:underline">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium">Kode</th>
                            <th class="px-5 py-3 text-left font-medium">Tanggal</th>
                            <th class="px-5 py-3 text-left font-medium">Jenis</th>
                            <th class="px-5 py-3 text-right font-medium">Nominal</th>
                            <th class="px-5 py-3 text-right font-medium">Biaya Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($terbaru as $t)
                            <tr>
                                <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $t->kode_transaksi }}</td>
                                <td class="px-5 py-3">{{ $t->tanggal->format('d/m/Y') }}</td>
                                <td class="px-5 py-3">{{ $t->jenisTransaksi->nama }}</td>
                                <td class="px-5 py-3 text-right">{{ number_format($t->nominal, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-right">{{ number_format($t->biaya_admin, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>