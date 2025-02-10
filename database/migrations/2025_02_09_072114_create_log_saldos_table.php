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
        Schema::create('log_saldos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User yang membuat transaksi
            $table->enum('jenis_transaksi', ['setoran', 'pengeluaran']); // Jenis transaksi

            $table->decimal('jumlah', 15, 2); // Jumlah yang ditransaksikan
            $table->decimal('saldo_terakhir', 15, 2); // Saldo setelah transaksi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_saldos');
    }
};
