<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
        'keterangan'
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
     * Scope untuk filter program
     */
    public function scopeByProgram($query, $programId)
    {
        return $query->where('program_id', $programId);
    }

    /**
     * Scope untuk filter berdasarkan nama warga (through relationship)
     */
    public function scopeByNamaWarga($query, $namaWarga)
    {
        return $query->whereHas('warga', function($q) use ($namaWarga) {
            $q->where('nama', 'like', "%{$namaWarga}%");
        });
    }

    /**
     * Scope untuk penerima yang sudah menerima penyaluran
     */
    public function scopeSudahMenerima($query)
    {
        return $query->whereHas('penyaluran');
    }

    /**
     * Scope untuk penerima yang belum menerima penyaluran
     */
    public function scopeBelumMenerima($query)
    {
        return $query->whereDoesntHave('penyaluran');
    }

    /**
     * Scope untuk penerima dengan penyaluran terbanyak
     */
    public function scopeDenganTotalPenyaluran($query)
    {
        return $query->withCount(['penyaluran as total_nilai' => function($q) {
            $q->select(DB::raw('COALESCE(SUM(nilai), 0)'));
        }])->orderBy('total_nilai', 'desc');
    }

    /**
     * Total nilai penyaluran yang sudah diterima
     */
    public function getTotalDiterimaAttribute()
    {
        return $this->penyaluran()->sum('nilai');
    }

    /**
     * Jumlah tahap penyaluran yang sudah diberikan
     */
    public function getJumlahTahapDiberikanAttribute()
    {
        return $this->penyaluran()->count();
    }

    /**
     * Status penerima berdasarkan penyaluran
     */
    public function getStatusPenerimaAttribute()
    {
        if ($this->penyaluran()->count() > 0) {
            return 'Sudah Menerima';
        }
        return 'Belum Menerima';
    }

    /**
     * Accessor untuk label status penerima
     */
    public function getStatusLabelAttribute()
    {
        $status = $this->status_penerima;
        $labels = [
            'Sudah Menerima' => ['class' => 'bg-success', 'label' => 'Sudah Menerima'],
            'Belum Menerima' => ['class' => 'bg-warning', 'label' => 'Belum Menerima']
        ];

        return $labels[$status] ?? ['class' => 'bg-secondary', 'label' => 'Tidak Diketahui'];
    }

    /**
     * Cek apakah penerima sudah menerima bantuan
     */
    public function getSudahMenerimaAttribute()
    {
        return $this->penyaluran()->exists();
    }

    /**
     * Rata-rata nilai penyaluran per tahap
     */
    public function getRataRataPerTahapAttribute()
    {
        $jumlahTahap = $this->jumlah_tahap_diberikan;
        if ($jumlahTahap == 0) return 0;

        return $this->total_diterima / $jumlahTahap;
    }

    /**
     * Tanggal penyaluran pertama
     */
    public function getTanggalPenyaluranPertamaAttribute()
    {
        $firstPenyaluran = $this->penyaluran()->orderBy('tanggal')->first();
        return $firstPenyaluran ? $firstPenyaluran->tanggal : null;
    }

    /**
     * Tanggal penyaluran terakhir
     */
    public function getTanggalPenyaluranTerakhirAttribute()
    {
        $lastPenyaluran = $this->penyaluran()->orderBy('tanggal', 'desc')->first();
        return $lastPenyaluran ? $lastPenyaluran->tanggal : null;
    }
}
