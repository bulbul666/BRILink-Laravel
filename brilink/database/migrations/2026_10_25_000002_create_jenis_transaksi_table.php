<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            // efek terhadap uang tunai di laci agen: masuk (setor tunai) / keluar (tarik tunai, transfer, pembayaran)
            $table->enum('efek_kas', ['masuk', 'keluar'])->default('keluar');
            $table->decimal('biaya_admin_default', 12, 2)->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_transaksi');
    }
};
