<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Bulanan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <form method="GET" class="bg-white shadow-sm sm:rounded-lg p-4 flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs text-gray-500">Bulan</label>
                    <input type="month" name="bulan" value="{{ $bulan->format('Y-m') }}"
                           class="border-gray-300 rounded-md text-sm">
                </div>
                <button class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md">Tampilkan</button>
                <a href="{{ route('laporan.bulanan.pdf', ['bulan' => $bulan->format('Y-m')]) }}"
                   class="px-4 py-2 bg-red-600 text-white text-sm rounded-md">Cetak PDF</a>
            </form>

            @include('laporan._ringkasan')

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <div class="px-4 py-3 font-semibold text-gray-700 border-b">Rekap per Hari</div>
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-right">Jumlah</th>
                            <th class="px-4 py-2 text-right">Total Nominal</th>
                            <th class="px-4 py-2 text-right">Pendapatan</th>
                            <th class="px-4 py-2 text-right">Pengeluaran</th>
                            <th class="px-4 py-2 text-right">Laba</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($perHari as $h)
                            <tr class="{{ $h['jumlah'] === 0 && $h['pengeluaran'] == 0 ? 'text-gray-400' : '' }}">
                                <td class="px-4 py-2">{{ $h['tanggal']->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 text-right">{{ $h['jumlah'] }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($h['total_nominal'], 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($h['pendapatan'], 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($h['pengeluaran'], 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($h['laba'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>