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
        Schema::create('media', function (Blueprint $table) {
            $table->increments('media_id'); // INTEGER AUTO_INCREMENT (konsisten dengan lainnya)
            $table->string('ref_table', 50); // program_bantuan, pendaftar_bantuan, dll
            $table->unsignedInteger('ref_id'); // INTEGER (konsisten dengan primary key lainnya)
            $table->string('file_url');
            $table->string('caption')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Index untuk performa query
            $table->index(['ref_table', 'ref_id']);
            $table->index('ref_table');
            $table->index('ref_id');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
