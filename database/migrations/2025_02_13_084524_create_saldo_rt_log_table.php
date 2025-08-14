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
        Schema::create('saldo_rt_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2); // Nilai perubahan (positif atau negatif)
            $table->decimal('saldo_sebelum', 15, 2);
            $table->decimal('saldo_sesudah', 15, 2);
            $table->enum('type', ['pemasukan', 'pengeluaran']);
            $table->string('description');
            $table->foreignId('reference_id')->nullable(); // ID referensi sumber
            $table->string('reference_type')->nullable(); // Tabel referensi sumber
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_rt_logs');
    }
};
