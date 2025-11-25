<?php

namespace App\Http\Controllers;

use App\Models\PendaftarBantuan;
use App\Models\ProgramBantuan;
use App\Models\Warga;
use Illuminate\Http\Request;

class PendaftarBantuanController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = PendaftarBantuan::with(['warga', 'program'])
            ->latest('tanggal_daftar');

        $data = $dataQuery->paginate(9)->appends($request->query());

        $stats = [
            'total' => PendaftarBantuan::count(),
            'diproses' => PendaftarBantuan::where('status', 'Diproses')->count(),
            'diterima' => PendaftarBantuan::where('status', 'Diterima')->count(),
            'ditolak' => PendaftarBantuan::where('status', 'Ditolak')->count(),
        ];

        return view('pendaftaran_bantuan.index', compact('data', 'stats'));
    }

    public function create()
    {
        $warga = Warga::all();
        $program = ProgramBantuan::all();
        return view('pendaftaran_bantuan.create', compact('warga', 'program'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_name' => 'required|string|max:100',
            'warga_id' => 'required|exists:warga,warga_id',
            'program_id' => 'required|exists:program_bantuans,program_id',
            'tanggal_daftar' => 'required|date',
            'status' => 'required|in:Diproses,Diterima,Ditolak',
            'keterangan' => 'nullable|string',
        ], [
            'warga_id.required' => 'Nama warga harus dipilih dari Data Warga. Tambah warga baru jika belum ada.',
            'warga_id.exists' => 'Nama warga tidak ditemukan pada Data Warga.',
        ]);

        PendaftarBantuan::create(collect($validated)->only([
            'warga_id',
            'program_id',
            'tanggal_daftar',
            'status',
            'keterangan',
        ])->toArray());

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = PendaftarBantuan::findOrFail($id);
        $warga = Warga::all();
        $program = ProgramBantuan::all();

        return view('pendaftaran_bantuan.edit', compact('data', 'warga', 'program'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'warga_name' => 'required|string|max:100',
            'warga_id' => 'required|exists:warga,warga_id',
            'program_id' => 'required|exists:program_bantuans,program_id',
            'tanggal_daftar' => 'required|date',
            'status' => 'required|in:Diproses,Diterima,Ditolak',
            'keterangan' => 'nullable|string',
        ], [
            'warga_id.required' => 'Nama warga dipilih dari Data Warga. Tambah warga baru jika belum ada.',
            'warga_id.exists' => 'Nama warga tidak ditemukan pada Data Warga.',
        ]);

        $data = PendaftarBantuan::findOrFail($id);
        $data->update(collect($validated)->only([
            'warga_id',
            'program_id',
            'tanggal_daftar',
            'status',
            'keterangan',
        ])->toArray());

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        PendaftarBantuan::destroy($id);

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data berhasil dihapus');
    }
}