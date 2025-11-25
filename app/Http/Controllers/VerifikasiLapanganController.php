<?php

namespace App\Http\Controllers;

use App\Models\VerifikasiLapangan;
use App\Models\PendaftarBantuan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VerifikasiLapanganController extends Controller
{
    /**
     * Tampilkan daftar verifikasi.
     */
    public function index(Request $request)
    {
        $query = VerifikasiLapangan::with(['pendaftar', 'media']);

        if ($request->status_verifikasi) {
            $query->byStatus($request->status_verifikasi);
        }

        if ($request->petugas) {
            $query->byPetugas($request->petugas);
        }

        if ($request->start_date && $request->end_date) {
            $query->periode($request->start_date, $request->end_date);
        }

        if ($request->skor_min) {
            $query->skorMin($request->skor_min);
        }

        $verifikasi = $query->orderBy('tanggal', 'desc')->paginate(15);

        return view('verifikasi.index', compact('verifikasi'));
    }

    /**
     * Form tambah verifikasi.
     */
    public function create()
    {
        $pendaftar = PendaftarBantuan::all();
        return view('verifikasi.create', compact('pendaftar'));
    }

    /**
     * Simpan verifikasi baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pendaftar_id' => 'required|exists:pendaftar_bantuan,pendaftar_id',
            'petugas' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'skor' => 'required|integer|min:0|max:100',
            'status_verifikasi' => 'required|in:menunggu,diverifikasi,ditolak',
            'file_media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Simpan data
        $verifikasi = VerifikasiLapangan::create([
            'pendaftar_id' => $request->pendaftar_id,
            'petugas' => $request->petugas,
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            'skor' => $request->skor,
            'status_verifikasi' => $request->status_verifikasi,
        ]);

        // Upload media jika ada
        if ($request->hasFile('file_media')) {
            $path = $request->file('file_media')->store('media/verifikasi', 'public');

            Media::create([
                'ref_table' => 'verifikasi_lapangan',
                'ref_id' => $verifikasi->verifikasi_id,
                'file_path' => $path,
                'file_name' => $request->file('file_media')->getClientOriginalName(),
            ]);
        }

        return redirect()->route('verifikasi.index')
            ->with('success', 'Verifikasi berhasil ditambahkan.');
    }

    /**
     * Detail verifikasi.
     */
    public function show($id)
    {
        $data = VerifikasiLapangan::with(['pendaftar', 'media'])->findOrFail($id);
        return view('verifikasi.show', compact('data'));
    }

    /**
     * Form edit.
     */
    public function edit($id)
    {
        $data = VerifikasiLapangan::with('media')->findOrFail($id);
        $pendaftar = PendaftarBantuan::all();

        return view('verifikasi.edit', compact('data', 'pendaftar'));
    }

    /**
     * Update verifikasi.
     */
    public function update(Request $request, $id)
    {
        $verifikasi = VerifikasiLapangan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'pendaftar_id' => 'required|exists:pendaftar_bantuan,pendaftar_id',
            'petugas' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'skor' => 'required|integer|min:0|max:100',
            'status_verifikasi' => 'required|in:menunggu,diverifikasi,ditolak',
            'file_media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update data
        $verifikasi->update($request->except('file_media'));

        // Jika media baru diupload
        if ($request->hasFile('file_media')) {
            $mediaLama = Media::where('ref_table', 'verifikasi_lapangan')
                ->where('ref_id', $verifikasi->verifikasi_id)
                ->first();

            if ($mediaLama) {
                Storage::disk('public')->delete($mediaLama->file_path);
                $mediaLama->delete();
            }

            $path = $request->file('file_media')->store('media/verifikasi', 'public');

            Media::create([
                'ref_table' => 'verifikasi_lapangan',
                'ref_id' => $verifikasi->verifikasi_id,
                'file_path' => $path,
                'file_name' => $request->file('file_media')->getClientOriginalName(),
            ]);
        }

        return redirect()->route('verifikasi.index')
            ->with('success', 'Data verifikasi berhasil diperbarui.');
    }

    /**
     * Hapus verifikasi.
     */
    public function destroy($id)
    {
        $verifikasi = VerifikasiLapangan::findOrFail($id);

        $media = Media::where('ref_table', 'verifikasi_lapangan')
            ->where('ref_id', $verifikasi->verifikasi_id)
            ->get();

        foreach ($media as $m) {
            Storage::disk('public')->delete($m->file_path);
            $m->delete();
        }

        $verifikasi->delete();

        return redirect()->route('verifikasi.index')
            ->with('success', 'Data verifikasi berhasil dihapus.');
    }
}
