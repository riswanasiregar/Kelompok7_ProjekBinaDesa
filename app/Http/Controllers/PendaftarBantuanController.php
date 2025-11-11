<?php

namespace App\Http\Controllers;

use App\Models\PendaftarBantuan;
use App\Models\Warga;
use App\Models\ProgramBantuan;
use Illuminate\Http\Request;

class PendaftarBantuanController extends Controller
{
    /**
     * Menampilkan semua data pendaftar bantuan.
     */
    public function index()
    {
        // Ambil semua data dengan relasi warga dan program
        $pendaftar = PendaftarBantuan::with(['warga', 'program'])->get();

        return view('admin.pendaftar_bantuan.index', compact('pendaftar'));
    }

    /**
     * Menampilkan form tambah data baru.
     */
    public function create()
    {
        $warga = Warga::all();
        $program = ProgramBantuan::all();

        return view('admin.pendaftar_bantuan.create', compact('warga', 'program'));
    }

    /**
     * Simpan data baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'warga_id' => 'required|exists:warga,warga_id',
            'program_id' => 'required|exists:program_bantuan,program_id',
            'tanggal_daftar' => 'required|date',
            'status' => 'required|in:Diproses,Diterima,Ditolak',
            'keterangan' => 'nullable|string|max:255',
        ]);

        PendaftarBantuan::create([
            'warga_id' => $request->warga_id,
            'program_id' => $request->program_id,
            'tanggal_daftar' => $request->tanggal_daftar,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('pendaftar_bantuan.index')
                         ->with('success', 'Data pendaftar berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit data pendaftar.
     */
    public function edit($id)
    {
        $pendaftar = PendaftarBantuan::findOrFail($id);
        $warga = Warga::all();
        $program = ProgramBantuan::all();

        return view('admin.pendaftar_bantuan.edit', compact('pendaftar', 'warga', 'program'));
    }

    /**
     * Update data pendaftar di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'warga_id' => 'required|exists:warga,warga_id',
            'program_id' => 'required|exists:program_bantuan,program_id',
            'tanggal_daftar' => 'required|date',
            'status' => 'required|in:Diproses,Diterima,Ditolak',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $pendaftar = PendaftarBantuan::findOrFail($id);
        $pendaftar->update([
            'warga_id' => $request->warga_id,
            'program_id' => $request->program_id,
            'tanggal_daftar' => $request->tanggal_daftar,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('pendaftar_bantuan.index')
                         ->with('success', 'Data pendaftar berhasil diperbarui.');
    }

    /**
     * Hapus data pendaftar.
     */
    public function destroy($id)
    {
        $pendaftar = PendaftarBantuan::findOrFail($id);
        $pendaftar->delete();

        return redirect()->route('pendaftar_bantuan.index')
                         ->with('success', 'Data pendaftar berhasil dihapus.');
    }
}
