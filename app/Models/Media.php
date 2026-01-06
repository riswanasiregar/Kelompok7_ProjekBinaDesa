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
        'file_path',
        'file_name',
        'caption',
        'mime_type',
        'sort_order',
        'user_id',
    ];

    /**
     * Accessor untuk URL lengkap file
     */
    public function getFullUrlAttribute()
    {
        if ($this->file_url) {
            return asset('storage/' . $this->file_url);
        }
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    /**
     * Accessor untuk mengecek apakah file adalah gambar
     */
    public function getIsImageAttribute()
    {
        if ($this->mime_type) {
            return in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
        }
        if ($this->file_name) {
            $extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
            return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        }
        return false;
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
        if ($this->caption) {
            return $this->caption;
        }
        if ($this->file_name) {
            return $this->file_name;
        }
        if ($this->file_url) {
            return basename($this->file_url);
        }
        return 'Unknown File';
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

    // Fungsi sederhana untuk mendapatkan URL file
    public function getUrlFile()
    {
        if ($this->file_url) {
            return asset('storage/' . $this->file_url);
        }
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    // Fungsi untuk cek apakah file adalah gambar
    public function isImage()
    {
        if ($this->mime_type) {
            return in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
        }
        if ($this->file_name) {
            $extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
            return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        }
        return false;
    }

    // COMMAND: Fungsi helper untuk mendapatkan media berdasarkan tabel dan ID
    public static function getFotoByTable($tableName, $tableId)
    {
        return self::where('ref_table', $tableName)
                   ->where('ref_id', $tableId)
                   ->first();
    }

    // COMMAND: Fungsi untuk cek apakah ada foto
    public static function hasFoto($tableName, $tableId)
    {
        return self::where('ref_table', $tableName)
                   ->where('ref_id', $tableId)
                   ->exists();
    }
}
