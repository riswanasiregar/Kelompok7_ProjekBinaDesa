<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multipleuploads extends Model
{
    // nama tabel sesuai database
    protected $table = 'multiuploads';

    // kolom yang boleh di-isi (mass assignment)
    protected $fillable = [
        'filename',
    ];
}
