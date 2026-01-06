<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MediaController extends Controller
{
    /**
     * Menampilkan semua media berdasarkan tabel referensi & ID.
     */
    public function index($refTable, $refId)
    {
        $media = Media::byReferensi($refTable, $refId)->get();

        return view('admin.media.index', [
            'media' => $media,
            'refTable' => $refTable,
            'refId' => $refId
        ]);
    }

    /**
     * Form upload media.
     */
    public function create($refTable, $refId)
    {
        return view('admin.media.create', [
            'refTable' => $refTable,
            'refId' => $refId
        ]);
    }

    /**
     * Simpan file media yang di-upload.
     */
    /**
 * Simpan program baru.
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'kode'         => 'required|string|max:20|unique:program_bantuan,kode',
        'nama_program' => 'required|string|max:255',
        'tahun'        => 'required|digits:4|integer|min:2000|max:' . (now()->year + 1),
        'deskripsi'    => 'nullable|string',
        'anggaran'     => 'required|numeric|min:0',

        // MULTIPLE FILE UPLOAD VALIDATION - PERBAIKAN
        'media_files'  => 'nullable|array',
        'media_files.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
    ]);

    // Gunakan transaction untuk atomic operation
    DB::transaction(function () use ($validated, $request) {
        // simpan program
        $program = ProgramBantuan::create($validated);

        // MULTIPLE FILE UPLOAD HANDLING - PERBAIKAN
        if ($request->hasFile('media_files')) {
            $files = [];
            $sortOrder = 1;

            foreach ($request->file('media_files') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/program', $filename, 'public');

                    $files[] = [
                        'ref_table'  => 'program_bantuan',
                        'ref_id'     => $program->program_id,
                        'file_url'   => $path,
                        'caption'    => $file->getClientOriginalName(), // Default caption
                        'mime_type'  => $file->getClientMimeType(),
                        'sort_order' => $sortOrder++,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            // Insert semua file sekaligus
            if (!empty($files)) {
                Media::insert($files);
            }
        }
    });

    return redirect()->route('program_bantuan.index')
                     ->with('success', 'Program berhasil ditambahkan dengan file upload.');
}
    public function edit(Media $media)
    {
        return view('admin.media.edit', compact('media'));
    }

    /**
     * Update informasi media (caption & sort_order).
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'caption'    => 'nullable|string|max:255',
            'sort_order' => 'required|integer'
        ]);

        $media->update($request->only(['caption', 'sort_order']));

        return redirect()->back()->with('success', 'Media berhasil diperbarui.');
    }

    /**
     * Hapus media + file fisiknya.
     */
    public function destroy(Media $media)
    {
        try {
            DB::transaction(function () use ($media) {
                // Hapus file dari storage
                if (Storage::disk('public')->exists($media->file_url)) {
                    Storage::disk('public')->delete($media->file_url);
                }

                // Hapus data di database
                $media->delete();
            });

            return redirect()->back()->with('success', 'Media berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus media: ' . $e->getMessage());
        }
    }
}