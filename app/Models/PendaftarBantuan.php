<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftarBantuan extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_bantuan';
    protected $primaryKey = 'pendaftar_bantuan_id';
    public $timestamps = true;

    protected $fillable = [
        'warga_id',
        'program_id',
        'tanggal_daftar',
        'status',
        'keterangan',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id', 'warga_id');
    }

    public function program()
    {
        return $this->belongsTo(ProgramBantuan::class, 'program_id', 'program_id');
    }
}

