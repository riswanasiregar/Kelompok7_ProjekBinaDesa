<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftarBantuan extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_bantuan';
    protected $primaryKey = 'pendaftar_bantuan_id';
    public $timestamps = true;

    protected $fillable = [
        'warga_id',
        'program_id',
        'tanggal_daftar',
        'keterangan',
        'user_id',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id', 'warga_id');
    }

    public function program()
    {
        return $this->belongsTo(ProgramBantuan::class, 'program_id', 'program_id');
    }

    // Hubungan dengan media (file)
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id')->where('ref_table', 'pendaftar_bantuan');
    }

    // Fungsi untuk mendapatkan file yang diupload
    public function getFile()
    {
        $media = $this->media()->first();
        return $media ? $media->getUrlFile() : null;
    }

    // Fungsi untuk cek apakah ada file
    public function hasFile()
    {
        return $this->media()->count() > 0;
    }
}







