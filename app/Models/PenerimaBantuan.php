<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaBantuan extends Model
{
    use HasFactory;

    protected $table = 'penerima_bantuan';
    protected $primaryKey = 'penerima_id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'program_id',
        'warga_id',
        'keterangan',
        'tanggal_ditetapkan',
        'status_penerima'
    ];

    protected $casts = [
        'tanggal_ditetapkan' => 'date'
    ];

    /**
     * Relationship dengan program bantuan
     */
    public function program()
    {
        return $this->belongsTo(ProgramBantuan::class, 'program_id');
    }

    /**
     * Relationship dengan warga
     */
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    /**
     * Relationship dengan riwayat penyaluran
     */
    public function penyaluran()
    {
        return $this->hasMany(RiwayatPenyaluranBantuan::class, 'penerima_id');
    }

    /**
     * Accessor untuk status penerima lengkap
     */
    public function getStatusLabelAttribute()
    {
        $status = [
            'aktif' => ['class' => 'bg-success', 'label' => 'Aktif'],
            'nonaktif' => ['class' => 'bg-secondary', 'label' => 'Nonaktif'],
            'dibatalkan' => ['class' => 'bg-danger', 'label' => 'Dibatalkan']
        ];

        return $status[$this->status_penerima] ?? ['class' => 'bg-secondary', 'label' => 'Tidak Diketahui'];
    }

    /**
     * Total nilai penyaluran yang sudah diterima
     */
    public function getTotalDiterimaAttribute()
    {
        return $this->penyaluran()
                    ->where('status_penyaluran', 'diberikan')
                    ->sum('nilai');
    }

    /**
     * Jumlah tahap penyaluran yang sudah diberikan
     */
    public function getJumlahTahapDiberikanAttribute()
    {
        return $this->penyaluran()
                    ->where('status_penyaluran', 'diberikan')
                    ->count();
    }

    /**
     * Scope untuk filter status penerima
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_penerima', $status);
    }

    /**
     * Scope untuk filter program
     */
    public function scopeByProgram($query, $programId)
    {
        return $query->where('program_id', $programId);
    }

    /**
     * Scope untuk penerima aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_penerima', 'aktif');
    }
}
