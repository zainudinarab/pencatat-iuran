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
        Schema::create('riwayat_transfer_pos_rts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained()->onDelete('cascade');
            $table->enum('pengirim_pos', ['bendahara_rt', 'ketua_rt']);
            $table->enum('penerima_pos', ['bendahara_rt', 'ketua_rt']);
            $table->decimal('jumlah', 15, 2);
            $table->string('nama_pengirim');
            $table->string('nama_penerima');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_transfer_pos_rts');
    }
};
