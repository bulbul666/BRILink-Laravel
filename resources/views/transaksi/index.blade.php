<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transaksi Harian</h2>
            <a href="{{ route('transaksi.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                + Tambah Transaksi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 rounded-md bg-green-100 text-green-800 text-sm">{{ session('success') }}</div>
            @endif

            {{-- Filter --}}
            <form method="GET" class="bg-white shadow-sm sm:rounded-lg p-4 grid grid-cols-1 md:grid-cols-5 gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode / keterangan"
                       class="border-gray-300 rounded-md text-sm">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                       class="border-gray-300 rounded-md text-sm">
                <select name="jenis" class="border-gray-300 rounded-md text-sm">
                    <option value="">Semua jenis</option>
                    @foreach ($jenisList as $j)
                        <option value="{{ $j->id }}" @selected(request('jenis') == $j->id)>{{ $j->nama }}</option>
                    @endforeach
                </select>
                <button class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md">Filter</button>
                <a href="{{ route('transaksi.index') }}"
                   class="px-4 py-2 border text-sm rounded-md text-center text-gray-700">Reset</a>
            </form>

            {{-- Ringkasan hasil filter --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <div class="text-xs text-gray-500">Jumlah Transaksi</div>
                    <div class="text-xl font-semibold">{{ number_format($ringkasan['jumlah'], 0, ',', '.') }}</div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <div class="text-xs text-gray-500">Total Nominal</div>
                    <div class="text-xl font-semibold">Rp {{ number_format($ringkasan['nominal'], 0, ',', '.') }}</div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <div class="text-xs text-gray-500">Total Biaya Admin</div>
                    <div class="text-xl font-semibold">Rp {{ number_format($ringkasan['biaya'], 0, ',', '.') }}</div>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Kode</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3 text-left">Pelanggan</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3 text-right">Biaya Admin</th>
                            <th class="px-4 py-3 text-left">Operator</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($transaksi as $t)
                            <tr>
                                <td class="px-4 py-2 font-mono text-xs">{{ $t->kode_transaksi }}</td>
                                <td class="px-4 py-2">{{ $t->tanggal->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $t->jenisTransaksi->nama }}</td>
                                <td class="px-4 py-2">{{ $t->pelanggan->nama ?? '-' }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($t->nominal, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($t->biaya_admin, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">{{ $t->user->name }}</td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">
                                    @if (auth()->user()->isAdmin() || $t->user_id === auth()->id())
                                        <a href="{{ route('transaksi.edit', $t) }}" class="text-blue-600 hover:underline">Edit</a>
                                    @endif
                                    @if (auth()->user()->isAdmin())
                                        <form action="{{ route('transaksi.destroy', $t) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus transaksi {{ $t->kode_transaksi }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline ml-2">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-gray-500">Belum ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $transaksi->links() }}
        </div>
    </div>
</x-app-layout>