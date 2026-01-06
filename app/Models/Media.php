<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ref_table',
        'ref_id',
        'file_path',
        'file_name',
        'user_id',
    ];

    // Fungsi sederhana untuk mendapatkan URL file
    public function getUrlFile()
    {
        return asset('storage/' . $this->file_path);
    }

    // Fungsi untuk cek apakah file adalah gambar
    public function isImage()
    {
        $extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
        return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
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