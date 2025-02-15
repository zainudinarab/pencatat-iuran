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
        Schema::create('saldo_rts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained()->onDelete('cascade'); // RT yang memiliki saldo
            $table->decimal('total_saldo', 15, 2)->default(0); // Saldo saat ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_rts');
    }
};
