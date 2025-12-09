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
        Schema::create('riwayat_penyaluran_bantuan', function (Blueprint $table) {
            $table->increments('penyaluran_id');
            $table->unsignedInteger('program_id');
            $table->unsignedInteger('penerima_id');
            $table->integer('tahap_ke');
            $table->date('tanggal');
            $table->decimal('nilai', 15, 2);
            $table->timestamps();

            // Foreign key constraints (biarkan otomatis)
            $table->foreign('program_id')
                  ->references('program_id')
                  ->on('program_bantuan')
                  ->onDelete('cascade');

            $table->foreign('penerima_id')
                  ->references('penerima_id')
                  ->on('penerima_bantuan')
                  ->onDelete('cascade');

            // HANYA UNIQUE CONSTRAINT yang beri nama manual
            $table->unique(['program_id', 'penerima_id', 'tahap_ke'], 'uniq_penyaluran_tahap');

            // Indexes biarkan otomatis (aman)
            $table->index(['program_id', 'penerima_id']);
            $table->index('tahap_ke');
            $table->index('tanggal');
            $table->index('nilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_penyaluran_bantuan');
    }
};
