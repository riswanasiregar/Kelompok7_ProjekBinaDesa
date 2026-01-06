<?php

namespace App\Http\Controllers;

use App\Models\VerifikasiLapangan;
use App\Models\PendaftarBantuan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VerifikasiLapanganController extends Controller
{
    // Halaman utama - tampilkan semua verifikasi
    public function index()
    {
        // Ambil semua data verifikasi milik user yang login
        $verifikasi = VerifikasiLapangan::with(['pendaftar.warga', 'pendaftar.program'])
            ->where('user_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('verifikasi_lapangan.index', compact('verifikasi'));
    }

    // Halaman form tambah verifikasi baru
    public function create()
    {
        // Ambil semua pendaftar untuk dipilih
        $pendaftar = PendaftarBantuan::with(['warga', 'program'])->get();
        return view('verifikasi_lapangan.create', compact('pendaftar'));
    }

    // Simpan verifikasi baru ke database
    public function store(Request $request)
    {
        // Cek apakah semua input sudah diisi dengan benar
        $request->validate([
            'pendaftar_bantuan_id' => 'required',
            'petugas' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'skor' => 'required|integer|min:0|max:100',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        // Simpan data verifikasi ke database
        $verifikasi = VerifikasiLapangan::create([
            'pendaftar_bantuan_id' => $request->pendaftar_bantuan_id,
            'petugas' => $request->petugas,
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            'skor' => $request->skor,
            'user_id' => Auth::id(),
        ]);

        // Jika ada foto yang diupload
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            
            // Buat nama file yang unik
            $namaFile = time() . '_' . $file->getClientOriginalName();
            
            // Pastikan folder ada
            $folderPath = storage_path('app/public/verifikasi_lapangan');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
            
            // Simpan file dengan cara yang lebih sederhana
            $file->move($folderPath, $namaFile);
            
            // Simpan info foto ke database
            Media::create([
                'ref_table' => 'verifikasi_lapangan',
                'ref_id' => $verifikasi->verifikasi_id,
                'file_path' => 'verifikasi_lapangan/' . $namaFile,
                'file_name' => $file->getClientOriginalName(),
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('verifikasi.index')
            ->with('success', 'Verifikasi berhasil ditambahkan');
    }

    // Halaman form edit verifikasi
    public function edit($id)
    {
        // Cari data verifikasi yang akan diedit
        $data = VerifikasiLapangan::where('user_id', Auth::id())->findOrFail($id);
        $pendaftar = PendaftarBantuan::with(['warga', 'program'])->get();

        return view('verifikasi_lapangan.edit', compact('data', 'pendaftar'));
    }

    // Update data verifikasi
    public function update(Request $request, $id)
    {
        // Cari data verifikasi
        $verifikasi = VerifikasiLapangan::where('user_id', Auth::id())->findOrFail($id);

        // Cek apakah semua input sudah diisi dengan benar
        $request->validate([
            'pendaftar_bantuan_id' => 'required',
            'petugas' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'skor' => 'required|integer|min:0|max:100',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        // Update data verifikasi
        $verifikasi->update([
            'pendaftar_bantuan_id' => $request->pendaftar_bantuan_id,
            'petugas' => $request->petugas,
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            'skor' => $request->skor,
        ]);

        // Jika ada foto baru yang diupload
        if ($request->hasFile('media')) {
            // Hapus foto lama jika ada
            $fotoLama = Media::where('ref_table', 'verifikasi_lapangan')
                ->where('ref_id', $verifikasi->verifikasi_id)
                ->first();
            
            if ($fotoLama) {
                Storage::disk('public')->delete($fotoLama->file_path);
                $fotoLama->delete();
            }

            // Simpan foto baru
            $file = $request->file('media');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/verifikasi_lapangan', $namaFile);
            
            Media::create([
                'ref_table' => 'verifikasi_lapangan',
                'ref_id' => $verifikasi->verifikasi_id,
                'file_path' => 'verifikasi_lapangan/' . $namaFile,
                'file_name' => $file->getClientOriginalName(),
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('verifikasi.index')
            ->with('success', 'Data verifikasi berhasil diupdate');
    }

    // Hapus data verifikasi
    public function destroy($id)
    {
        // Cari data verifikasi yang akan dihapus
        $verifikasi = VerifikasiLapangan::where('user_id', Auth::id())->findOrFail($id);

        // Hapus semua foto yang terkait
        $semuaFoto = Media::where('ref_table', 'verifikasi_lapangan')
            ->where('ref_id', $verifikasi->verifikasi_id)
            ->get();

        foreach ($semuaFoto as $foto) {
            Storage::disk('public')->delete($foto->file_path);
            $foto->delete();
        }

        // Hapus data verifikasi
        $verifikasi->delete();

        return redirect()->route('verifikasi.index')
            ->with('success', 'Data verifikasi berhasil dihapus');
    }
}