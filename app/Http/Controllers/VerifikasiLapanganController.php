<?php

namespace App\Http\Controllers;

use App\Models\VerifikasiLapangan;
use App\Models\PendaftarBantuan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class VerifikasiLapanganController extends Controller
{
    /**
     * Tampilkan daftar verifikasi.
     */
    public function index(Request $request)
    {
        $query = VerifikasiLapangan::with(['pendaftar', 'media'])
            ->when(!Auth::user()->isAdmin(), function ($query) {
                $query->where('user_id', Auth::id());
            });

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

        return view('verifikasi_lapangan.index', compact('verifikasi'));
    }

    /**
     * Form tambah verifikasi.
     */
    public function create()
    {
        $pendaftar = PendaftarBantuan::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->get();
        return view('verifikasi_lapangan.create', compact('pendaftar'));
    }

    /**
     * Simpan verifikasi baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pendaftar_bantuan_id' => 'required|exists:pendaftar_bantuan,pendaftar_bantuan_id',
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

        // Pastikan pendaftar milik user saat ini (untuk guest)
        $pendaftar = PendaftarBantuan::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($request->pendaftar_bantuan_id);

        // Simpan data
        $verifikasi = VerifikasiLapangan::create([
            'pendaftar_bantuan_id' => $pendaftar->pendaftar_bantuan_id,
            'petugas' => $request->petugas,
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            'skor' => $request->skor,
            'status_verifikasi' => $request->status_verifikasi,
            'user_id' => Auth::id(),
        ]);

        // Upload media jika ada
        if ($request->hasFile('file_media')) {
            $path = $request->file('file_media')->store('media/verifikasi', 'public');

            Media::create([
                'ref_table' => 'verifikasi_lapangan',
                'ref_id' => $verifikasi->verifikasi_id,
                'file_path' => $path,
                'file_name' => $request->file('file_media')->getClientOriginalName(),
                'user_id' => Auth::id(),
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
        $data = VerifikasiLapangan::with(['pendaftar', 'media'])
            ->when(!Auth::user()->isAdmin(), function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($id);
        return view('verifikasi_lapangan.show', compact('data'));
    }

    /**
     * Form edit.
     */
    public function edit($id)
    {
        $data = VerifikasiLapangan::with('media')
            ->when(!Auth::user()->isAdmin(), function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        $pendaftar = PendaftarBantuan::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('verifikasi_lapangan.edit', compact('data', 'pendaftar'));
    }

    /**
     * Update verifikasi.
     */
    public function update(Request $request, $id)
    {
        $verifikasi = VerifikasiLapangan::findOrFail($id);
        if (!Auth::user()->isAdmin() && $verifikasi->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'pendaftar_bantuan_id' => 'required|exists:pendaftar_bantuan,pendaftar_bantuan_id',
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

        // Validasi kepemilikan pendaftar untuk guest
        $pendaftar = PendaftarBantuan::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($request->pendaftar_bantuan_id);

        // Update data verifikasi
        $verifikasi->update([
            'pendaftar_bantuan_id' => $pendaftar->pendaftar_bantuan_id,
            'petugas' => $request->petugas,
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            'skor' => $request->skor,
            'status_verifikasi' => $request->status_verifikasi,
        ]);

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
                'user_id' => Auth::id(),
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
        $verifikasi = VerifikasiLapangan::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

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