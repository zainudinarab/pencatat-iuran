<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran_rts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained('rts')->onDelete('cascade');
            // User pencatat
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nama_pencatat'); // disimpan statis untuk arsip
            // Nomor nota unik
            $table->string('nomor_nota')->unique();
            // Status konfirmasi
            $table->enum('status_konfirmasi', ['pending', 'confirmed'])->default('pending');
            // User validator
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nama_validator')->nullable(); // nama disimpan statis
            $table->timestamp('confirmed_at')->nullable();
            // Data pengeluaran
            $table->decimal('total', 15, 2); // Total pengeluaran
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->string('bukti_gambar')->nullable();
            $table->timestamps();
        });

        Schema::create('pengeluaran_rt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_rt_id')->constrained('pengeluaran_rts')->onDelete('cascade');
            $table->string('nama_item');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('satuan')->nullable(); // contoh: pcs, kg, liter
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_rt_items');
        Schema::dropIfExists('pengeluaran_rts');
    }
};
