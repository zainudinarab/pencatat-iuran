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
        Schema::create('konfirmasi_setoran_petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setoran_id')->constrained('setoran_petugas')->onDelete('cascade'); // Relasi ke tabel setoran_petugas
            $table->foreignId('bendahara_id')->constrained('users')->onDelete('cascade'); // Bendahara yang mengkonfirmasi
            $table->enum('status', ['pending', 'confirmed', 'ditolak'])->default('pending'); // Status konfirmasi
            $table->text('catatan')->nullable(); // Catatan dari bendahara
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfirmasi_setoran_petugas');
    }
};
