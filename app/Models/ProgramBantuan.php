<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramBantuan extends Model
{
    use HasFactory;

    protected $primaryKey = 'program_id';

    protected $fillable = [
        'kode',
        'nama_program',
        'tahun',
        'deskripsi',
        'anggaran',
        'user_id',
    ];

    // Hubungan dengan media (foto)
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id')->where('ref_table', 'program_bantuan');
    }

    // Fungsi untuk mendapatkan foto yang diupload
    public function getFoto()
    {
        $foto = $this->media()->first();
        return $foto ? $foto->getUrlFile() : null;
    }

    // Fungsi untuk cek apakah ada foto
    public function adaFoto()
    {
        return $this->media()->count() > 0;
    }

    // Fungsi untuk format anggaran dengan rupiah
    public function getAnggaranFormatAttribute()
    {
        return 'Rp ' . number_format($this->anggaran, 0, ',', '.');
    }
}

