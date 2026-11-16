<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saldo extends Model
{
    protected $table = 'saldo';

    protected $fillable = [
        'tanggal',
        'user_id',
        'saldo_awal',
        'saldo_akhir',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'saldo_awal' => 'decimal:2',
            'saldo_akhir' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
