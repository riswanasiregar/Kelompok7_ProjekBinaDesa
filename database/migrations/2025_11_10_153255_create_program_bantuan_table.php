<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('program_bantuans', function (Blueprint $table) {
            $table->id('program_id');
            $table->string('kode')->unique();
            $table->string('nama_program');
            $table->year('tahun');
            $table->text('deskripsi')->nullable();
            $table->decimal('anggaran', 15, 2);
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_bantuans');
    }
};
