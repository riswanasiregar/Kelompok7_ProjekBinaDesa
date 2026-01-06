<?php

namespace App\Http\Controllers;

use App\Models\PendaftarBantuan;
use App\Models\ProgramBantuan;
use App\Models\Warga;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendaftarBantuanController extends Controller
{
    // Halaman utama - tampilkan semua pendaftar
    public function index(Request $request)
    {
        $query = PendaftarBantuan::query();

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        $data = $query->paginate(10)->withQueryString();

        return view('pendaftaran_bantuan.index', compact('data'));
    }

    // Halaman form tambah pendaftar baru
    public function create()
    {
        $warga = Warga::all();
        $program = ProgramBantuan::all();

        return view('pendaftaran_bantuan.create', compact('warga', 'program'));
    }

    // Simpan data pendaftar baru
    public function store(Request $request)
    {
        $request->validate([
            'warga_id' => 'required|exists:warga,warga_id',
            'program_id' => 'required|exists:program_bantuan,program_id',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        // Cek duplicate warga + program
        $exists = PendaftarBantuan::where('warga_id', $request->warga_id)
            ->where('program_id', $request->program_id)
            ->first();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['warga_id' => 'Warga ini sudah mendaftar program yang sama'])
                ->withInput();
        }

        // Simpan pendaftar
        $pendaftar = PendaftarBantuan::create([
            'warga_id' => $request->warga_id,
            'program_id' => $request->program_id,
        ]);

        // Upload file jika ada
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('pendaftar_bantuan', 'public');
            $fileName = $file->getClientOriginalName(); // <-- PERBAIKAN: Definisikan $fileName

            Media::create([
                'ref_table' => 'pendaftar_bantuan',
                'ref_id' => $pendaftar->pendaftar_id,
                'file_url' => $path, // <-- PERBAIKAN: 'file_url' bukan 'file_path'
                'caption' => $fileName, // <-- PERBAIKAN: 'caption' bukan 'file_name'
                'mime_type' => $file->getMimeType(), // <-- PERBAIKAN: Tambahkan mime_type
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data pendaftar bantuan berhasil ditambahkan.');
    }

    // Form edit pendaftar
    public function edit($id)
    {
        $data = PendaftarBantuan::findOrFail($id);
        $warga = Warga::all();
        $program = ProgramBantuan::all();

        return view('pendaftaran_bantuan.edit', compact('data', 'warga', 'program'));
    }

    // Update data pendaftar
    public function update(Request $request, $id)
    {
        $request->validate([
            'warga_id' => 'required|exists:warga,warga_id',
            'program_id' => 'required|exists:program_bantuan,program_id',
            'keterangan' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $data = PendaftarBantuan::findOrFail($id);

        // Cek duplicate (kecuali sendiri)
        $exists = PendaftarBantuan::where('warga_id', $request->warga_id)
            ->where('program_id', $request->program_id)
            ->where('pendaftar_id', '!=', $id)
            ->first();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['warga_id' => 'Warga ini sudah mendaftar program yang sama'])
                ->withInput();
        }

        $data->update([
            'warga_id' => $request->warga_id,
            'program_id' => $request->program_id,
            'keterangan' => $request->keterangan,
        ]);

        // Jika ada file baru
        if ($request->hasFile('media')) {
            $mediaLama = Media::where('ref_table', 'pendaftar_bantuan')
                ->where('ref_id', $data->pendaftar_id)
                ->first();

            if ($mediaLama) {
                Storage::disk('public')->delete($mediaLama->file_url); // <-- PERBAIKAN: 'file_url' bukan 'file_path'
                $mediaLama->delete();
            }

            $file = $request->file('media');
            $path = $file->store('pendaftar_bantuan', 'public');

            Media::create([
                'ref_table' => 'pendaftar_bantuan',
                'ref_id' => $data->pendaftar_id,
                'file_url' => $path, // <-- PERBAIKAN: 'file_url' bukan 'file_path'
                'caption' => $file->getClientOriginalName(), // <-- PERBAIKAN: 'caption' bukan 'file_name'
                'mime_type' => $file->getMimeType(), // <-- PERBAIKAN: Tambahkan mime_type
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data berhasil diupdate');
    }

    // Hapus data pendaftar
    public function destroy($id)
    {
        $data = PendaftarBantuan::findOrFail($id);

        $media = Media::where('ref_table', 'pendaftar_bantuan')
            ->where('ref_id', $data->pendaftar_id)
            ->get();

        foreach ($media as $m) {
            Storage::disk('public')->delete($m->file_url); // <-- PERBAIKAN: 'file_url' bukan 'file_path'
            $m->delete();
        }

        $data->delete();

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
