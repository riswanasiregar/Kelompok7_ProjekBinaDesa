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
    Schema::create('warga', function (Blueprint $table) {
        $table->increments('warga_id'); // warga_id (PK)
        $table->string('no_ktp', 16)->unique(); // no_ktp (UNQ)
        $table->string('nama', 100);
        $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
        $table->string('agama', 30)->nullable();
        $table->string('pekerjaan', 50)->nullable();
        $table->string('telp', 20)->nullable();
        $table->string('email', 100)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga');
    }
};
