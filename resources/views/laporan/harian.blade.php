<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Harian</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <form method="GET" class="bg-white shadow-sm sm:rounded-lg p-4 flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs text-gray-500">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal->toDateString() }}"
                           class="border-gray-300 rounded-md text-sm">
                </div>
                <button class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md">Tampilkan</button>
                <a href="{{ route('laporan.harian.pdf', ['tanggal' => $tanggal->toDateString()]) }}"
                   class="px-4 py-2 bg-red-600 text-white text-sm rounded-md">Cetak PDF</a>
            </form>

            @include('laporan._ringkasan')

            {{-- Saldo kas --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <div class="font-semibold text-gray-700 mb-2">Saldo Kas Harian</div>
                @if ($saldo)
                    <dl class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                        <div><dt class="text-gray-500">Saldo Awal</dt>
                            <dd class="font-semibold">Rp {{ number_format($saldo->saldo_awal, 0, ',', '.') }}</dd></div>
                        <div><dt class="text-gray-500">Saldo Akhir (hitung sistem)</dt>
                            <dd class="font-semibold">Rp {{ number_format($saldoSistem, 0, ',', '.') }}</dd></div>
                        <div><dt class="text-gray-500">Saldo Akhir (input manual)</dt>
                            <dd class="font-semibold">
                                {{ $saldo->saldo_akhir !== null ? 'Rp ' . number_format($saldo->saldo_akhir, 0, ',', '.') : '-' }}
                            </dd></div>
                    </dl>
                    @if ($saldo->saldo_akhir !== null)
                        @php $selisih = (float) $saldo->saldo_akhir - $saldoSistem; @endphp
                        <p class="mt-2 text-sm {{ abs($selisih) < 0.01 ? 'text-green-700' : 'text-red-600' }}">
                            Selisih: Rp {{ number_format($selisih, 0, ',', '.') }}
                            {{ abs($selisih) < 0.01 ? '(sesuai)' : '(perlu dicek)' }}
                        </p>
                    @endif
                @else
                    <p class="text-sm text-gray-500">Saldo awal untuk tanggal ini belum diisi.</p>
                @endif
            </div>

            {{-- Daftar transaksi --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <div class="px-4 py-3 font-semibold text-gray-700 border-b">Daftar Transaksi</div>
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-2 text-left">Kode</th>
                            <th class="px-4 py-2 text-left">Jenis</th>
                            <th class="px-4 py-2 text-right">Nominal</th>
                            <th class="px-4 py-2 text-right">Biaya Admin</th>
                            <th class="px-4 py-2 text-left">Operator</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($transaksi as $t)
                            <tr>
                                <td class="px-4 py-2 font-mono text-xs">{{ $t->kode_transaksi }}</td>
                                <td class="px-4 py-2">{{ $t->jenisTransaksi->nama }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($t->nominal, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($t->biaya_admin, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">{{ $t->user->name }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Tidak ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>