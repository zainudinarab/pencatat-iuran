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
        Schema::create('houses', function (Blueprint $table) {
            $table->string('id')->primary(); // ID gabungan dari blok dan nomer (contoh: A01, B10)
            $table->char('blok', 1); // Kolom blok (1 karakter)
            $table->unsignedInteger('nomer'); // Kolom nomer (angka)
            $table->foreignId('rt_id')->constrained('rts')->onDelete('cascade'); // Hubungan ke tabel rts
            $table->string('address'); // Alamat rumah
            $table->string('owner_name'); // Nama pemilik rumah
            $table->string('owner_phone'); // Nomor telepon pemilik rumah
            $table->string('owner_email'); // Email pemilik rumah
            $table->string('owner_address'); // Alamat pemilik rumah
            $table->string('owner_id_card'); // Nomor KTP pemilik rumah
            $table->string('owner_photo'); // Foto pemilik rumah
            $table->string('owner_ktp_photo'); // Foto KTP pemilik rumah
            $table->timestamps();
            // Unique constraint untuk memastikan blok dan nomer tidak duplikat
            $table->unique(['blok', 'nomer']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
