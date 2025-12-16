<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPenyaluranBantuan;
use App\Models\ProgramBantuan;
use App\Models\PenerimaBantuan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RiwayatPenyaluranBantuanController extends Controller
{
    /**
     * Tampilkan semua data penyaluran dengan filter.
     */
    public function index(Request $request)
    {
        $filterableColumns = ['program_id', 'penerima_id'];
        $searchableColumns = [];

        $query = RiwayatPenyaluranBantuan::with(['program', 'penerima.warga', 'media']);

        // Filterable columns
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->$column);
            }
        }

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->tahun($request->tahun);
        }

        // Filter periode tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->periode($request->start_date, $request->end_date);
        }

        // Search by penerima name (through relationship)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('penerima.warga', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        // Order & pagination
        $penyaluran = $query->orderBy('tanggal', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->paginate(10)
                           ->withQueryString();

        $program = ProgramBantuan::all();
        $penerima = PenerimaBantuan::with('warga')->get();

        return view('riwayat.index', compact('penyaluran', 'program', 'penerima'));
    }

    /**
     * Form tambah penyaluran baru.
     */
    public function create()
    {
        return view('riwayat.create', [
            'program' => ProgramBantuan::all(),
            'penerima' => PenerimaBantuan::with('warga')->get()
        ]);
    }

    /**
     * Simpan data penyaluran baru.
     */
    public function store(Request $request)
    {
        // Bersihkan format rupiah (hapus titik ribuan, ganti koma desimal jadi titik) sebelum validasi
        if ($request->has('nilai')) {
            $nilai = str_replace(['.', 'Rp', ' '], '', $request->nilai);
            $nilai = str_replace(',', '.', $nilai);
            $request->merge(['nilai' => $nilai]);
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:program_bantuans,program_id',
            'penerima_id' => 'required|exists:penerima_bantuan,penerima_id',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'tahap_ke' => 'required|integer|min:1',
            'file_media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'caption' => 'nullable|string|max:255'
        ]);

        // Cek duplikasi tahap untuk penerima yang sama dalam program yang sama
        $existingTahap = RiwayatPenyaluranBantuan::where('program_id', $request->program_id)
            ->where('penerima_id', $request->penerima_id)
            ->where('tahap_ke', $request->tahap_ke)
            ->exists();

        if ($existingTahap) {
            return back()->with('error', 'Tahap penyaluran ini sudah ada untuk penerima tersebut.')->withInput();
        }

        // Gunakan transaction
        DB::transaction(function () use ($validated, $request) {
            $penyaluran = RiwayatPenyaluranBantuan::create($validated);

            // Upload media jika ada
            if ($request->hasFile('file_media')) {
                $file = $request->file('file_media');
                $path = $file->store('uploads/penyaluran', 'public');

                Media::create([
                    'ref_table' => 'riwayat_penyaluran_bantuan',
                    'ref_id' => $penyaluran->penyaluran_id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'user_id' => Auth::id(),
                ]);
            }
        });

        return redirect()->route('riwayat.index')->with('success', 'Data penyaluran berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail penyaluran.
     */
    public function show($id)
    {
        $penyaluran = RiwayatPenyaluranBantuan::findOrFail($id);
        $penyaluran->load(['program', 'penerima.warga', 'media']);
        return view('riwayat.show', compact('penyaluran'));
    }

    /**
     * Form edit penyaluran.
     */
    public function edit($id)
    {
        $penyaluran = RiwayatPenyaluranBantuan::findOrFail($id);
        return view('riwayat.edit', [
            'penyaluran' => $penyaluran,
            'program' => ProgramBantuan::all(),
            'penerima' => PenerimaBantuan::with('warga')->get()
        ]);
    }

    /**
     * Update penyaluran.
     */
    public function update(Request $request, $id)
    {
        $penyaluran = RiwayatPenyaluranBantuan::findOrFail($id);

        // Bersihkan format rupiah hapus titik ribuan, ganti koma desimal jadi titik) sebelum validasi
        if ($request->has('nilai')) {
            $nilai = str_replace(['.', 'Rp', ' '], '', $request->nilai);
            $nilai = str_replace(',', '.', $nilai);
            $request->merge(['nilai' => $nilai]);
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:program_bantuans,program_id',
            'penerima_id' => 'required|exists:penerima_bantuan,penerima_id',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'tahap_ke' => 'required|integer|min:1',
            'file_media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'caption' => 'nullable|string|max:255'
        ]);

        // Cek duplikasi tahap (kecuali data yang sedang diupdate)
        $existingTahap = RiwayatPenyaluranBantuan::where('program_id', $request->program_id)
            ->where('penerima_id', $request->penerima_id)
            ->where('tahap_ke', $request->tahap_ke)
            ->where('penyaluran_id', '!=', $penyaluran->penyaluran_id)
            ->exists();

        if ($existingTahap) {
            return back()->with('error', 'Tahap penyaluran ini sudah ada untuk penerima tersebut.')->withInput();
        }

        // Gunakan transaction
        DB::transaction(function () use ($penyaluran, $validated, $request) {
            $penyaluran->update($validated);

            // Jika media baru diupload
            if ($request->hasFile('file_media')) {
                // Hapus media lama
                foreach ($penyaluran->media as $media) {
                    if (Storage::disk('public')->exists($media->file_path)) {
                        Storage::disk('public')->delete($media->file_path);
                    }
                    $media->delete();
                }

                $file = $request->file('file_media');
                $path = $file->store('uploads/penyaluran', 'public');

                Media::create([
                    'ref_table' => 'riwayat_penyaluran_bantuan',
                    'ref_id' => $penyaluran->penyaluran_id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'user_id' => Auth::id(),
                ]);
            }
        });

        return redirect()->route('riwayat.index')->with('success', 'Data penyaluran berhasil diperbarui.');
    }

    /**
     * Hapus penyaluran.
     */
    public function destroy($id)
    {
        $penyaluran = RiwayatPenyaluranBantuan::findOrFail($id);

        try {
            DB::transaction(function () use ($penyaluran) {
                // Hapus media
                foreach ($penyaluran->media as $media) {
                    if (Storage::disk('public')->exists($media->file_path)) {
                        Storage::disk('public')->delete($media->file_path);
                    }
                    $media->delete();
                }

                // Hapus penyaluran
                $penyaluran->delete();
            });

            return redirect()->route('riwayat.index')->with('success', 'Data penyaluran berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('riwayat.index')
                             ->with('error', 'Gagal menghapus penyaluran: ' . $e->getMessage());
        }
    }
}