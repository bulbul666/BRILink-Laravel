<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Pengeluaran</h1>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6">

        @if (session('success'))
            <div class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-800 ring-1 ring-blue-100">{{ session('success') }}</div>
        @endif

        {{-- Form catat pengeluaran --}}
        <form method="POST" action="{{ route('pengeluaran.store') }}" class="rounded-2xl bg-white p-5 ring-1 ring-slate-200">
            @csrf
            <h2 class="text-sm font-semibold text-slate-700">Catat Pengeluaran</h2>

            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                    <input type="date" name="tanggal" max="{{ now()->toDateString() }}"
                           value="{{ old('tanggal', now()->toDateString()) }}"
                           class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                    @error('tanggal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Keterangan</label>
                    <input type="text" name="keterangan" maxlength="255" placeholder="Contoh: Listrik, kertas struk, pulsa"
                           value="{{ old('keterangan') }}"
                           class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                    @error('keterangan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Nominal (Rp)</label>
                    <input type="number" name="nominal" min="1" step="1" value="{{ old('nominal') }}"
                           class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                    @error('nominal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4">
                <button class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">Simpan</button>
            </div>
        </form>

        {{-- Filter bulan + total --}}
        <div class="flex flex-wrap items-end justify-between gap-4">
            <form method="GET" class="flex items-end gap-3">
                <div>
                    <label class="block text-xs text-slate-500">Bulan</label>
                    <input type="month" name="bulan" value="{{ $bulan }}"
                           class="mt-1 rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                </div>
                <button class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Tampilkan</button>
            </form>
            <div class="text-right">
                <div class="text-xs text-slate-500">Total pengeluaran bulan ini</div>
                <div class="text-xl font-semibold text-slate-900">Rp {{ number_format($total, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-hidden rounded-2xl bg-white ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium">Tanggal</th>
                            <th class="px-5 py-3 text-left font-medium">Keterangan</th>
                            <th class="px-5 py-3 text-right font-medium">Nominal</th>
                            <th class="px-5 py-3 text-left font-medium">Dicatat oleh</th>
                            <th class="px-5 py-3 text-center font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pengeluaran as $p)
                            <tr>
                                <td class="px-5 py-3">{{ $p->tanggal->format('d/m/Y') }}</td>
                                <td class="px-5 py-3">{{ $p->keterangan }}</td>
                                <td class="px-5 py-3 text-right">{{ number_format($p->nominal, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $p->user->name }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-center">
                                    @if (auth()->user()->isAdmin() || $p->user_id === auth()->id())
                                        <a href="{{ route('pengeluaran.edit', $p) }}" class="font-medium text-blue-700 hover:underline">Edit</a>
                                    @endif
                                    @if (auth()->user()->isAdmin())
                                        <form action="{{ route('pengeluaran.destroy', $p) }}" method="POST" class="ml-3 inline"
                                              onsubmit="return confirm('Hapus pengeluaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="font-medium text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Belum ada pengeluaran pada bulan ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $pengeluaran->links() }}
    </div>
</x-app-layout>