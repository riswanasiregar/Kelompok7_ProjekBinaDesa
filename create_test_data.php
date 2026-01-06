<?php
// Buat contoh data untuk test deskripsi dan catatan
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ProgramBantuan;
use App\Models\VerifikasiLapangan;
use Illuminate\Support\Facades\Auth;

echo "=== BUAT CONTOH DATA UNTUK TEST ===\n\n";

// Set user ID untuk test (ambil user pertama)
$userId = 1; // Ganti dengan ID user yang ada

// Update program bantuan yang ada dengan deskripsi
echo "Update Program Bantuan dengan deskripsi...\n";
$program = ProgramBantuan::first();
if($program) {
    $program->update([
        'deskripsi' => 'Program bantuan sembako untuk keluarga kurang mampu di desa. Bantuan berupa beras, minyak goreng, gula, dan kebutuhan pokok lainnya yang diberikan setiap bulan.'
    ]);
    echo "✓ Program '{$program->nama_program}' berhasil diupdate\n";
}

// Update verifikasi lapangan yang ada dengan catatan
echo "\nUpdate Verifikasi Lapangan dengan catatan...\n";
$verifikasi = VerifikasiLapangan::first();
if($verifikasi) {
    $verifikasi->update([
        'catatan' => 'Hasil verifikasi menunjukkan keluarga ini layak menerima bantuan. Kondisi rumah sederhana, penghasilan tidak tetap, dan memiliki 3 anak yang masih sekolah.'
    ]);
    echo "✓ Verifikasi ID {$verifikasi->verifikasi_id} berhasil diupdate\n";
}

echo "\n=== DATA TEST SUDAH DIBUAT ===\n";
echo "Sekarang coba buka halaman:\n";
echo "1. Program Bantuan - akan tampil deskripsi\n";
echo "2. Verifikasi Lapangan - akan tampil catatan\n";
echo "\nData baru yang ditambah juga akan tersimpan dengan benar!\n";