<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengeluaran_rts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi_rts'); // Relasi ke transaksi utama
            $table->decimal('total', 15, 2); // Total pengeluaran (harus sama dengan transaksi_rts.jumlah)
            $table->string('nomor_nota')->nullable()->unique(); // Format: NOTA/RT-XX/YYYYMMDD/001
            $table->date('tanggal');
            $table->string('penerima'); // Nama penerima/pihak terkait
            $table->text('deskripsi'); // Contoh: "Pembelian alat kebersihan"
            $table->json('rincian')->nullable(); // Format: [{"nama":"Sapu","harga":50000,"qty":2}]
            $table->string('dokumen')->nullable(); // Path file bukti
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_rts');
    }
};
