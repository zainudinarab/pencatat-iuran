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
        Schema::create('detail_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->onDelete('cascade'); // Invoice terkait
            $table->foreignId('house_id')->constrained()->onDelete('cascade'); // Rumah yang membayar
            $table->foreignId('iuran_wajib_id')->constrained('iuran_wajibs')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'confirmed'])->default('pending'); // Status pembayaran
            $table->timestamps();

            // Tambahkan index
            $table->index('iuran_wajib_id');
            $table->index('warga_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembayarans');
    }
};
