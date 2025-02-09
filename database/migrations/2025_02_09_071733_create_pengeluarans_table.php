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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bendahara_id')->constrained('users')->onDelete('cascade'); // Bendahara yang mengeluarkan uang
            $table->decimal('amount', 10, 2); // Jumlah pengeluaran
            $table->string('description'); // Deskripsi pengeluaran
            $table->date('tanggal_pengeluaran'); // Tanggal pengeluaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
