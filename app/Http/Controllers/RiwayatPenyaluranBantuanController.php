<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPenyaluranBantuan;
use App\Models\ProgramBantuan;
use App\Models\PenerimaBantuan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class RiwayatPenyaluranBantuanController extends Controller
{
    // Tampilkan semua data penyaluran
    public function index(Request $request)
    {
        $penyaluran = RiwayatPenyaluranBantuan::with(['program', 'penerima.warga'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $program = ProgramBantuan::all();
        $penerima = PenerimaBantuan::with('warga')->get();

        return view('riwayat.index', compact('penyaluran', 'program', 'penerima'));
    }

    // Form tambah penyaluran baru
    public function create()
    {
        return view('riwayat.create', [
            'program' => ProgramBantuan::all(),
            'penerima' => PenerimaBantuan::with('warga')->get()
        ]);
    }

    // Simpan data penyaluran baru
    public function store(Request $request)
    {
        // Bersihkan format rupiah jika ada
        if ($request->has('nilai')) {
            $nilai = str_replace(['.', 'Rp', ' '], '', $request->nilai);
            $nilai = str_replace(',', '.', $nilai);
            $request->merge(['nilai' => $nilai]);
        }

        // Cek input yang wajib diisi
        $request->validate([
            'program_id' => 'required',
            'penerima_id' => 'required',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'tahap_ke' => 'required|integer|min:1',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'caption' => 'nullable|string|max:255'
        ]);

        // Simpan data penyaluran
        $penyaluran = RiwayatPenyaluranBantuan::create([
            'program_id' => $request->program_id,
            'penerima_id' => $request->penerima_id,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'tahap_ke' => $request->tahap_ke,
        ]);

        // Jika ada file yang diupload
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            
            // Buat nama file yang unik
            $namaFile = time() . '_' . $file->getClientOriginalName();
            
            // Pastikan folder ada
            $folderPath = storage_path('app/public/riwayat_penyaluran');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
            
            // Simpan file dengan cara yang lebih sederhana
            $file->move($folderPath, $namaFile);
            
            // Simpan info file ke database
            Media::create([
                'ref_table' => 'riwayat_penyaluran_bantuan',
                'ref_id' => $penyaluran->penyaluran_id,
                'file_path' => 'riwayat_penyaluran/' . $namaFile,
                'file_name' => $file->getClientOriginalName(),
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('riwayat.index')->with('success', 'Data penyaluran berhasil ditambahkan.');
    }

    // Lihat detail penyaluran
    public function show($id)
    {
        $penyaluran = RiwayatPenyaluranBantuan::findOrFail($id);
        $penyaluran->load(['program', 'penerima.warga']);
        return view('riwayat.show', compact('penyaluran'));
    }

    // Form edit penyaluran
    public function edit($id)
    {
        $penyaluran = RiwayatPenyaluranBantuan::findOrFail($id);
        return view('riwayat.edit', [
            'penyaluran' => $penyaluran,
            'program' => ProgramBantuan::all(),
            'penerima' => PenerimaBantuan::with('warga')->get()
        ]);
    }

    // Update data penyaluran
    public function update(Request $request, $id)
    {
        $penyaluran = RiwayatPenyaluranBantuan::findOrFail($id);

        // Bersihkan format rupiah jika ada
        if ($request->has('nilai')) {
            $nilai = str_replace(['.', 'Rp', ' '], '', $request->nilai);
            $nilai = str_replace(',', '.', $nilai);
            $request->merge(['nilai' => $nilai]);
        }

        // Cek input yang wajib diisi
        $request->validate([
            'program_id' => 'required',
            'penerima_id' => 'required',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'tahap_ke' => 'required|integer|min:1',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'caption' => 'nullable|string|max:255'
        ]);

        // Update data penyaluran
        $penyaluran->update([
            'program_id' => $request->program_id,
            'penerima_id' => $request->penerima_id,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'tahap_ke' => $request->tahap_ke,
        ]);

        // Jika ada file baru yang diupload
        if ($request->hasFile('media')) {
            // Hapus file lama jika ada
            $mediaLama = Media::where('ref_table', 'riwayat_penyaluran_bantuan')
                ->where('ref_id', $penyaluran->penyaluran_id)
                ->first();
            
            if ($mediaLama) {
                Storage::disk('public')->delete($mediaLama->file_path);
                $mediaLama->delete();
            }

            // Simpan file baru
            $file = $request->file('media');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/riwayat_penyaluran', $namaFile);
            
            Media::create([
                'ref_table' => 'riwayat_penyaluran_bantuan',
                'ref_id' => $penyaluran->penyaluran_id,
                'file_path' => 'riwayat_penyaluran/' . $namaFile,
                'file_name' => $file->getClientOriginalName(),
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('riwayat.index')->with('success', 'Data penyaluran berhasil diperbarui.');
    }

    // Hapus data penyaluran
    public function destroy($id)
    {
        $penyaluran = RiwayatPenyaluranBantuan::findOrFail($id);

        // Hapus file yang terkait
        $media = Media::where('ref_table', 'riwayat_penyaluran_bantuan')
            ->where('ref_id', $penyaluran->penyaluran_id)
            ->get();

        foreach ($media as $m) {
            Storage::disk('public')->delete($m->file_path);
            $m->delete();
        }

        $penyaluran->delete();

        return redirect()->route('riwayat.index')->with('success', 'Data penyaluran berhasil dihapus.');
    }
}