@php
    $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
    $kartu = [
        ['Jumlah Transaksi', number_format($rekap['jumlah'], 0, ',', '.')],
        ['Total Nominal', $rp($rekap['total_nominal'])],
        ['Pendapatan (Biaya Admin)', $rp($rekap['pendapatan'])],
        ['Pengeluaran', $rp($rekap['pengeluaran'])],
        ['Laba Bersih', $rp($rekap['laba'])],
    ];
@endphp

<div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
    @foreach ($kartu as [$label, $nilai])
        <div class="bg-white shadow-sm sm:rounded-lg p-4">
            <div class="text-xs text-gray-500">{{ $label }}</div>
            <div class="text-lg font-semibold">{{ $nilai }}</div>
        </div>
    @endforeach
</div>

<div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
    <div class="px-4 py-3 font-semibold text-gray-700 border-b">Rekap per Jenis Transaksi</div>
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-2 text-left">Jenis</th>
                <th class="px-4 py-2 text-right">Jumlah</th>
                <th class="px-4 py-2 text-right">Total Nominal</th>
                <th class="px-4 py-2 text-right">Biaya Admin</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($rekap['per_jenis'] as $r)
                <tr>
                    <td class="px-4 py-2">{{ $r->nama }}</td>
                    <td class="px-4 py-2 text-right">{{ $r->jumlah }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($r->total_nominal, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($r->total_biaya, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada transaksi pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>