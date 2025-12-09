<?php

namespace App\Http\Controllers;

use App\Models\PendaftarBantuan;
use App\Models\ProgramBantuan;
use App\Models\Warga;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PendaftarBantuanController extends Controller
{
    /**
     * Menampilkan daftar pendaftar bantuan dengan pagination, filter, dan search.
     */
    public function index(Request $request)
    {
        $filterableColumns = ['program_id', 'status_seleksi'];
        $searchableColumns = ['status_seleksi'];

        $query = PendaftarBantuan::with(['program', 'warga', 'media']);

        // Filterable columns
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->$column);
            }
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
        $pendaftar = $query->orderBy('created_at', 'desc')
                           ->paginate(5)
                           ->withQueryString();

        // List program untuk filter di view
        $programs = ProgramBantuan::all();

        return view('admin.pendaftar_bantuan.index', compact('pendaftar', 'programs'));
    }

    /**
     * Form tambah pendaftar baru.
     */
    public function create()
    {
        $programs = ProgramBantuan::all();
        $warga = Warga::all();

        return view('admin.pendaftar_bantuan.create', compact('programs', 'warga'));
    }

    /**
     * Simpan pendaftar baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:program_bantuan,program_id',
            'warga_id' => 'required|exists:warga,warga_id',
            'status_seleksi' => 'nullable|in:pending,diterima,ditolak',
            'file_media' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf',
            'caption' => 'nullable|string|max:255',
        ]);

        // Cek duplikasi
        if (PendaftarBantuan::where('program_id', $request->program_id)
            ->where('warga_id', $request->warga_id)
            ->exists()
        ) {
            return back()->with('error', 'Warga sudah terdaftar pada program ini.');
        }

        // Gunakan transaction
        DB::transaction(function () use ($validated, $request) {
            $pendaftar = PendaftarBantuan::create($validated);

            // Upload media
            if ($request->hasFile('file_media')) {
                $file = $request->file('file_media');
                $path = $file->store('uploads/pendaftar', 'public');

                Media::create([
                    'ref_table' => 'pendaftar_bantuan',
                    'ref_id' => $pendaftar->pendaftar_id,
                    'file_url' => $path,
                    'caption' => $request->caption ?? null,
                    'mime_type' => $file->getClientMimeType(),
                    'sort_order' => 1
                ]);
            }
        });

        return redirect()->route('pendaftar_bantuan.index')
            ->with('success', 'Pendaftar berhasil ditambahkan.');
    }

    /**
     * Form edit pendaftar.
     */
    public function edit(PendaftarBantuan $pendaftar_bantuan)
    {
        $pendaftar = $pendaftar_bantuan;
        $programs = ProgramBantuan::all();
        $warga = Warga::all();

        return view('admin.pendaftar_bantuan.edit', compact('pendaftar', 'programs', 'warga'));
    }

    /**
     * Update pendaftar.
     */
    public function update(Request $request, PendaftarBantuan $pendaftar_bantuan)
    {
        $pendaftar = $pendaftar_bantuan;

        $validated = $request->validate([
            'program_id' => 'required|exists:program_bantuan,program_id',
            'warga_id' => 'required|exists:warga,warga_id',
            'status_seleksi' => 'nullable|in:pending,diterima,ditolak',
            'file_media' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf',
            'caption' => 'nullable|string|max:255',
        ]);

        // Cek duplikasi update
        if (PendaftarBantuan::where('program_id', $request->program_id)
            ->where('warga_id', $request->warga_id)
            ->where('pendaftar_id', '!=', $pendaftar->pendaftar_id)
            ->exists()
        ) {
            return back()->with('error', 'Warga sudah terdaftar pada program ini.');
        }

        // Gunakan transaction
        DB::transaction(function () use ($pendaftar, $validated, $request) {
            $pendaftar->update($validated);

            // Upload media baru jika ada
            if ($request->hasFile('file_media')) {
                // Hapus media lama
                foreach ($pendaftar->media as $media) {
                    if (Storage::disk('public')->exists($media->file_url)) {
                        Storage::disk('public')->delete($media->file_url);
                    }
                    $media->delete();
                }

                $file = $request->file('file_media');
                $path = $file->store('uploads/pendaftar', 'public');

                Media::create([
                    'ref_table' => 'pendaftar_bantuan',
                    'ref_id' => $pendaftar->pendaftar_id,
                    'file_url' => $path,
                    'caption' => $request->caption ?? null,
                    'mime_type' => $file->getClientMimeType(),
                    'sort_order' => 1
                ]);
            }
        });

        return redirect()->route('pendaftar_bantuan.index')
            ->with('success', 'Data pendaftar berhasil diperbarui.');
    }

    /**
     * Hapus pendaftar beserta media.
     */
    public function destroy(PendaftarBantuan $pendaftar_bantuan)
    {
        $pendaftar = $pendaftar_bantuan;

        try {
            DB::transaction(function () use ($pendaftar) {
                foreach ($pendaftar->media as $media) {
                    if (Storage::disk('public')->exists($media->file_url)) {
                        Storage::disk('public')->delete($media->file_url);
                    }
                    $media->delete();
                }

                $pendaftar->delete();
            });

            return redirect()->route('pendaftar_bantuan.index')
                ->with('success', 'Pendaftar berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('pendaftar_bantuan.index')
                ->with('error', 'Gagal menghapus pendaftar: ' . $e->getMessage());
        }
    }
}
