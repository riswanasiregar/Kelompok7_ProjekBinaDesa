<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PendaftarBantuan extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_bantuan';
    protected $primaryKey = 'pendaftar_id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'program_id',
        'warga_id',
        'status_seleksi',
        'keterangan',
        'tanggal_daftar'
    ];

    protected $casts = [
        'tanggal_daftar' => 'date'
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
     * Relationship dengan verifikasi
     */
    public function verifikasi()
    {
        return $this->hasMany(VerifikasiLapangan::class, 'pendaftar_id');
    }

    /**
     * Relationship dengan media
     */
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id')
                    ->where('ref_table', 'pendaftar_bantuan');
    }

    /**
     * Accessor untuk status seleksi lengkap
     */
    public function getStatusLabelAttribute()
    {
        $status = [
            'pending' => ['class' => 'bg-warning', 'label' => 'Menunggu'],
            'diterima' => ['class' => 'bg-success', 'label' => 'Diterima'],
            'ditolak' => ['class' => 'bg-danger', 'label' => 'Ditolak']
        ];

        return $status[$this->status_seleksi] ?? ['class' => 'bg-secondary', 'label' => 'Tidak Diketahui'];
    }

    /**
     * Cek apakah pendaftar sudah diverifikasi
     */
    public function getSudahDiverifikasiAttribute()
    {
        return $this->verifikasi()->where('status_verifikasi', 'diverifikasi')->exists();
    }

    /**
     * Scope untuk filter status
     */
    public function scopeByStatus(Builder $query, $status)
    {
        return $query->where('status_seleksi', $status);
    }

    /**
     * Scope untuk filter program
     */
    public function scopeByProgram(Builder $query, $programId)
    {
        return $query->where('program_id', $programId);
    }

    /**
     * Scope untuk pendaftar yang belum diverifikasi
     */
    public function scopeBelumDiverifikasi(Builder $query)
    {
        return $query->whereDoesntHave('verifikasi', function ($q) {
            $q->where('status_verifikasi', 'diverifikasi');
        });
    }

    /**
     * Scope untuk pendaftar berdasarkan periode tanggal
     */
    public function scopePeriode(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_daftar', [$startDate, $endDate]);
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
     * Accessor untuk media utama
     */
    public function getMediaUtamaAttribute()
    {
        return $this->media()->orderBy('sort_order')->first();
    }

    /**
     * Hitung jumlah verifikasi lengkap
     */
    public function getJumlahVerifikasiAttribute()
    {
        return $this->verifikasi()->count();
    }

    /**
     * Hitung jumlah diverifikasi
     */
    public function getJumlahDiverifikasiAttribute()
    {
        return $this->verifikasi()->where('status_verifikasi', 'diverifikasi')->count();
    }
}
