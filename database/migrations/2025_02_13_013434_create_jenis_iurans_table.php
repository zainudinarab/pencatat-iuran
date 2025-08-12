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
        Schema::create('jenis_iurans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();           // Nama iuran (contoh: "Iuran Keamanan")
            $table->decimal('amount', 12, 2)->default(0); // Jumlah default iuran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_iurans');
    }
};
