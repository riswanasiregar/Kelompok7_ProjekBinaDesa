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
        Schema::create('verifikasi_lapangan', function (Blueprint $table) {
            $table->increments('verifikasi_id'); // Primary key, UNSIGNED INTEGER
            // Foreign key ke tabel pendaftar_bantuan.
            // Tipe data harus sama dengan primary key di tabel pendaftar_bantuan (yang dibuat dengan increments()).
            $table->unsignedInteger('pendaftar_bantuan_id');
            $table->foreign('pendaftar_bantuan_id')->references('pendaftar_bantuan_id')->on('pendaftar_bantuan')->onDelete('cascade');

 
            $table->string('petugas', 100);
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->integer('skor')->default(0);
            $table->enum('status_verifikasi', ['menunggu', 'diverifikasi', 'ditolak'])->default('menunggu');
            $table->timestamps();
 
            $table->index('tanggal');
            $table->index('status_verifikasi');
            $table->index('petugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_lapangan');
    }
};