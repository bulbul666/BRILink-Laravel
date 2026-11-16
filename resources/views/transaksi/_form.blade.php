@php $t = $transaksi ?? null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
        <input type="date" name="tanggal" max="{{ now()->toDateString() }}"
               value="{{ old('tanggal', $t?->tanggal?->format('Y-m-d') ?? now()->toDateString()) }}"
               class="mt-1 w-full border-gray-300 rounded-md text-sm">
        @error('tanggal') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
        <select name="jenis_transaksi_id" id="jenis_transaksi_id" class="mt-1 w-full border-gray-300 rounded-md text-sm">
            <option value="">-- Pilih jenis --</option>
            @foreach ($jenisList as $j)
                <option value="{{ $j->id }}" data-biaya="{{ (int) $j->biaya_admin_default }}"
                    @selected(old('jenis_transaksi_id', $t?->jenis_transaksi_id) == $j->id)>
                    {{ $j->nama }}
                </option>
            @endforeach
        </select>
        @error('jenis_transaksi_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Nominal (Rp)</label>
        <input type="number" name="nominal" min="1" step="1"
               value="{{ old('nominal', $t ? (int) $t->nominal : '') }}"
               class="mt-1 w-full border-gray-300 rounded-md text-sm">
        @error('nominal') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Biaya Admin (Rp)</label>
        <input type="number" name="biaya_admin" id="biaya_admin" min="0" step="1"
               value="{{ old('biaya_admin', $t ? (int) $t->biaya_admin : '') }}"
               class="mt-1 w-full border-gray-300 rounded-md text-sm">
        @error('biaya_admin') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Pelanggan (opsional)</label>
        <select name="pelanggan_id" class="mt-1 w-full border-gray-300 rounded-md text-sm">
            <option value="">-- Tanpa pelanggan --</option>
            @foreach ($pelangganList as $p)
                <option value="{{ $p->id }}" @selected(old('pelanggan_id', $t?->pelanggan_id) == $p->id)>
                    {{ $p->nama }}
                </option>
            @endforeach
        </select>
        @error('pelanggan_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
        <input type="text" name="keterangan" maxlength="255"
               value="{{ old('keterangan', $t?->keterangan) }}"
               class="mt-1 w-full border-gray-300 rounded-md text-sm">
        @error('keterangan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex gap-2">
    <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Simpan</button>
    <a href="{{ route('transaksi.index') }}" class="px-4 py-2 border text-sm rounded-md text-gray-700">Batal</a>
</div>

<script>
    // Isi otomatis biaya admin saat jenis transaksi dipilih
    document.getElementById('jenis_transaksi_id').addEventListener('change', function () {
        const biaya = this.options[this.selectedIndex].dataset.biaya;
        if (biaya !== undefined) {
            document.getElementById('biaya_admin').value = biaya;
        }
    });
</script>