<?php

namespace App\Models;

use App\Models\Multipleuploads;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'notes',
        'user_id',
    ];

    public function supportingFiles()
    {
        return $this->hasMany(Multipleuploads::class, 'ref_id')
            ->where('ref_table', 'pelanggan');
    }
}

