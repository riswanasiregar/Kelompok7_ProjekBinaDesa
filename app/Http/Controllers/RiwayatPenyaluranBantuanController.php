<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPenyaluranBantuan;
use App\Models\ProgramBantuan;
use App\Models\PenerimaBantuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RiwayatPenyaluranBantuanController extends Controller
{
    /**
     * Tampilkan semua data penyaluran dengan filter.
     */
    public function index(Request $request)
    {
        $query = RiwayatPenyaluranBantuan::with(['program', 'penerima']);

        // Filter berdasarkan request
        if ($request->program_id) {
            $query->byProgram($request->program_id);
        }

        if ($request->penerima_id) {
            $query->byPenerima($request->penerima_id);
        }

        if ($request->status_penyaluran) {
            $query->where('status_penyaluran', $request->status_penyaluran);
        }

        if ($request->metode_penyaluran) {
            $query->byMetode($request->metode_penyaluran);
        }

        if ($request->tahun) {
            $query->tahun($request->tahun);
        }

        if ($request->start_date && $request->end_date) {
            $query->periode($request->start_date, $request->end_date);
        }

        $penyaluran = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('penyaluran.index', [
            'penyaluran' => $penyaluran,
            'program' => ProgramBantuan::all(),
            'penerima' => PenerimaBantuan::all()
        ]);
    }

    /**
     * Form tambah penyaluran baru.
     */
    public function create()
    {
        return view('penyaluran.create', [
            'program' => ProgramBantuan::all(),
            'penerima' => PenerimaBantuan::all()
        ]);
    }

    /**
     * Simpan data penyaluran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:program_bantuan,program_id',
            'penerima_id' => 'required|exists:penerima_bantuan,penerima_id',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'tahap_ke' => 'nullable|integer',
            'status_penyaluran' => 'required|in:direncanakan,diberikan,dibatalkan',
            'metode_penyaluran' => 'nullable|string|max:50',
            'bukti_penyaluran' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);

        // Hitung otomatis tahap_ke jika tidak diinput
        $tahap = $request->tahap_ke ?? RiwayatPenyaluranBantuan::where('program_id', $request->program_id)
            ->where('penerima_id', $request->penerima_id)
            ->max('tahap_ke') + 1;

        $data = $request->all();
        $data['tahap_ke'] = $tahap;

        // Upload file
        if ($request->hasFile('bukti_penyaluran')) {
            $data['bukti_penyaluran'] = $request->file('bukti_penyaluran')->store('bukti_penyaluran', 'public');
        }

        RiwayatPenyaluranBantuan::create($data);

        return redirect()->route('penyaluran.index')->with('success', 'Data penyaluran berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail penyaluran.
     */
    public function show($id)
    {
        $data = RiwayatPenyaluranBantuan::with(['program', 'penerima', 'media'])->findOrFail($id);

        return view('penyaluran.show', compact('data'));
    }

    /**
     * Form edit penyaluran.
     */
    public function edit($id)
    {
        return view('penyaluran.edit', [
            'data' => RiwayatPenyaluranBantuan::findOrFail($id),
            'program' => ProgramBantuan::all(),
            'penerima' => PenerimaBantuan::all()
        ]);
    }

    /**
     * Update penyaluran.
     */
    public function update(Request $request, $id)
    {
        $data = RiwayatPenyaluranBantuan::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'status_penyaluran' => 'required|in:direncanakan,diberikan,dibatalkan',
            'metode_penyaluran' => 'nullable|string|max:50',
            'bukti_penyaluran' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);

        $updateData = $request->all();

        // Upload file baru jika ada
        if ($request->hasFile('bukti_penyaluran')) {
            if ($data->bukti_penyaluran) {
                Storage::disk('public')->delete($data->bukti_penyaluran);
            }
            $updateData['bukti_penyaluran'] = $request->file('bukti_penyaluran')->store('bukti_penyaluran', 'public');
        }

        $data->update($updateData);

        return redirect()->route('penyaluran.index')->with('success', 'Data penyaluran berhasil diperbarui.');
    }

    /**
     * Hapus penyaluran.
     */
    public function destroy($id)
    {
        $data = RiwayatPenyaluranBantuan::findOrFail($id);

        if ($data->bukti_penyaluran) {
            Storage::disk('public')->delete($data->bukti_penyaluran);
        }

        $data->delete();

        return redirect()->route('penyaluran.index')->with('success', 'Data penyaluran berhasil dihapus.');
    }
}
