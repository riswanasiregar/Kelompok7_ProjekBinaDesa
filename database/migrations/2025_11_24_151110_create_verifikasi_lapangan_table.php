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
            $table->increments('verifikasi_id'); // INTEGER AUTO_INCREMENT

            // Foreign key - menggunakan unsignedInteger untuk konsistensi
            $table->unsignedInteger('pendaftar_id');

            $table->string('petugas', 100);
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->integer('skor')->default(0);
            $table->enum('status_verifikasi', ['menunggu', 'diverifikasi', 'ditolak'])->default('menunggu');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('pendaftar_id')
                  ->references('pendaftar_id')
                  ->on('pendaftar_bantuan')
                  ->onDelete('cascade');

            // Indexes
            $table->index('pendaftar_id');
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
