<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'warga';
    protected $primaryKey = 'warga_id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'no_ktp',
        'nama',
        'jenis_kelamin',
        'agama',
        'pekerjaan',
        'telp',
        'email'
    ];

    /**
     * Relationship dengan pendaftaran bantuan
     */
    public function pendaftaranBantuan()
    {
        return $this->hasMany(PendaftarBantuan::class, 'warga_id');
    }

    /**
     * Relationship dengan penerima bantuan
     */
    public function penerimaBantuan()
    {
        return $this->hasMany(PenerimaBantuan::class, 'warga_id');
    }

    public function getJenisKelaminSingkatAttribute()
{
    return match($this->jenis_kelamin) {
        'Laki-laki' => 'L',
        'Perempuan' => 'P',
        default => '-'
    };
}

    /**
     * Scope untuk filter berdasarkan nama
     */
    public function scopeCariNama($query, $nama)
    {
        return $query->where('nama', 'like', "%{$nama}%");
    }

    /**
     * Scope untuk filter berdasarkan no KTP
     */
    public function scopeCariKtp($query, $no_ktp)
    {
        return $query->where('no_ktp', 'like', "%{$no_ktp}%");
    }

    /**
     * Cek apakah warga sudah mendaftar di program tertentu
     */
    public function sudahDaftarProgram($programId)
    {
        return $this->pendaftaranBantuan()
                    ->where('program_id', $programId)
                    ->exists();
    }

    /**
     * Cek apakah warga adalah penerima di program tertentu
     */
    public function adalahPenerimaProgram($programId)
    {
        return $this->penerimaBantuan()
                    ->where('program_id', $programId)
                    ->exists();
    }
}
