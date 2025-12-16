<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verifikasi_lapangan', function (Blueprint $table) {
            if (!Schema::hasColumn('verifikasi_lapangan', 'pendaftar_bantuan_id')) {
                $table->unsignedInteger('pendaftar_bantuan_id')->after('verifikasi_id');
                $table->foreign('pendaftar_bantuan_id')
                    ->references('pendaftar_bantuan_id')
                    ->on('pendaftar_bantuan')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('verifikasi_lapangan', function (Blueprint $table) {
            if (Schema::hasColumn('verifikasi_lapangan', 'pendaftar_bantuan_id')) {
                $table->dropForeign(['pendaftar_bantuan_id']);
                $table->dropColumn('pendaftar_bantuan_id');
            }
        });
    }
};



