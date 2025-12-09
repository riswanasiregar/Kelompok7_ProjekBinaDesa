<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
        'skor'
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
     * Scope untuk filter berdasarkan request dan kolom yang bisa difilter
     */
    public function scopeFilter(Builder $query, $request, array $filterableColumns): Builder
    {
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, 'like', '%' . trim($request->input($column)) . '%');
            }
        }
        return $query;
    }

    /**
     * Scope search global
     */
    public function scopeSearch(Builder $query, $request, array $columns)
    {
        if ($request->filled('search')) {
            $query->where(function($q) use ($request, $columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $request->search . '%');
                }
            });
        }
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

    /**
     * Scope untuk filter pendaftar
     */
    public function scopeByPendaftar($query, $pendaftarId)
    {
        return $query->where('pendaftar_id', $pendaftarId);
    }
    public function getMediaAttribute()
    {
        return $this->media()->orderBy('sort_order')->first();
    }
}
