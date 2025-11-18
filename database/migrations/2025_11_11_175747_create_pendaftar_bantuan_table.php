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
        if (Schema::hasTable('pendaftar_bantuan')) {
            return;
        }

        Schema::create('pendaftar_bantuan', function (Blueprint $table) {
            $table->increments('pendaftar_bantuan_id'); // PK
            $table->unsignedInteger('warga_id'); // FK 
            $table->unsignedInteger('program_id'); // FK 
            $table->date('tanggal_daftar');
            $table->enum('status', ['Diproses', 'Diterima', 'Ditolak'])->default('Diproses');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('warga_id')
                  ->references('warga_id')->on('warga')
                  ->onDelete('cascade');

            $table->foreign('program_id')
                  ->references('program_id')->on('program_bantuans')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftar_bantuan');
    }
};