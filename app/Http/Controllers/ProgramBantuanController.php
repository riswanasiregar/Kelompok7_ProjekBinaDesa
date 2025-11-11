<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramBantuan;

class ProgramBantuanController extends Controller
{
    /**
     * Tampilkan semua data program bantuan.
     */
    public function index()
    {
        $ProgramBantuan = ProgramBantuan::all();
        return view('admin.program_bantuan.index', compact('ProgramBantuan'));
    }

    /**
     * Tampilkan form tambah program bantuan.
     */
    public function create()
    {
        return view('admin.program_bantuan.create');
    }

    /**
     * Simpan data baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:program_bantuan,kode|max:20',
            'nama_program' => 'required|string|max:100',
            'tahun' => 'nullable|integer',
            'anggaran' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
        ]);

        ProgramBantuan::create([
            'kode' => $request->kode,
            'nama_program' => $request->nama_program,
            'tahun' => $request->tahun,
            'anggaran' => $request->anggaran,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('program_bantuan.index')
                         ->with('success', 'Data program bantuan berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit program bantuan.
     */
    public function edit(string $id)
    {
        $data = ProgramBantuan::findOrFail($id);
        return view('admin.program_bantuan.edit', compact('data'));
    }

    /**
     * Update data program bantuan di database.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:program_bantuan,kode,' . $id . ',program_id',
            'nama_program' => 'required|string|max:100',
            'tahun' => 'nullable|integer',
            'anggaran' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
        ]);

        $program = ProgramBantuan::findOrFail($id);
        $program->update([
            'kode' => $request->kode,
            'nama_program' => $request->nama_program,
            'tahun' => $request->tahun,
            'anggaran' => $request->anggaran,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('program_bantuan.index')
                         ->with('success', 'Data program bantuan berhasil diperbarui!');
    }

    /**
     * Hapus data program bantuan.
     */
    public function destroy(string $id)
    {
        $program = ProgramBantuan::findOrFail($id);
        $program->delete();

        return redirect()->route('program_bantuan.index')
                         ->with('success', 'Data program bantuan berhasil dihapus!');
    }
}
