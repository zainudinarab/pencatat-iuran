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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained()->onDelete('cascade'); // Rumah yang bayar
            $table->decimal('total_amount', 12, 2);
            $table->enum('payment_method', ['manual', 'midtrans', 'xendit']);
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('pending'); // Status pembayaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
