<?php

namespace Database\Seeders;

use App\Models\JenisTransaksi;
use Illuminate\Database\Seeder;

class JenisTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        // Nilai biaya admin hanya contoh; sesuaikan dengan tarif agen yang sebenarnya.
        $data = [
            ['kode' => 'TRF',  'nama' => 'Transfer Antar Rekening',  'efek_kas' => 'masuk',  'biaya_admin_default' => 5000],
            ['kode' => 'TRK',  'nama' => 'Tarik Tunai',              'efek_kas' => 'keluar', 'biaya_admin_default' => 5000],
            ['kode' => 'STR',  'nama' => 'Setor Tunai',              'efek_kas' => 'masuk',  'biaya_admin_default' => 5000],
            ['kode' => 'LSTK', 'nama' => 'Pembayaran Listrik',       'efek_kas' => 'masuk',  'biaya_admin_default' => 3000],
            ['kode' => 'PDAM', 'nama' => 'Pembayaran PDAM',          'efek_kas' => 'masuk',  'biaya_admin_default' => 3000],
            ['kode' => 'BPJS', 'nama' => 'Pembayaran BPJS',          'efek_kas' => 'masuk',  'biaya_admin_default' => 3000],
            ['kode' => 'PLSD', 'nama' => 'Pulsa / Paket Data',       'efek_kas' => 'masuk',  'biaya_admin_default' => 2000],
            ['kode' => 'CICL', 'nama' => 'Pembayaran Cicilan',       'efek_kas' => 'masuk',  'biaya_admin_default' => 5000],
        ];

        foreach ($data as $row) {
            JenisTransaksi::updateOrCreate(['kode' => $row['kode']], $row);
        }
    }
}
