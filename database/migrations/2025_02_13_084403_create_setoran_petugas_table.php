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
        Schema::create('setoran_petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collector_id')->constrained('users')->onDelete('cascade'); // Petugas penyetor
            $table->foreignId('rt_id')->constrained('rts')->onDelete('cascade'); // RT tujuan
            $table->decimal('total_amount', 12, 2); // Jumlah yang disetor
            $table->enum('status', ['pending', 'confirmed', 'ditolak'])->default('pending'); // Status setoran
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Bendahara yang menerima
            $table->timestamp('confirmed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_petugas');
    }
};
