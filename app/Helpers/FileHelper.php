<?php

namespace App\Helpers;

use App\Models\Media;
use Illuminate\Support\Facades\Auth;

class FileHelper
{
    /**
     * Fungsi sederhana untuk upload foto
     * Mudah dipahami untuk mahasiswa semester 3
     */
    public static function uploadFoto($file, $folder, $refTable, $refId)
    {
        // Buat nama file yang unik
        $namaFile = time() . '_' . $file->getClientOriginalName();
        
        // Pastikan folder ada
        $folderPath = storage_path('app/public/' . $folder);
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }
        
        // Simpan file ke folder
        $file->move($folderPath, $namaFile);
        
        // Simpan info foto ke database
        $media = Media::create([
            'ref_table' => $refTable,
            'ref_id' => $refId,
            'file_path' => $folder . '/' . $namaFile,
            'file_name' => $file->getClientOriginalName(),
            'user_id' => Auth::id(),
        ]);
        
        return $media;
    }
    
    /**
     * Fungsi sederhana untuk hapus foto lama
     */
    public static function hapusFotoLama($refTable, $refId)
    {
        $mediaLama = Media::where('ref_table', $refTable)
            ->where('ref_id', $refId)
            ->get();
        
        foreach ($mediaLama as $media) {
            // Hapus file fisik
            $pathFile = storage_path('app/public/' . $media->file_path);
            if (file_exists($pathFile)) {
                unlink($pathFile);
            }
            
            // Hapus dari database
            $media->delete();
        }
    }
    
    /**
     * Fungsi untuk cek apakah file adalah gambar
     */
    public static function isGambar($namaFile)
    {
        $extension = pathinfo($namaFile, PATHINFO_EXTENSION);
        return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
    }
}