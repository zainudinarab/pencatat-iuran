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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->nullable()->constrained('rts')->onDelete('cascade');
            $table->string('user_name');
            $table->string('user_role')->nullable();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('action'); // created, updated, deleted
            $table->json('old_values')->nullable(); // nilai sebelum perubahan
            $table->json('new_values')->nullable(); // nilai setelah perubahan
            $table->json('changes')->nullable(); // daftar field yang berubah
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
