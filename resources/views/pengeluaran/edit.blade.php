<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Ubah Pengeluaran</h1>
    </x-slot>

    <div class="mx-auto max-w-2xl">
        <form method="POST" action="{{ route('pengeluaran.update', $pengeluaran) }}"
              class="rounded-2xl bg-white p-6 ring-1 ring-slate-200">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                    <input type="date" name="tanggal" max="{{ now()->toDateString() }}"
                           value="{{ old('tanggal', $pengeluaran->tanggal->format('Y-m-d')) }}"
                           class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                    @error('tanggal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Keterangan</label>
                    <input type="text" name="keterangan" maxlength="255"
                           value="{{ old('keterangan', $pengeluaran->keterangan) }}"
                           class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                    @error('keterangan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Nominal (Rp)</label>
                    <input type="number" name="nominal" min="1" step="1"
                           value="{{ old('nominal', (int) $pengeluaran->nominal) }}"
                           class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                    @error('nominal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <button class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">Simpan</button>
                <a href="{{ route('pengeluaran.index') }}"
                   class="rounded-lg border border-slate-200 px-5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>