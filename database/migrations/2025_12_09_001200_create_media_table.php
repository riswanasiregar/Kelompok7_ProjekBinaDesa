<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('ref_table', 100);
                $table->unsignedBigInteger('ref_id')->nullable();
                $table->string('file_path');
                $table->string('file_name')->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->index(['ref_table', 'ref_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};

