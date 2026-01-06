<?php

namespace App\Http\Controllers;

use App\Models\PendaftarBantuan;
use App\Models\ProgramBantuan;
use App\Models\Warga;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PendaftarBantuanController extends Controller
{
    // Halaman utama - tampilkan semua pendaftar
    public function index(Request $request)
    {
        // Ambil semua data pendaftar milik user yang login
        $data = PendaftarBantuan::with(['warga', 'program'])
            ->where('user_id', Auth::id())
            ->latest('tanggal_daftar')
            ->paginate(9);

        return view('pendaftaran_bantuan.index', compact('data'));
    }

    // Halaman form tambah pendaftar baru
    public function create()
    {
        // Ambil data warga dan program untuk dipilih
        $warga = Warga::where('user_id', Auth::id())->get();
        $program = ProgramBantuan::where('user_id', Auth::id())->get();
        return view('pendaftaran_bantuan.create', compact('warga', 'program'));
    }

    // Simpan data pendaftar baru
    public function store(Request $request)
    {
        // Cek input yang wajib diisi
        $request->validate([
            'warga_id' => 'required',
            'program_id' => 'required',
            'tanggal_daftar' => 'required|date',
            'keterangan' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        // Simpan data pendaftar
        $pendaftar = PendaftarBantuan::create([
            'warga_id' => $request->warga_id,
            'program_id' => $request->program_id,
            'tanggal_daftar' => $request->tanggal_daftar,
            'keterangan' => $request->keterangan,
            'user_id' => Auth::id(),
        ]);

        // Jika ada foto yang diupload
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            
            // Buat nama file yang unik
            $namaFile = time() . '_' . $file->getClientOriginalName();
            
            // Pastikan folder ada
            $folderPath = storage_path('app/public/pendaftar_bantuan');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
            
            // Simpan file dengan cara yang lebih sederhana
            $file->move($folderPath, $namaFile);
            
            // Simpan info foto ke database
            Media::create([
                'ref_table' => 'pendaftar_bantuan',
                'ref_id' => $pendaftar->pendaftar_bantuan_id,
                'file_path' => 'pendaftar_bantuan/' . $namaFile,
                'file_name' => $file->getClientOriginalName(),
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    // Form edit pendaftar
    public function edit($id)
    {
        $data = PendaftarBantuan::where('user_id', Auth::id())->findOrFail($id);
        $warga = Warga::where('user_id', Auth::id())->get();
        $program = ProgramBantuan::where('user_id', Auth::id())->get();

        return view('pendaftaran_bantuan.edit', compact('data', 'warga', 'program'));
    }

    // Update data pendaftar
    public function update(Request $request, $id)
    {
        // Cek input yang wajib diisi
        $request->validate([
            'warga_id' => 'required',
            'program_id' => 'required',
            'tanggal_daftar' => 'required|date',
            'keterangan' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        // Cari data yang akan diupdate
        $data = PendaftarBantuan::where('user_id', Auth::id())->findOrFail($id);
        
        // Update data utama
        $data->update([
            'warga_id' => $request->warga_id,
            'program_id' => $request->program_id,
            'tanggal_daftar' => $request->tanggal_daftar,
            'keterangan' => $request->keterangan,
        ]);

        // Jika ada file baru yang diupload
        if ($request->hasFile('media')) {
            // Hapus file lama jika ada
            $mediaLama = Media::where('ref_table', 'pendaftar_bantuan')
                ->where('ref_id', $data->pendaftar_bantuan_id)
                ->first();
            
            if ($mediaLama) {
                // Hapus file fisik
                $pathLama = storage_path('app/public/' . $mediaLama->file_path);
                if (file_exists($pathLama)) {
                    unlink($pathLama);
                }
                $mediaLama->delete();
            }

            // Simpan file baru
            $file = $request->file('media');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            
            // Pastikan folder ada
            $folderPath = storage_path('app/public/pendaftar_bantuan');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
            
            // Simpan file dengan cara yang lebih sederhana
            $file->move($folderPath, $namaFile);
            
            Media::create([
                'ref_table' => 'pendaftar_bantuan',
                'ref_id' => $data->pendaftar_bantuan_id,
                'file_path' => 'pendaftar_bantuan/' . $namaFile,
                'file_name' => $file->getClientOriginalName(),
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data berhasil diupdate');
    }

    // Hapus data pendaftar
    public function destroy($id)
    {
        $data = PendaftarBantuan::where('user_id', Auth::id())->findOrFail($id);

        // Hapus file yang terkait
        $media = Media::where('ref_table', 'pendaftar_bantuan')
            ->where('ref_id', $data->pendaftar_bantuan_id)
            ->get();

        foreach ($media as $m) {
            Storage::disk('public')->delete($m->file_path);
            $m->delete();
        }

        $data->delete();

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data berhasil dihapus');
    }
}