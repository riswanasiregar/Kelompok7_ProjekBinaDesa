<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerifikasiLapangan;
use App\Models\PendaftarBantuan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class VerifikasiLapanganController extends Controller
{
    /**
     * Tampilkan daftar verifikasi.
     */
    public function index(Request $request)
    {
        $filterableColumns = ['petugas'];
        $searchableColumns = ['petugas', 'catatan'];

        $query = VerifikasiLapangan::with([
            'pendaftar.warga',
            'pendaftar.programBantuan', // PERBAIKAN: programBantuan bukan program
            'media' => function ($q) {
                $q->where('ref_table', 'verifikasi_lapangan')
                  ->orderBy('sort_order');
            }
        ]);

        // Filterable columns
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, 'like', '%' . $request->$column . '%');
            }
        }

        // Filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // Filter skor minimal
        if ($request->filled('skor_min')) {
            $query->where('skor', '>=', $request->skor_min);
        }

        // Filter pendaftar
        if ($request->filled('pendaftar_id')) {
            $query->where('pendaftar_id', $request->pendaftar_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        // Order & pagination
        $verifikasi = $query->orderBy('tanggal', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->paginate(10)
                           ->withQueryString();

        // Data untuk filter dropdown
        $pendaftarList = PendaftarBantuan::with('warga')->get();

        return view('admin.verifikasi_lapangan.index', compact('verifikasi', 'pendaftarList'));
    }

    /**
     * Form tambah verifikasi.
     */
    public function create()
    {
        // Get all pendaftars
        $allPendaftar = PendaftarBantuan::with(['warga', 'programBantuan'])->get(); // PERBAIKAN: programBantuan

        // Get IDs of pendaftars that already have verification
        $verifiedIds = VerifikasiLapangan::pluck('pendaftar_id')->toArray();

        // Filter out pendaftars that already have verification
        $pendaftar = $allPendaftar->reject(function ($item) use ($verifiedIds) {
            return in_array($item->pendaftar_id, $verifiedIds);
        });

        return view('admin.verifikasi_lapangan.create', compact('pendaftar'));
    }

    /**
     * Simpan verifikasi baru - DIPERBAIKI LENGKAP
     */
    public function store(Request $request)
    {
        // DEBUG: Log request
        \Log::info('=== STORE VERIFIKASI LAPANGAN DIPANGGIL ===');
        \Log::info('Request data:', $request->except(['foto']));

        if ($request->hasFile('foto')) {
            \Log::info('File ditemukan:', [
                'count' => count($request->file('foto')),
                'names' => array_map(function($f) {
                    return $f->getClientOriginalName();
                }, $request->file('foto'))
            ]);
        }

        $validated = $request->validate([
            'pendaftar_id' => 'required|exists:pendaftar_bantuan,pendaftar_id',
            'petugas' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'skor' => 'required|integer|min:0|max:100',
            'foto' => 'nullable|array', // PERBAIKAN: konsisten dengan form
            'foto.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif', // HAPUS pdf
        ]);

        \Log::info('Validasi berhasil:', $validated);

        // Cek duplikasi
        if (VerifikasiLapangan::where('pendaftar_id', $validated['pendaftar_id'])->exists()) {
            \Log::warning('Duplikasi ditemukan untuk pendaftar_id: ' . $validated['pendaftar_id']);
            return back()
                ->with('error', 'Pendaftar ini sudah memiliki verifikasi lapangan.')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // 1. Simpan data verifikasi
            $verifikasi = VerifikasiLapangan::create([
                'pendaftar_id' => $validated['pendaftar_id'],
                'petugas' => $validated['petugas'],
                'tanggal' => $validated['tanggal'],
                'catatan' => $validated['catatan'] ?? null,
                'skor' => $validated['skor'],
                'user_id' => auth()->id(), // PERBAIKAN: tambah user_id
            ]);

            \Log::info('Verifikasi berhasil dibuat:', [
                'verifikasi_id' => $verifikasi->verifikasi_id,
                'pendaftar_id' => $verifikasi->pendaftar_id,
            ]);

            // 2. Simpan file jika ada
            if ($request->hasFile('foto')) {
                $order = 1;

                foreach ($request->file('foto') as $file) {
                    if ($file->isValid()) {
                        // Generate nama file yang unik
                        $filename = 'verifikasi_' . time() . '_' . $order . '_' . $verifikasi->verifikasi_id . '.' . $file->getClientOriginalExtension();

                        // Simpan file ke folder yang benar
                        $path = $file->storeAs('verifikasi_lapangan', $filename, 'public');

                        \Log::info('Menyimpan file:', [
                            'filename' => $filename,
                            'path' => $path,
                            'original_name' => $file->getClientOriginalName(),
                        ]);

                        Media::create([
                            'ref_table' => 'verifikasi_lapangan',
                            'ref_id' => $verifikasi->verifikasi_id,
                            'file_url' => $path,
                            'caption' => $file->getClientOriginalName(), // PERBAIKAN: pakai nama file asli
                            'mime_type' => $file->getMimeType(),
                            'sort_order' => $order++,
                        ]);
                    }
                }

                \Log::info('Total media disimpan: ' . ($order - 1));
            }

            DB::commit();

            \Log::info('=== STORE BERHASIL ===');

            return redirect()->route('admin.verifikasi_lapangan.index')
                ->with('success', 'Verifikasi berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error menyimpan verifikasi:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['foto']),
            ]);

            return back()
                ->with('error', 'Gagal menyimpan verifikasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Detail verifikasi.
     */
    public function show(VerifikasiLapangan $verifikasi_lapangan)
    {
        $verifikasi = $verifikasi_lapangan->load([
            'pendaftar.warga',
            'pendaftar.programBantuan', // PERBAIKAN
            'media' => function ($q) {
                $q->where('ref_table', 'verifikasi_lapangan')
                  ->orderBy('sort_order');
            }
        ]);

        return view('admin.verifikasi_lapangan.show', compact('verifikasi'));
    }

    /**
     * Form edit.
     */
    public function edit(VerifikasiLapangan $verifikasi_lapangan)
    {
        $verifikasi = $verifikasi_lapangan->load([
            'media' => function ($q) {
                $q->where('ref_table', 'verifikasi_lapangan')
                  ->orderBy('sort_order');
            }
        ]);

        $pendaftar = PendaftarBantuan::with(['warga', 'programBantuan'])->get(); // PERBAIKAN

        return view('admin.verifikasi_lapangan.edit', compact('verifikasi', 'pendaftar'));
    }

    /**
     * Update verifikasi - DIPERBAIKI
     */
    public function update(Request $request, VerifikasiLapangan $verifikasi_lapangan)
    {
        $validated = $request->validate([
            'pendaftar_id' => 'required|exists:pendaftar_bantuan,pendaftar_id',
            'petugas' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'skor' => 'required|integer|min:0|max:100',
            'foto' => 'nullable|array', // PERBAIKAN: konsisten
            'foto.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif', // HAPUS pdf
        ]);

        // Cek duplikasi (kecuali record yang sedang diedit)
        if (VerifikasiLapangan::where('pendaftar_id', $validated['pendaftar_id'])
            ->where('verifikasi_id', '!=', $verifikasi_lapangan->verifikasi_id)
            ->exists()
        ) {
            return back()
                ->with('error', 'Pendaftar ini sudah memiliki verifikasi lapangan.')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Update data verifikasi
            $verifikasi_lapangan->update([
                'pendaftar_id' => $validated['pendaftar_id'],
                'petugas' => $validated['petugas'],
                'tanggal' => $validated['tanggal'],
                'catatan' => $validated['catatan'] ?? null,
                'skor' => $validated['skor'],
            ]);

            // Tambah file baru jika ada
            if ($request->hasFile('foto')) {
                $order = Media::where('ref_table', 'verifikasi_lapangan')
                              ->where('ref_id', $verifikasi_lapangan->verifikasi_id)
                              ->max('sort_order') ?? 0;

                foreach ($request->file('foto') as $file) {
                    if ($file->isValid()) {
                        $order++;

                        $filename = 'verifikasi_' . time() . '_' . $order . '_' . $verifikasi_lapangan->verifikasi_id . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('verifikasi_lapangan', $filename, 'public');

                        Media::create([
                            'ref_table' => 'verifikasi_lapangan',
                            'ref_id' => $verifikasi_lapangan->verifikasi_id,
                            'file_url' => $path,
                            'caption' => $file->getClientOriginalName(), // PERBAIKAN
                            'mime_type' => $file->getMimeType(),
                            'sort_order' => $order,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.verifikasi_lapangan.index')
                ->with('success', 'Data verifikasi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error update verifikasi:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->with('error', 'Gagal memperbarui verifikasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * DELETE SINGLE MEDIA (AJAX)
     */
    public function deleteMedia($verifikasiId, $mediaId)
    {
        $media = Media::where('media_id', $mediaId)
            ->where('ref_table', 'verifikasi_lapangan')
            ->where('ref_id', $verifikasiId)
            ->firstOrFail();

        // Hapus file fisik dari storage
        if (Storage::disk('public')->exists($media->file_url)) {
            Storage::disk('public')->delete($media->file_url);
        }

        // Hapus record dari database
        $media->delete();

        return response()->json(['success' => true]);
    }

    /**
     * DESTROY VERIFIKASI + MEDIA
     */
    public function destroy(VerifikasiLapangan $verifikasi_lapangan)
    {
        DB::beginTransaction();

        try {
            // Hapus semua media
            foreach ($verifikasi_lapangan->media as $media) {
                if (Storage::disk('public')->exists($media->file_url)) {
                    Storage::disk('public')->delete($media->file_url);
                }
                $media->delete();
            }

            // Hapus data verifikasi
            $verifikasi_lapangan->delete();

            DB::commit();

            return redirect()->route('admin.verifikasi_lapangan.index')
                ->with('success', 'Data verifikasi berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error menghapus verifikasi:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->with('error', 'Gagal menghapus verifikasi: ' . $e->getMessage());
        }
    }
}
