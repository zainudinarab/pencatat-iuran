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
        Schema::create('saldo_pos_rts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pemegang pos
            $table->enum('pos', ['bendahara_rt', 'ketua_rt']);
            $table->decimal('saldo', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['rt_id', 'pos']); // 1 pos hanya satu record per RT
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_pos_rts');
    }
};
