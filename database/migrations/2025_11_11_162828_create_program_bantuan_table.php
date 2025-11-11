<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{

    Schema::create('program_bantuan', function (Blueprint $table) {
        $table->increments('program_id'); // program_id (PK)
        $table->string('kode', 20)->unique(); // kode (UNQ)
        $table->string('nama_program', 255)->nullable();
        $table->year('tahun')->nullable();
        $table->text('deskripsi')->nullable();
        $table->decimal('anggaran', 15, 2)->nullable();
        $table->string('media', 255)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_bantuan');
    }
};
