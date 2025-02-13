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
        Schema::create('pengeluaran_rt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained()->onDelete('cascade'); // RT yang mengeluarkan uang
            $table->decimal('amount', 15, 2); // Jumlah uang yang dikeluarkan
            $table->string('description'); // Keterangan penggunaan dana
            $table->foreignId('approved_by')->constrained('users')->onDelete('cascade'); // Bendahara yang menyetujui
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_rt');
    }
};
