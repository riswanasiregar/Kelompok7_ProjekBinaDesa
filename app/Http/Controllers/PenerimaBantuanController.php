<?php

namespace App\Http\Controllers;

use App\Models\PenerimaBantuan;
use App\Models\ProgramBantuan;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenerimaBantuanController extends Controller
{
    /**
     * Tampilkan daftar penerima bantuan.
     */
    public function index(Request $request)
    {
        $query = PenerimaBantuan::with(['program', 'warga', 'penyaluran']);

        if ($request->program_id) {
            $query->byProgram($request->program_id);
        }

        if ($request->status_penerima) {
            $query->byStatus($request->status_penerima);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal_ditetapkan', [$request->start_date, $request->end_date]);
        }

        $penerima = $query->orderBy('tanggal_ditetapkan', 'desc')->paginate(15);
        $program = ProgramBantuan::all();

        return view('penerima.index', compact('penerima', 'program'));
    }

    /**
     * Form tambah penerima.
     */
    public function create()
    {
        $program = ProgramBantuan::all();
        $warga = Warga::all();

        return view('penerima.create', compact('program', 'warga'));
    }

    /**
     * Simpan data penerima baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'program_id' => 'required|exists:program_bantuan,program_id',
            'warga_id' => 'required|exists:warga,warga_id',
            'keterangan' => 'nullable|string',
            'tanggal_ditetapkan' => 'required|date',
            'status_penerima' => 'required|in:aktif,nonaktif,dibatalkan'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek apakah warga sudah terdaftar sebagai penerima dalam program yang sama
        $existing = PenerimaBantuan::where('program_id', $request->program_id)
                                   ->where('warga_id', $request->warga_id)
                                   ->first();

        if ($existing) {
            return back()->with('error', 'Warga ini sudah menjadi penerima di program ini.')->withInput();
        }

        PenerimaBantuan::create([
            'program_id' => $request->program_id,
            'warga_id' => $request->warga_id,
            'keterangan' => $request->keterangan,
            'tanggal_ditetapkan' => $request->tanggal_ditetapkan,
            'status_penerima' => $request->status_penerima
        ]);

        return redirect()->route('penerima.index')->with('success', 'Penerima bantuan berhasil ditambahkan.');
    }

    /**
     * Detail penerima bantuan.
     */
    public function show($id)
    {
        $data = PenerimaBantuan::with(['program', 'warga', 'penyaluran'])->findOrFail($id);
        return view('penerima.show', compact('data'));
    }

    /**
     * Form edit penerima bantuan.
     */
    public function edit($id)
    {
        $data = PenerimaBantuan::findOrFail($id);
        $program = ProgramBantuan::all();
        $warga = Warga::all();

        return view('penerima.edit', compact('data', 'program', 'warga'));
    }

    /**
     * Update data penerima bantuan.
     */
    public function update(Request $request, $id)
    {
        $penerima = PenerimaBantuan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'program_id' => 'required|exists:program_bantuan,program_id',
            'warga_id' => 'required|exists:warga,warga_id',
            'keterangan' => 'nullable|string',
            'tanggal_ditetapkan' => 'required|date',
            'status_penerima' => 'required|in:aktif,nonaktif,dibatalkan'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek duplikasi (kecuali jika data tidak berubah)
        $existing = PenerimaBantuan::where('program_id', $request->program_id)
                                   ->where('warga_id', $request->warga_id)
                                   ->where('penerima_id', '!=', $id)
                                   ->first();

        if ($existing) {
            return back()->with('error', 'Warga ini sudah terdaftar sebagai penerima di program tersebut.')->withInput();
        }

        $penerima->update($request->all());

        return redirect()->route('penerima.index')->with('success', 'Data penerima bantuan berhasil diperbarui.');
    }

    /**
     * Hapus penerima bantuan.
     */
    public function destroy($id)
    {
        $penerima = PenerimaBantuan::findOrFail($id);

        // Jika mau: cek apakah sudah menerima penyaluran → tidak boleh dihapus
        if ($penerima->penyaluran()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus penerima yang sudah memiliki riwayat penyaluran.');
        }

        $penerima->delete();

        return redirect()->route('penerima.index')->with('success', 'Data penerima bantuan berhasil dihapus.');
    }
}
