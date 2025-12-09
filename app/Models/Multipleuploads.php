<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multipleuploads extends Model
{
  
    protected $table = 'multiuploads';

   
    protected $fillable = [
        'filename',
    ];
}
