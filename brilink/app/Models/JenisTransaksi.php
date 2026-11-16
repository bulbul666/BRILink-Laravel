<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisTransaksi extends Model
{
    protected $table = 'jenis_transaksi';

    protected $fillable = [
        'kode',
        'nama',
        'efek_kas',
        'biaya_admin_default',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'biaya_admin_default' => 'decimal:2',
            'aktif' => 'boolean',
        ];
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
}
