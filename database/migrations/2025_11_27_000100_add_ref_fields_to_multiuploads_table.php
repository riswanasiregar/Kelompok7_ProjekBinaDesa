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
        Schema::table('multiuploads', function (Blueprint $table) {
            if (!Schema::hasColumn('multiuploads', 'ref_table')) {
                $table->string('ref_table', 100)->nullable()->after('filename');
            }

            if (!Schema::hasColumn('multiuploads', 'ref_id')) {
                $table->unsignedBigInteger('ref_id')->nullable()->after('ref_table');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('multiuploads', function (Blueprint $table) {
            if (Schema::hasColumn('multiuploads', 'ref_id')) {
                $table->dropColumn('ref_id');
            }

            if (Schema::hasColumn('multiuploads', 'ref_table')) {
                $table->dropColumn('ref_table');
            }
        });
    }
};

