<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftarBantuan;
use App\Models\ProgramBantuan;
use App\Models\Warga;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PendaftarBantuanController extends Controller
{
    /**
     * =========================
     * INDEX
     * =========================
     */
    public function index(Request $request)
    {
        $query = PendaftarBantuan::with([
            'program',
            'warga',
            'media' => function ($q) {
                $q->where('ref_table', 'pendaftar_bantuan')
                  ->orderBy('sort_order');
            }
        ])->orderBy('created_at', 'desc');

        // Filter program_id
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        // Filter status_seleksi
        if ($request->filled('status_seleksi')) {
            $query->where('status_seleksi', $request->status_seleksi);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('warga', function($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%")
                       ->orWhere('no_ktp', 'like', "%{$search}%");
                })
                ->orWhereHas('program', function($q3) use ($search) {
                    $q3->where('nama_program', 'like', "%{$search}%");
                })
                ->orWhere('status_seleksi', 'like', "%{$search}%");
            });
        }

        // Pagination
        $pendaftar = $query->paginate(5)->withQueryString();
        
        // List program untuk filter
        $programs = ProgramBantuan::all();

        return view('admin.pendaftar_bantuan.index', compact('pendaftar', 'programs'));
    }

    /**
     * =========================
     * CREATE
     * =========================
     */
    public function create()
    {
        $programs = ProgramBantuan::all();
        $warga = Warga::all();

        return view('admin.pendaftar_bantuan.create', compact('programs', 'warga'));
    }

    /**
     * =========================
     * STORE (MULTI UPLOAD)
     * =========================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:program_bantuan,program_id',
            'warga_id' => 'required|exists:warga,warga_id',
            'status_seleksi' => 'nullable|in:pending,diterima,ditolak',

            'media.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,gif',
            'caption' => 'nullable|string|max:255',
        ]);

        // Cek duplikasi
        if (PendaftarBantuan::where('program_id', $validated['program_id'])
            ->where('warga_id', $validated['warga_id'])
            ->exists()
        ) {
            return back()->with('error', 'Warga sudah terdaftar pada program ini.')
                         ->withInput();
        }

        DB::transaction(function () use ($request, $validated) {
            // 1. Simpan data pendaftar
            $pendaftar = PendaftarBantuan::create([
                'program_id' => $validated['program_id'],
                'warga_id' => $validated['warga_id'],
                'status_seleksi' => $validated['status_seleksi'] ?? 'pending',
            ]);

            // 2. Simpan file jika ada
            if ($request->hasFile('media')) {
                $order = 1;

                foreach ($request->file('media') as $file) {
                    if ($file->isValid()) {
                        // Generate nama file yang unik
                        $filename = time() . '_' . rand(1000, 9999) . '_' . $file->getClientOriginalName();

                        // Simpan file
                        $path = $file->storeAs('uploads/pendaftar', $filename, 'public');

                        Media::create([
                            'ref_table' => 'pendaftar_bantuan',
                            'ref_id' => $pendaftar->pendaftar_id,
                            'file_url' => $path,
                            'caption' => $request->caption,
                            'mime_type' => $file->getClientMimeType(),
                            'sort_order' => $order++,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.pendaftar_bantuan.index')
            ->with('success', 'Pendaftar berhasil ditambahkan.');
    }

    /**
     * =========================
     * EDIT
     * =========================
     */
    public function edit(PendaftarBantuan $pendaftar_bantuan)
    {
        $pendaftar = $pendaftar_bantuan->load(['media' => function ($q) {
            $q->where('ref_table', 'pendaftar_bantuan')
              ->orderBy('sort_order');
        }]);

        $programs = ProgramBantuan::all();
        $warga = Warga::all();

        return view('admin.pendaftar_bantuan.edit', compact('pendaftar', 'programs', 'warga'));
    }

    /**
     * =========================
     * UPDATE (TANPA HAPUS MEDIA LAMA)
     * =========================
     */
    public function update(Request $request, PendaftarBantuan $pendaftar_bantuan)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:program_bantuan,program_id',
            'warga_id' => 'required|exists:warga,warga_id',
            'status_seleksi' => 'nullable|in:pending,diterima,ditolak',

            'media.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,gif',
            'caption' => 'nullable|string|max:255',
        ]);

        // Cek duplikasi (kecuali record yang sedang diedit)
        if (PendaftarBantuan::where('program_id', $validated['program_id'])
            ->where('warga_id', $validated['warga_id'])
            ->where('pendaftar_id', '!=', $pendaftar_bantuan->pendaftar_id)
            ->exists()
        ) {
            return back()->with('error', 'Warga sudah terdaftar pada program ini.')
                         ->withInput();
        }

        DB::transaction(function () use ($request, $pendaftar_bantuan, $validated) {
            // Update data pendaftar
            $pendaftar_bantuan->update([
                'program_id' => $validated['program_id'],
                'warga_id' => $validated['warga_id'],
                'status_seleksi' => $validated['status_seleksi'] ?? $pendaftar_bantuan->status_seleksi,
            ]);

            // Tambah file baru jika ada
            if ($request->hasFile('media')) {
                $order = Media::where('ref_table', 'pendaftar_bantuan')
                              ->where('ref_id', $pendaftar_bantuan->pendaftar_id)
                              ->max('sort_order') ?? 0;

                foreach ($request->file('media') as $file) {
                    if ($file->isValid()) {
                        $order++;

                        $filename = time() . '_' . rand(1000, 9999) . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('uploads/pendaftar', $filename, 'public');

                        Media::create([
                            'ref_table' => 'pendaftar_bantuan',
                            'ref_id' => $pendaftar_bantuan->pendaftar_id,
                            'file_url' => $path,
                            'caption' => $request->caption,
                            'mime_type' => $file->getClientMimeType(),
                            'sort_order' => $order,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.pendaftar_bantuan.index')
            ->with('success', 'Data pendaftar berhasil diperbarui.');
    }

    /**
     * =========================
     * DELETE SINGLE MEDIA (AJAX)
     * =========================
     */
    public function deleteMedia($pendaftarId, $mediaId)
    {
        $media = Media::where('media_id', $mediaId)
            ->where('ref_table', 'pendaftar_bantuan')
            ->where('ref_id', $pendaftarId)
            ->firstOrFail();

        if (Storage::disk('public')->exists($media->file_url)) {
            Storage::disk('public')->delete($media->file_url);
        }

        $media->delete();

        return response()->json(['success' => true]);
    }

    /**
     * =========================
     * DESTROY PENDAFTAR + MEDIA
     * =========================
     */
    public function destroy(PendaftarBantuan $pendaftar_bantuan)
    {
        DB::transaction(function () use ($pendaftar_bantuan) {
            // Hapus semua media
            foreach ($pendaftar_bantuan->media as $media) {
                if (Storage::disk('public')->exists($media->file_url)) {
                    Storage::disk('public')->delete($media->file_url);
                }
                $media->delete();
            }

            // Hapus pendaftar
            $pendaftar_bantuan->delete();
        });

        return redirect()
            ->route('admin.pendaftar_bantuan.index')
            ->with('success', 'Pendaftar berhasil dihapus.');
    }

    /**
     * =========================
     * SHOW DETAIL
     * =========================
     */
    public function show(PendaftarBantuan $pendaftar_bantuan)
    {
        $pendaftar = $pendaftar_bantuan->load([
            'program',
            'warga',
            'media' => function ($q) {
                $q->where('ref_table', 'pendaftar_bantuan')
                  ->orderBy('sort_order');
            }
        ]);

        return view('admin.pendaftar_bantuan.show', compact('pendaftar'));
    }
}
