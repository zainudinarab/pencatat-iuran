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
        Schema::create('saldo_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained()->onDelete('cascade'); // RT terkait
            $table->decimal('amount', 15, 2); // Nominal perubahan saldo
            $table->enum('type', ['income', 'expense'])->default('income'); // income = pemasukan, expense = pengeluaran
            $table->string('description'); // Keterangan transaksi
            $table->foreignId('reference_id')->nullable(); // ID referensi (misal pembayaran atau pengeluaran)
            $table->string('reference_type')->nullable(); // Jenis referensi (pembayaran, setoran, pengeluaran)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_log');
    }
};
