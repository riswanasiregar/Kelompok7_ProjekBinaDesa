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
            // Primary key - konsisten dengan tabel lain
            $table->increments('penyaluran_id');

            // Foreign keys - menggunakan unsignedInteger untuk konsistensi
            $table->unsignedInteger('program_id');
            $table->unsignedInteger('penerima_id');

            // Data penyaluran
            $table->integer('tahap_ke');
            $table->date('tanggal');
            $table->decimal('nilai', 15, 2);
            $table->text('keterangan')->nullable();
            $table->enum('status_penyaluran', ['direncanakan', 'diberikan', 'dibatalkan'])->default('direncanakan');
            $table->string('metode_penyaluran', 50)->nullable();
            $table->string('bukti_penyaluran', 255)->nullable();
            $table->timestamps();

            // Foreign key constraints - konsisten dengan tabel referensi
            $table->foreign('program_id')
                  ->references('program_id')
                  ->on('program_bantuan')
                  ->onDelete('cascade');

            $table->foreign('penerima_id')
                  ->references('penerima_id')
                  ->on('penerima_bantuan')
                  ->onDelete('cascade');

            // Unique constraint dengan nama pendek
            $table->unique(['program_id', 'penerima_id', 'tahap_ke'], 'rp_unique_tahap');

            // Indexes dengan nama pendek
            $table->index(['program_id', 'penerima_id'], 'rp_prog_penerima');
            $table->index('tanggal', 'rp_tanggal_idx');
            $table->index('status_penyaluran', 'rp_status_idx');
            $table->index('tahap_ke', 'rp_tahap_idx');
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
