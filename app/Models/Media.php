<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';
    protected $primaryKey = 'media_id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'ref_table',
        'ref_id',
        'file_url',
        'caption',
        'mime_type',
        'sort_order'
    ];

    /**
     * Accessor untuk URL lengkap file
     */
    public function getFullUrlAttribute()
    {
        return asset('storage/' . $this->file_url);
    }

    /**
     * Accessor untuk mengecek apakah file adalah gambar
     */
    public function getIsImageAttribute()
    {
        return in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    /**
     * Accessor untuk icon berdasarkan mime type
     */
    public function getFileIconAttribute()
    {
        if ($this->is_image) {
            return 'image';
        }

        switch ($this->mime_type) {
            case 'application/pdf':
                return 'picture_as_pdf';
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return 'article';
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                return 'table_chart';
            default:
                return 'insert_drive_file';
        }
    }

    /**
     * Accessor untuk nama file yang user-friendly
     */
    public function getDisplayNameAttribute()
    {
        return $this->caption ?: basename($this->file_url);
    }

    /**
     * Scope untuk filter berdasarkan tabel referensi
     */
    public function scopeByReferensi($query, $refTable, $refId = null)
    {
        $query->where('ref_table', $refTable);

        if ($refId) {
            $query->where('ref_id', $refId);
        }

        return $query->orderBy('sort_order');
    }

    /**
     * Scope untuk masing-masing jenis referensi
     */
    public function scopeProgramBantuan($query, $programId = null)
    {
        return $this->scopeByReferensi($query, 'program_bantuan', $programId);
    }

    public function scopePendaftarBantuan($query, $pendaftarId = null)
    {
        return $this->scopeByReferensi($query, 'pendaftar_bantuan', $pendaftarId);
    }

    public function scopeVerifikasiLapangan($query, $verifikasiId = null)
    {
        return $this->scopeByReferensi($query, 'verifikasi_lapangan', $verifikasiId);
    }

    public function scopePenyaluranBantuan($query, $penyaluranId = null)
    {
        return $this->scopeByReferensi($query, 'penyaluran_bantuan', $penyaluranId);
    }
}
