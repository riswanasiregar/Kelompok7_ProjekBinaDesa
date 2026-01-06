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

        $query = VerifikasiLapangan::with(['pendaftar', 'media']);

        // Filterable columns
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, 'like', '%' . $request->$column . '%');
            }
        }

        // Filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->periode($request->start_date, $request->end_date);
        }

        // Filter skor minimal
        if ($request->filled('skor_min')) {
            $query->skorMin($request->skor_min);
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
        $pendaftar = PendaftarBantuan::with('warga')->get();
        return view('admin.verifikasi_lapangan.create', compact('pendaftar'));
    }

    /**
     * Simpan verifikasi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendaftar_id' => 'required|exists:pendaftar_bantuan,pendaftar_id',
            'petugas' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'skor' => 'required|integer|min:0|max:100',
            'file_media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'caption' => 'nullable|string|max:255'
        ]);

        // Gunakan transaction
        DB::transaction(function () use ($validated, $request) {
            // Simpan data
            $verifikasi = VerifikasiLapangan::create($validated);

            // Upload media jika ada
            if ($request->hasFile('file_media')) {
                $file = $request->file('file_media');
                $path = $file->store('uploads/verifikasi', 'public');

                Media::create([
                    'ref_table' => 'verifikasi_lapangan',
                    'ref_id' => $verifikasi->verifikasi_id,
                    'file_url' => $path,
                    'caption' => $request->caption ?? null,
                    'mime_type' => $file->getClientMimeType(),
                    'sort_order' => 1
                ]);
            }
        });

        // PERBAIKAN: Hapus prefix admin.
        return redirect()->route('verifikasi_lapangan.index')
            ->with('success', 'Verifikasi berhasil ditambahkan.');
    }

    /**
     * Detail verifikasi.
     */
    public function show(VerifikasiLapangan $verifikasi_lapangan)
    {
        $verifikasi = $verifikasi_lapangan->load(['pendaftar.warga', 'pendaftar.program', 'media']);
        return view('admin.verifikasi_lapangan.show', compact('verifikasi'));
    }

    /**
     * Form edit.
     */
    public function edit(VerifikasiLapangan $verifikasi_lapangan)
    {
        $verifikasi = $verifikasi_lapangan;
        $pendaftar = PendaftarBantuan::with('warga')->get();

        return view('admin.verifikasi_lapangan.edit', compact('verifikasi', 'pendaftar'));
    }


    /**
     * Update verifikasi.
     */


              /**
 * Update verifikasi.
 */
/**
 * Update verifikasi.
 */
public function update(Request $request, VerifikasiLapangan $verifikasi_lapangan)
{
    $verifikasi = $verifikasi_lapangan;

    $validated = $request->validate([
        'pendaftar_id' => 'required|exists:pendaftar_bantuan,pendaftar_id',
        'petugas' => 'required|string|max:100',
        'tanggal' => 'required|date',
        'catatan' => 'nullable|string',
        'skor' => 'required|integer|min:0|max:100',
        'file_media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        'caption' => 'nullable|string|max:255'
    ]);

    try {
        DB::transaction(function () use ($verifikasi, $validated, $request) {
            // Update data verifikasi
            $verifikasi->update($validated);

            // Jika media baru diupload
            if ($request->hasFile('file_media')) {
                // Hapus media lama - AMAN
                $existingMedia = $verifikasi->media;

                foreach ($existingMedia as $media) {
                    // Simpan file_url sebelum menghapus
                    $fileUrl = $media->file_url;

                    // Hapus record dari database
                    $media->delete();

                    // Hapus file fisik dari storage
                    if (Storage::disk('public')->exists($fileUrl)) {
                        Storage::disk('public')->delete($fileUrl);
                    }
                }

                // Upload file baru
                $file = $request->file('file_media');
                $path = $file->store('uploads/verifikasi', 'public');

                Media::create([
                    'ref_table' => 'verifikasi_lapangan',
                    'ref_id' => $verifikasi->verifikasi_id,
                    'file_url' => $path,
                    'caption' => $request->caption ?? null,
                    'mime_type' => $file->getClientMimeType(),
                    'sort_order' => 1
                ]);
            }
        });

        return redirect()->route('verifikasi_lapangan.index')
            ->with('success', 'Data verifikasi berhasil diperbarui.');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
            ->withInput();
    }
}
    public function destroy(VerifikasiLapangan $verifikasi_lapangan)
    {
        $verifikasi = $verifikasi_lapangan;

        try {
            DB::transaction(function () use ($verifikasi) {
                // Hapus media
                foreach ($verifikasi->media as $media) {
                    if (Storage::disk('public')->exists($media->file_url)) {
                        Storage::disk('public')->delete($media->file_url);
                    }
                    $media->delete();
                }

                $verifikasi->delete();
            });

            return redirect()->route('verifikasi_lapangan.index')
                ->with('success', 'Data verifikasi berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('verifikasi_lapangan.index')
                ->with('error', 'Gagal menghapus verifikasi: ' . $e->getMessage());
        }
    }
}
