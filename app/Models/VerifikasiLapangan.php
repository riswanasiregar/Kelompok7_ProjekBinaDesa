<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiLapangan extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_lapangan';
    protected $primaryKey = 'verifikasi_id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'pendaftar_bantuan_id',
        'petugas',
        'tanggal',
        'catatan',
        'skor',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    /**
     * Hubungan dengan pendaftar bantuan
     */
    public function pendaftar()
    {
        return $this->belongsTo(PendaftarBantuan::class, 'pendaftar_bantuan_id', 'pendaftar_bantuan_id');
    }

    /**
     * Hubungan dengan media (foto)
     */
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id')->where('ref_table', 'verifikasi_lapangan');
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

    /**
     * Fungsi untuk menentukan kategori skor
     */
    public function getKategoriSkorAttribute()
    {
        if ($this->skor >= 85) return 'Sangat Baik';
        if ($this->skor >= 70) return 'Baik';
        if ($this->skor >= 55) return 'Cukup';
        return 'Kurang';
    }

    /**
     * Scope untuk filter petugas
     */
    public function scopeByPetugas($query, $petugas)
    {
        return $query->where('petugas', 'like', "%{$petugas}%");
    }

    /**
     * Scope untuk verifikasi berdasarkan tanggal
     */
    public function scopePeriode($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Scope untuk skor minimal
     */
    public function scopeSkorMin($query, $minSkor)
    {
        return $query->where('skor', '>=', $minSkor);
    }
}