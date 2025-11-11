<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendaftarBantuan extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_bantuan';
    protected $primaryKey = 'pendaftar_bantuan_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'warga_id',
        'program_id',
        'tanggal_daftar',
        'status',
        'keterangan',
    ];

    /**
     * Relasi ke tabel warga
     * Setiap pendaftar bantuan dimiliki oleh satu warga.
     */
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id', 'warga_id');
    }

    /**
     * Relasi ke tabel program_bantuan
     * Setiap pendaftar terhubung ke satu program bantuan.
     */
    public function program()
    {
        return $this->belongsTo(ProgramBantuan::class, 'program_id', 'program_id');
    }
}
