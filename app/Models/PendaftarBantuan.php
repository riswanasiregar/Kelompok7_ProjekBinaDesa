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
        'status_seleksi'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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
                    ->where('ref_table', 'pendaftar_bantuan')
                    ->orderBy('sort_order');
    }

    /**
     * Relationship dengan penerima (jika diterima)
     */
    public function penerima()
    {
        return $this->hasOne(PenerimaBantuan::class, 'pendaftar_id');
    }

    /**
     * Relationship dengan riwayat penyaluran melalui penerima
     */
    public function penyaluran()
    {
        return $this->hasManyThrough(
            RiwayatPenyaluranBantuan::class,
            PenerimaBantuan::class,
            'pendaftar_id', // Foreign key pada penerima_bantuan
            'penerima_id', // Foreign key pada riwayat_penyaluran_bantuan
            'pendaftar_id', // Local key pada pendaftar_bantuan
            'penerima_id' // Local key pada penerima_bantuan
        );
    }

    /**
     * Scope untuk filter status seleksi
     */
    public function scopeByStatus(Builder $query, $status)
    {
        if ($status) {
            $query->where('status_seleksi', $status);
        }
        return $query;
    }

    /**
     * Scope untuk filter program
     */
    public function scopeByProgram(Builder $query, $programId)
    {
        if ($programId) {
            $query->where('program_id', $programId);
        }
        return $query;
    }

    /**
     * Scope untuk pendaftar yang belum diverifikasi
     */
    public function scopeBelumDiverifikasi(Builder $query)
    {
        return $query->whereDoesntHave('verifikasi');
    }

    /**
     * Scope untuk filter berdasarkan tahun program
     */
    public function scopeByTahun(Builder $query, $tahun)
    {
        if ($tahun) {
            $query->whereHas('program', function($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }
        return $query;
    }

    /**
     * Scope untuk search global
     */
    public function scopeSearch(Builder $query, $search, array $columns)
    {
        if ($search) {
            $query->where(function($q) use ($search, $columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $search . '%');
                }
            });
        }
        return $query;
    }

    /**
     * Scope untuk search dengan join ke relasi
     */
    public function scopeSearchGlobal(Builder $query, $search)
    {
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('warga', function($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%")
                       ->orWhere('no_ktp', 'like', "%{$search}%")
                       ->orWhere('alamat', 'like', "%{$search}%");
                })
                ->orWhereHas('program', function($q3) use ($search) {
                    $q3->where('nama_program', 'like', "%{$search}%")
                       ->orWhere('kode', 'like', "%{$search}%");
                })
                ->orWhere('status_seleksi', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Accessor untuk status seleksi lengkap
     */
    public function getStatusLabelAttribute()
    {
        $status = [
            'pending' => ['class' => 'badge bg-warning', 'label' => 'Menunggu'],
            'diterima' => ['class' => 'badge bg-success', 'label' => 'Diterima'],
            'ditolak' => ['class' => 'badge bg-danger', 'label' => 'Ditolak']
        ];

        return $status[$this->status_seleksi] ?? ['class' => 'badge bg-secondary', 'label' => 'Tidak Diketahui'];
    }

    /**
     * Accessor untuk media utama
     */
    public function getMediaUtamaAttribute()
    {
        return $this->media()->orderBy('sort_order')->first();
    }

    /**
     * Accessor untuk nama lengkap warga
     */
    public function getNamaWargaAttribute()
    {
        return $this->warga ? $this->warga->nama : '-';
    }

    /**
     * Accessor untuk nama program
     */
    public function getNamaProgramAttribute()
    {
        return $this->program ? $this->program->nama_program : '-';
    }

    /**
     * Accessor untuk kode program
     */
    public function getKodeProgramAttribute()
    {
        return $this->program ? $this->program->kode : '-';
    }

    /**
     * Accessor untuk tahun program
     */
    public function getTahunProgramAttribute()
    {
        return $this->program ? $this->program->tahun : '-';
    }

    /**
     * Cek apakah pendaftar sudah diverifikasi
     */
    public function getSudahDiverifikasiAttribute()
    {
        return $this->verifikasi()->exists();
    }

    /**
     * Cek apakah pendaftar sudah jadi penerima
     */
    public function getSudahJadiPenerimaAttribute()
    {
        return $this->penerima()->exists();
    }

    /**
     * Hitung jumlah verifikasi
     */
    public function getJumlahVerifikasiAttribute()
    {
        return $this->verifikasi()->count();
    }

    /**
     * Cek apakah pendaftar diterima
     */
    public function getIsDiterimaAttribute()
    {
        return $this->status_seleksi === 'diterima';
    }

    /**
     * Cek apakah pendaftar ditolak
     */
    public function getIsDitolakAttribute()
    {
        return $this->status_seleksi === 'ditolak';
    }

    /**
     * Cek apakah pendaftar pending
     */
    public function getIsPendingAttribute()
    {
        return $this->status_seleksi === 'pending';
    }

    /**
     * Format tanggal pendaftaran
     */
    public function getTanggalDaftarFormattedAttribute()
    {
        return $this->created_at ? $this->created_at->translatedFormat('d F Y') : '-';
    }

    /**
     * Format tanggal pendaftaran dengan waktu
     */
    public function getTanggalDaftarFullAttribute()
    {
        return $this->created_at ? $this->created_at->translatedFormat('d F Y H:i:s') : '-';
    }

    /**
     * Scope untuk pendaftar yang diterima
     */
    public function scopeDiterima(Builder $query)
    {
        return $query->where('status_seleksi', 'diterima');
    }

    /**
     * Scope untuk pendaftar yang ditolak
     */
    public function scopeDitolak(Builder $query)
    {
        return $query->where('status_seleksi', 'ditolak');
    }

    /**
     * Scope untuk pendaftar yang pending
     */
    public function scopePending(Builder $query)
    {
        return $query->where('status_seleksi', 'pending');
    }

    /**
     * Scope untuk pendaftar berdasarkan rentang tanggal
     */
    public function scopeRentangTanggal(Builder $query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        return $query;
    }

    /**
     * Scope untuk program aktif (tahun sekarang)
     */
    public function scopeProgramAktif(Builder $query)
    {
        return $query->whereHas('program', function($q) {
            $q->where('tahun', '>=', now()->year);
        });
    }
}
