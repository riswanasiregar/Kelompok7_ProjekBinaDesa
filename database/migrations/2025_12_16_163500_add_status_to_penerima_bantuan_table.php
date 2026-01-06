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
        Schema::table('penerima_bantuan', function (Blueprint $table) {
            if (!Schema::hasColumn('penerima_bantuan', 'status')) {
                $table->enum('status', ['Sudah Menerima', 'Belum Menerima'])->default('Belum Menerima')->after('keterangan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerima_bantuan', function (Blueprint $table) {
            if (Schema::hasColumn('penerima_bantuan', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};