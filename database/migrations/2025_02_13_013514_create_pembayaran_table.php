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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('house_id');
            $table->foreignId('rt_id')->constrained('rts')->onDelete('cascade');
            $table->decimal('total_amount', 12, 2);
            $table->enum('payment_method', ['manual', 'midtrans', 'xendit']);
            $table->enum('status', ['confirmed', 'failed'])->default('confirmed'); // Langsung confirmed jika bayar ke ketua gang
            $table->foreignId('collector_id')->nullable()->constrained('users')->onDelete('set null'); // Petugas penerima
            $table->foreignId('setoran_id')->nullable()->constrained('setoran_petugas')->onDelete('set null'); // ID setoran (null jika belum disetor)
            $table->enum('payment_source', ['resident', 'collector'])->default('resident'); // Sumber pembayaran
            $table->timestamps();

            $table->foreign('house_id')->references('id')->on('houses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
