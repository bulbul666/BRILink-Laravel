<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $judul }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 15px; margin: 0 0 4px; }
        h2 { font-size: 12px; margin: 16px 0 6px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 6px; }
        th { background: #eee; text-align: left; }
        .r { text-align: right; }
        .muted { color: #666; font-size: 10px; }
    </style>
</head>
<body>
    <h1>{{ $judul }}</h1>
    <div class="muted">Dicetak: {{ now()->format('d/m/Y H:i') }} oleh {{ auth()->user()->name }}</div>

    <h2>Ringkasan</h2>
    <table>
        <tr><th>Jumlah Transaksi</th><td class="r">{{ number_format($rekap['jumlah'], 0, ',', '.') }}</td></tr>
        <tr><th>Total Nominal</th><td class="r">Rp {{ number_format($rekap['total_nominal'], 0, ',', '.') }}</td></tr>
        <tr><th>Pendapatan (Biaya Admin)</th><td class="r">Rp {{ number_format($rekap['pendapatan'], 0, ',', '.') }}</td></tr>
        <tr><th>Pengeluaran</th><td class="r">Rp {{ number_format($rekap['pengeluaran'], 0, ',', '.') }}</td></tr>
        <tr><th>Laba Bersih</th><td class="r"><strong>Rp {{ number_format($rekap['laba'], 0, ',', '.') }}</strong></td></tr>
    </table>

    <h2>Rekap per Jenis Transaksi</h2>
    <table>
        <thead>
            <tr><th>Jenis</th><th class="r">Jumlah</th><th class="r">Total Nominal</th><th class="r">Biaya Admin</th></tr>
        </thead>
        <tbody>
            @forelse ($rekap['per_jenis'] as $r)
                <tr>
                    <td>{{ $r->nama }}</td>
                    <td class="r">{{ $r->jumlah }}</td>
                    <td class="r">{{ number_format($r->total_nominal, 0, ',', '.') }}</td>
                    <td class="r">{{ number_format($r->total_biaya, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Tidak ada transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>

    @isset($transaksi)
        <h2>Daftar Transaksi</h2>
        <table>
            <thead>
                <tr><th>Kode</th><th>Jenis</th><th class="r">Nominal</th><th class="r">Biaya Admin</th></tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $t)
                    <tr>
                        <td>{{ $t->kode_transaksi }}</td>
                        <td>{{ $t->jenisTransaksi->nama }}</td>
                        <td class="r">{{ number_format($t->nominal, 0, ',', '.') }}</td>
                        <td class="r">{{ number_format($t->biaya_admin, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($saldo)
            <h2>Saldo Kas</h2>
            <table>
                <tr><th>Saldo Awal</th><td class="r">Rp {{ number_format($saldo->saldo_awal, 0, ',', '.') }}</td></tr>
                <tr><th>Saldo Akhir (hitung sistem)</th><td class="r">Rp {{ number_format($saldoSistem, 0, ',', '.') }}</td></tr>
            </table>
        @endif
    @endisset

    @isset($perHari)
        <h2>Rekap per Hari</h2>
        <table>
            <thead>
                <tr><th>Tanggal</th><th class="r">Jumlah</th><th class="r">Nominal</th><th class="r">Pendapatan</th><th class="r">Pengeluaran</th><th class="r">Laba</th></tr>
            </thead>
            <tbody>
                @foreach ($perHari as $h)
                    <tr>
                        <td>{{ $h['tanggal']->format('d/m/Y') }}</td>
                        <td class="r">{{ $h['jumlah'] }}</td>
                        <td class="r">{{ number_format($h['total_nominal'], 0, ',', '.') }}</td>
                        <td class="r">{{ number_format($h['pendapatan'], 0, ',', '.') }}</td>
                        <td class="r">{{ number_format($h['pengeluaran'], 0, ',', '.') }}</td>
                        <td class="r">{{ number_format($h['laba'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endisset
</body>
</html>