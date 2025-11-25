<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'nilai',
        'keterangan',
        'status_penyaluran',
        'metode_penyaluran',
        'bukti_penyaluran'
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
        return $this->hasMany(Media::class, 'ref_id')->where('ref_table', 'penyaluran_bantuan');
    }

    /**
     * Accessor untuk format nilai
     */
    public function getNilaiFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nilai, 0, ',', '.');
    }

    /**
     * Accessor untuk status penyaluran lengkap
     */
    public function getStatusLabelAttribute()
    {
        $status = [
            'direncanakan' => ['class' => 'bg-info', 'label' => 'Direncanakan'],
            'diberikan' => ['class' => 'bg-success', 'label' => 'Telah Diberikan'],
            'dibatalkan' => ['class' => 'bg-danger', 'label' => 'Dibatalkan']
        ];

        return $status[$this->status_penyaluran] ?? ['class' => 'bg-secondary', 'label' => 'Tidak Diketahui'];
    }

    /**
     * Scope untuk penyaluran yang sudah diberikan
     */
    public function scopeSudahDiberikan($query)
    {
        return $query->where('status_penyaluran', 'diberikan');
    }

    /**
     * Scope untuk penyaluran yang direncanakan
     */
    public function scopeDirencanakan($query)
    {
        return $query->where('status_penyaluran', 'direncanakan');
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
     * Scope berdasarkan metode penyaluran
     */
    public function scopeByMetode($query, $metode)
    {
        return $query->where('metode_penyaluran', $metode);
    }

    /**
     * Scope untuk periode tertentu
     */
    public function scopePeriode($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }
}
