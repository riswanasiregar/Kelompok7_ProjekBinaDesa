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
        Schema::create('penerima_bantuan', function (Blueprint $table) {
            $table->increments('penerima_id');
            $table->unsignedInteger('program_id');
            $table->unsignedInteger('warga_id');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('program_id')
                  ->references('program_id')
                  ->on('program_bantuan')
                  ->onDelete('cascade');

            $table->foreign('warga_id')
                  ->references('warga_id')
                  ->on('warga')
                  ->onDelete('cascade');

            // Constraints
            $table->unique(['program_id', 'warga_id']);

            // Indexes
            $table->index(['program_id', 'warga_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerima_bantuan');
    }
};
