<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class RiwayatPenyaluranBantuan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_penyaluran_bantuan';
    protected $primaryKey = 'penyaluran_id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'program_id',
        'penerima_id',
        'tahap_ke',
        'tanggal',
        'nilai'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nilai' => 'decimal:2'
    ];

    /**
     * Relationship dengan program bantuan
     */
    public function program()
    {
        return $this->belongsTo(ProgramBantuan::class, 'program_id');
    }

    /**
     * Relationship dengan penerima bantuan
     */
    public function penerima()
    {
        return $this->belongsTo(PenerimaBantuan::class, 'penerima_id');
    }

    /**
     * Relationship dengan media
     */
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id')->where('ref_table', 'riwayat_penyaluran_bantuan');
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
     * Accessor untuk format nilai
     */
    public function getNilaiFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nilai, 0, ',', '.');
    }

    /**
     * Mutator untuk membersihkan format rupiah sebelum disimpan
     * Mengubah "1.000.000" atau "Rp 1.000.000" menjadi "1000000"
     */
    public function setNilaiAttribute($value)
    {
        // Jika value sudah format angka standard (ada titik sebagai desimal), simpan langsung.
        if (is_numeric($value)) {
            $this->attributes['nilai'] = $value;
            return;
        }

        if (is_string($value)) {
            // Hapus semua karakter kecuali angka dan koma (regex lebih aman)
            $value = preg_replace('/[^0-9,]/', '', $value);
            // Ubah koma jadi titik desimal
            $value = str_replace(',', '.', $value);
        }
        $this->attributes['nilai'] = $value;
    }

    /**
     * Mutator untuk memastikan format tanggal Y-m-d (Database format)
     */
    public function setTanggalAttribute($value)
    {
        if (!empty($value)) {
            // Parse tanggal dari format apapun (d-m-Y atau Y-m-d) ke Y-m-d
            $this->attributes['tanggal'] = Carbon::parse($value)->format('Y-m-d');
        }
    }

    /**
     * Scope berdasarkan tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->whereYear('tanggal', $tahun);
    }

    /**
     * Scope berdasarkan program
     */
    public function scopeByProgram($query, $programId)
    {
        return $query->where('program_id', $programId);
    }

    /**
     * Scope berdasarkan penerima
     */
    public function scopeByPenerima($query, $penerimaId)
    {
        return $query->where('penerima_id', $penerimaId);
    }

    /**
     * Scope untuk periode tertentu
     */
    public function scopePeriode($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Scope untuk nilai minimal
     */
    public function scopeNilaiMin($query, $minNilai)
    {
        return $query->where('nilai', '>=', $minNilai);
    }

    /**
     * Scope untuk nilai maksimal
     */
    public function scopeNilaiMax($query, $maxNilai)
    {
        return $query->where('nilai', '<=', $maxNilai);
    }

    /**
     * Scope untuk tahap tertentu
     */
    public function scopeByTahap($query, $tahap)
    {
        return $query->where('tahap_ke', $tahap);
    }

    /**
     * Hitung total penyaluran per program
     */
    public function scopeTotalPerProgram($query)
    {
        return $query->selectRaw('program_id, SUM(nilai) as total_penyaluran')
                    ->groupBy('program_id');
    }

    /**
     * Hitung total penyaluran per penerima
     */
    public function scopeTotalPerPenerima($query)
    {
        return $query->selectRaw('penerima_id, SUM(nilai) as total_diterima, COUNT(*) as jumlah_tahap')
                    ->groupBy('penerima_id');
    }
}