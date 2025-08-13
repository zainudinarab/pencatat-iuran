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
        Schema::create('transaksi_rts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained();
            $table->foreignId('setoran_id')->nullable()->constrained('setoran_petugas');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan');
            $table->string('dokumen_bukti')->nullable();
            $table->foreignId('pembuat_id')->constrained('users');
            $table->foreignId('penyetuju_id')->nullable()->constrained('users');
            $table->enum('status', ['draft', 'disetujui', 'ditolak'])->default('draft');
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_rts');
    }
};
