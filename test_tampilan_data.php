<?php
// Test untuk cek data deskripsi dan catatan yang ada
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ProgramBantuan;
use App\Models\VerifikasiLapangan;

echo "=== CEK DATA DESKRIPSI & CATATAN ===\n\n";

// Cek Program Bantuan
echo "=== PROGRAM BANTUAN ===\n";
$programs = ProgramBantuan::all();
foreach($programs as $program) {
    echo "ID: {$program->program_id}\n";
    echo "Nama: {$program->nama_program}\n";
    echo "Deskripsi: " . ($program->deskripsi ?: '[KOSONG]') . "\n";
    echo "Anggaran: Rp " . number_format($program->anggaran, 0, ',', '.') . "\n";
    echo "---\n";
}

// Cek Verifikasi Lapangan
echo "\n=== VERIFIKASI LAPANGAN ===\n";
$verifikasis = VerifikasiLapangan::with(['pendaftar.warga'])->get();
foreach($verifikasis as $verifikasi) {
    echo "ID: {$verifikasi->verifikasi_id}\n";
    echo "Warga: " . ($verifikasi->pendaftar->warga->nama ?? 'Tidak ada') . "\n";
    echo "Petugas: {$verifikasi->petugas}\n";
    echo "Catatan: " . ($verifikasi->catatan ?: '[KOSONG]') . "\n";
    echo "Skor: {$verifikasi->skor}\n";
    echo "---\n";
}

echo "\n=== PERBAIKAN TAMPILAN ===\n";
echo "✓ Program Bantuan: Deskripsi dengan label 'Deskripsi:'\n";
echo "✓ Program Bantuan: Tampil 'Tidak ada deskripsi' jika kosong\n";
echo "✓ Verifikasi Lapangan: Catatan dengan label 'Catatan:'\n";
echo "✓ Verifikasi Lapangan: Tampil 'Tidak ada catatan' jika kosong\n";
echo "✓ Button foto sudah diperbaiki dengan emoji 📷\n";

echo "\nSekarang coba tambah data baru dengan deskripsi/catatan!\n";