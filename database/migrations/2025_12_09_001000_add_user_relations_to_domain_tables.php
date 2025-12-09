<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Program Bantuan
        Schema::table('program_bantuans', function (Blueprint $table) {
            if (!Schema::hasColumn('program_bantuans', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('program_id')
                    ->constrained()
                    ->onDelete('cascade');
            }
        });

        // Warga
        Schema::table('warga', function (Blueprint $table) {
            if (!Schema::hasColumn('warga', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('warga_id')
                    ->constrained()
                    ->onDelete('cascade');
            }
        });

        // Pendaftar bantuan
        Schema::table('pendaftar_bantuan', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftar_bantuan', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('program_id')
                    ->constrained()
                    ->onDelete('cascade');
            }
        });

        // Customers
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->onDelete('cascade');
            }
        });

        // Multi uploads
        Schema::table('multiuploads', function (Blueprint $table) {
            if (!Schema::hasColumn('multiuploads', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->onDelete('cascade');
            }
        });

        // Verifikasi Lapangan
        Schema::table('verifikasi_lapangan', function (Blueprint $table) {
            if (!Schema::hasColumn('verifikasi_lapangan', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('verifikasi_id')
                    ->constrained()
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        $tables = [
            'program_bantuans',
            'warga',
            'pendaftar_bantuan',
            'customers',
            'multiuploads',
            'verifikasi_lapangan',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'user_id')) {
                    $table->dropConstrainedForeignId('user_id');
                }
            });
        }
    }
};

