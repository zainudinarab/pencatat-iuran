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
        Schema::create('iuran_wajibs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained()->onDelete('cascade'); // RT terkait
            $table->foreignId('jenis_iuran_id')->constrained()->onDelete('cascade');
            $table->integer('bill_month'); // Format YYYYMM (contoh: 202502 untuk Feb 2025)
            $table->decimal('amount', 12, 2);
            $table->timestamps();
            // Tambahkan unique constraint untuk menghindari iuran dobel
            $table->unique(['rt_id', 'jenis_iuran_id', 'bill_month']);
            // Tambahkan index
            $table->index('rt_id');
            $table->index('bill_month');
            $table->index(['rt_id', 'bill_month']); // Composite index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_wajibs');
    }
};
