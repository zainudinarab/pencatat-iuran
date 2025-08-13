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
             $table->foreignId('rt_id')->nullable()->constrained('rts')->onDelete('cascade');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->string('sumber')->nullable(); // setoran_petugas / pemasukan_lain / pengeluaran_rt
             $table->unsignedBigInteger('ref_id')->nullable(); // ID di tabel sumber
            $table->string('ref_tabel')->nullable(); // nama tabel sumber
            $table->decimal('nominal', 15, 2);
            $table->decimal('saldo_setelah', 15, 2)->nullable();
            $table->text('keterangan');
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
