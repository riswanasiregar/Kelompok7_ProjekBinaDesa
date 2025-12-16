<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPenyaluranBantuan;
use App\Models\ProgramBantuan;
use App\Models\PenerimaBantuan;
use App\Models\Media;
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

        // DEBUG: Log untuk cek data media
        foreach($penyaluran as $item) {
            if($item->media->count() > 0) {
                foreach($item->media as $media) {
                    \Log::info("Media Debug - Penyaluran ID: {$item->penyaluran_id}, Media ID: {$media->id}, Path: {$media->file_url}, Exists: " .
                        (Storage::disk('public')->exists($media->file_url) ? 'Yes' : 'No'));
                }
            } else {
                \Log::info("Media Debug - Penyaluran ID: {$item->penyaluran_id}, No media found");
            }
        }

        return view('admin.riwayat_penyaluran_bantuan.index', compact('penyaluran', 'program', 'penerima'));
    }

    /**
     * Form tambah penyaluran baru.
     */
    public function create()
    {
        return view('admin.riwayat_penyaluran_bantuan.create', [
            'program' => ProgramBantuan::all(),
            'penerima' => PenerimaBantuan::with('warga')->get()
        ]);
    }

    /**
     * Simpan data penyaluran baru.
     */
    public function store(Request $request)
    {
        // Bersihkan nilai dari format rupiah jika ada
        if ($request->has('nilai')) {
            $cleanedNilai = (float) str_replace(['.', ','], '', $request->nilai);
            $request->merge(['nilai' => $cleanedNilai]);
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:program_bantuan,program_id',
            'penerima_id' => 'required|exists:penerima_bantuan,penerima_id',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0|max:999999999999999.99', // Tambah batas maksimum
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

        try {
            // Gunakan transaction
            DB::transaction(function () use ($validated, $request) {
                $penyaluran = RiwayatPenyaluranBantuan::create($validated);

                // Upload media jika ada
                if ($request->hasFile('file_media')) {
                    $file = $request->file('file_media');

                    // Generate nama file unik
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/penyaluran', $filename, 'public');

                    \Log::info("File uploaded - Path: {$path}, Original Name: {$file->getClientOriginalName()}");

                    Media::create([
                        'ref_table' => 'penyaluran_bantuan',
                        'ref_id' => $penyaluran->penyaluran_id,
                        'file_url' => $path,
                        'caption' => $request->caption ?? null,
                        'mime_type' => $file->getClientMimeType(),
                        'sort_order' => 1
                    ]);
                }
            });

            return redirect()->route('riwayat_penyaluran_bantuan.index')->with('success', 'Data penyaluran berhasil ditambahkan.');

        } catch (\Exception $e) {
            \Log::error("Store error: " . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan detail penyaluran.
     */
    public function show(RiwayatPenyaluranBantuan $riwayat_penyaluran_bantuan)
    {
        $penyaluran = $riwayat_penyaluran_bantuan->load(['program', 'penerima.warga', 'media']);

        // Debug data media
        foreach($penyaluran->media as $media) {
            \Log::info("Show Media Debug - Path: {$media->file_url}, Exists: " .
                (Storage::disk('public')->exists($media->file_url) ? 'Yes' : 'No'));
        }

        return view('admin.riwayat_penyaluran_bantuan.show', compact('penyaluran'));
    }

    /**
     * Form edit penyaluran.
     */
    public function edit(RiwayatPenyaluranBantuan $riwayat_penyaluran_bantuan)
    {
        $penyaluran = $riwayat_penyaluran_bantuan;

        // Debug data media
        foreach($penyaluran->media as $media) {
            \Log::info("Edit Media Debug - Path: {$media->file_url}, Exists: " .
                (Storage::disk('public')->exists($media->file_url) ? 'Yes' : 'No'));
        }

        return view('admin.riwayat_penyaluran_bantuan.edit', [
            'penyaluran' => $penyaluran,
            'program' => ProgramBantuan::all(),
            'penerima' => PenerimaBantuan::with('warga')->get()
        ]);
    }

    /**
     * Update penyaluran.
     */
    public function update(Request $request, RiwayatPenyaluranBantuan $riwayat_penyaluran_bantuan)
    {
        $penyaluran = $riwayat_penyaluran_bantuan;

        // Bersihkan nilai dari format rupiah jika ada
        if ($request->has('nilai')) {
            $cleanedNilai = (float) str_replace(['.', ','], '', $request->nilai);
            $request->merge(['nilai' => $cleanedNilai]);
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:program_bantuan,program_id',
            'penerima_id' => 'required|exists:penerima_bantuan,penerima_id',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0|max:999999999999999.99',
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

        try {
            // Gunakan transaction
            DB::transaction(function () use ($penyaluran, $validated, $request) {
                $penyaluran->update($validated);

                // Jika media baru diupload
                if ($request->hasFile('file_media')) {
                    // Hapus media lama
                    foreach ($penyaluran->media as $media) {
                        if (Storage::disk('public')->exists($media->file_url)) {
                            Storage::disk('public')->delete($media->file_url);
                        }
                        $media->delete();
                    }

                    $file = $request->file('file_media');

                    // Generate nama file unik
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/penyaluran', $filename, 'public');

                    \Log::info("Update Media - New Path: {$path}");

                    Media::create([
                        'ref_table' => 'penyaluran_bantuan',
                        'ref_id' => $penyaluran->penyaluran_id,
                        'file_url' => $path,
                        'caption' => $request->caption ?? null,
                        'mime_type' => $file->getClientMimeType(),
                        'sort_order' => 1
                    ]);
                }
            });

            return redirect()->route('riwayat_penyaluran_bantuan.index')->with('success', 'Data penyaluran berhasil diperbarui.');

        } catch (\Exception $e) {
            \Log::error("Update error: " . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus penyaluran.
     */
    public function destroy(RiwayatPenyaluranBantuan $riwayat_penyaluran_bantuan)
    {
        $penyaluran = $riwayat_penyaluran_bantuan;

        try {
            DB::transaction(function () use ($penyaluran) {
                // Debug sebelum hapus
                foreach ($penyaluran->media as $media) {
                    \Log::info("Destroy Media - Path: {$media->file_url}, Exists: " .
                        (Storage::disk('public')->exists($media->file_url) ? 'Yes' : 'No'));
                }

                // Hapus media
                foreach ($penyaluran->media as $media) {
                    if (Storage::disk('public')->exists($media->file_url)) {
                        Storage::disk('public')->delete($media->file_url);
                    }
                    $media->delete();
                }

                // Hapus penyaluran
                $penyaluran->delete();
            });

            return redirect()->route('riwayat_penyaluran_bantuan.index')->with('success', 'Data penyaluran berhasil dihapus.');

        } catch (\Exception $e) {
            \Log::error("Destroy error: " . $e->getMessage());
            return redirect()->route('riwayat_penyaluran_bantuan.index')
                             ->with('error', 'Gagal menghapus penyaluran: ' . $e->getMessage());
        }
    }

    /**
     * Download dokumen/media.
     */
    public function downloadMedia(Media $media)
    {
        if (!Storage::disk('public')->exists($media->file_url)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($media->file_url);
    }

    /**
     * Preview gambar.
     */
    public function previewImage(Media $media)
    {
        if (!Storage::disk('public')->exists($media->file_url)) {
            abort(404, 'File tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($media->file_url);
        $mime = mime_content_type($path);

        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline'
        ]);
    }
}
