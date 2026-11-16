<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'user_id',
        'jenis_transaksi_id',
        'pelanggan_id',
        'nominal',
        'biaya_admin',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'nominal' => 'decimal:2',
            'biaya_admin' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jenisTransaksi(): BelongsTo
    {
        return $this->belongsTo(JenisTransaksi::class);
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function scopeTanggal(Builder $query, string $tanggal): Builder
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    public function scopeBulan(Builder $query, int $bulan, int $tahun): Builder
    {
        return $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
    }

    /** Contoh format kode: TRX-20261025-0001 */
    public static function buatKode(string $tanggal): string
    {
        $prefix = 'TRX-' . date('Ymd', strtotime($tanggal)) . '-';
        $urutan = static::where('kode_transaksi', 'like', $prefix . '%')->count() + 1;

        return $prefix . str_pad((string) $urutan, 4, '0', STR_PAD_LEFT);
    }
}
