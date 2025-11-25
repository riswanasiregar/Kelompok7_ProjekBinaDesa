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
        'pendaftar_id',
        'petugas',
        'tanggal',
        'catatan',
        'skor',
        'status_verifikasi'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    /**
     * Relationship dengan pendaftar
     */
    public function pendaftar()
    {
        return $this->belongsTo(PendaftarBantuan::class, 'pendaftar_id');
    }

    /**
     * Relationship dengan media
     */
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id')->where('ref_table', 'verifikasi_lapangan');
    }

    /**
     * Accessor untuk status verifikasi lengkap
     */
    public function getStatusLabelAttribute()
    {
        $status = [
            'menunggu' => ['class' => 'bg-warning', 'label' => 'Menunggu'],
            'diverifikasi' => ['class' => 'bg-success', 'label' => 'Terverifikasi'],
            'ditolak' => ['class' => 'bg-danger', 'label' => 'Ditolak']
        ];

        return $status[$this->status_verifikasi] ?? ['class' => 'bg-secondary', 'label' => 'Tidak Diketahui'];
    }

    /**
     * Accessor untuk kategori skor
     */
    public function getKategoriSkorAttribute()
    {
        if ($this->skor >= 85) return 'Sangat Baik';
        if ($this->skor >= 70) return 'Baik';
        if ($this->skor >= 55) return 'Cukup';
        return 'Kurang';
    }

    /**
     * Scope untuk filter status verifikasi
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_verifikasi', $status);
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
