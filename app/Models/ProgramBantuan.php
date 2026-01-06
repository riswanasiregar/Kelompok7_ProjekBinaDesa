<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProgramBantuan extends Model
{
    use HasFactory;

    protected $table = 'program_bantuan';
    protected $primaryKey = 'program_id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'kode',
        'nama_program',
        'tahun',
        'deskripsi',
        'anggaran',
    ];

    protected $casts = [
        'anggaran' => 'decimal:2',
        'tahun' => 'integer'
    ];

    /**
     * Relationship dengan media
     */
   public function media()
{
    return $this->hasMany(Media::class, 'ref_id')->where('ref_table', 'program_bantuan')->orderBy('sort_order', 'asc');
}

    /**
     * Relationship dengan pendaftar
     */
    public function pendaftar()
    {
        return $this->hasMany(PendaftarBantuan::class, 'program_id');
    }

    /**
     * Relationship dengan penerima
     */
    public function penerima()
    {
        return $this->hasMany(PenerimaBantuan::class, 'program_id');
    }

    /**
     * Relationship dengan riwayat penyaluran
     */
    public function penyaluran()
    {
        return $this->hasMany(RiwayatPenyaluranBantuan::class, 'program_id');
    }

    /**
     * Relationship dengan verifikasi melalui pendaftar
     */
    public function verifikasi()
    {
        return $this->hasManyThrough(
            VerifikasiLapangan::class,
            PendaftarBantuan::class,
            'program_id', // Foreign key pada pendaftar_bantuan
            'pendaftar_id', // Foreign key pada verifikasi_lapangan
            'program_id', // Local key pada program_bantuan
            'pendaftar_id' // Local key pada pendaftar_bantuan
        );
    }

    //filter
   public function scopeFilter(Builder $query, $request, array $filterableColumns): Builder
{
    foreach ($filterableColumns as $column) {
        if ($request->filled($column)) {
           $query->where($column, 'like', '%' . trim($request->input($column)) . '%');
        }
    }
    return $query;
}

    public function scopeSearch($query, $request, array $columns)
{
    if ($request->filled('search')) {
        $query->where(function($q) use ($request, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'LIKE', '%' . $request->search . '%');
            }
        });
    }
}

    public function getAnggaranFormattedAttribute()
    {
        return 'Rp ' . number_format($this->anggaran, 0, ',', '.');
    }

    /**
    * Accessor untuk media utama
    */
    public function getMediaUtamaAttribute()
    {
        return $this->media()->orderBy('sort_order')->first();
    }

    /**
     * Hitung total penyaluran
     */
    public function getTotalPenyaluranAttribute()
    {
        return $this->penyaluran()->sum('nilai');
    }

    /**
     * Hitung sisa anggaran
     */
    public function getSisaAnggaranAttribute()
    {
        return $this->anggaran - $this->total_penyaluran;
    }

    /**
     * Hitung jumlah pendaftar
     */
    public function getJumlahPendaftarAttribute()
    {
        return $this->pendaftar()->count();
    }

    /**
     * Hitung jumlah penerima
     */
    public function getJumlahPenerimaAttribute()
    {
        return $this->penerima()->count();
    }

    /**
     * Persentase penyerapan anggaran
     */
    public function getPersentasePenyerapanAttribute()
    {
        if ($this->anggaran == 0) return 0;
        return ($this->total_penyaluran / $this->anggaran) * 100;
    }

    /**
     * Scope untuk program aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('tahun', '>=', now()->year);
    }

    /**
     * Scope berdasarkan tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk program dengan anggaran tertentu
     */
    public function scopeAnggaranMin($query, $minAnggaran)
    {
        return $query->where('anggaran', '>=', $minAnggaran);
    }

    /**
     * Scope untuk program dengan anggaran maksimal
     */
    public function scopeAnggaranMax($query, $maxAnggaran)
    {
        return $query->where('anggaran', '<=', $maxAnggaran);
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
