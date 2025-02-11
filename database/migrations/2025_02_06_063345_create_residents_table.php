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
        // Schema::create('residents', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('phone_number');
        //     $table->string('slug')->unique();
        //     $table->string('blok');
        //     $table->string('nomor_rumah');
        //     $table->string('RT');
        //     $table->string('RW');
        //     $table->text('address');
        //     $table->timestamps();
        // });
        Schema::create('residents', function (Blueprint $table) {
            $table->string('id', 3)->primary(); // Menggunakan string dengan panjang 3 karakter sebagai primary key
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('phone_number');
            $table->string('slug')->unique();
            $table->string('blok');
            $table->string('nomor_rumah');
            $table->string('RT');
            $table->string('RW');
            $table->text('address');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
