<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

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
    public function store(Request $request)
    {
        $request->validate([
            'ref_table' => 'required|string|max:50',
            'ref_id'    => 'required|integer',
            'file'      => 'required|file|max:5120', // max 5MB
            'caption'   => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $path = $file->store('uploads/media', 'public');

        Media::create([
            'ref_table' => $request->ref_table,
            'ref_id'    => $request->ref_id,
            'file_url'  => $path,
            'caption'   => $request->caption,
            'mime_type' => $file->getClientMimeType(),
            'sort_order' => Media::where('ref_table', $request->ref_table)
                                  ->where('ref_id', $request->ref_id)
                                  ->max('sort_order') + 1,
        ]);

        return redirect()->back()->with('success', 'Media berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit caption / sort order.
     */
    public function edit($id)
    {
        $media = Media::findOrFail($id);

        return view('admin.media.edit', compact('media'));
    }

    /**
     * Update informasi media (caption & sort_order).
     */
    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        $request->validate([
            'caption'    => 'nullable|string|max:255',
            'sort_order' => 'required|integer'
        ]);

        $media->update([
            'caption' => $request->caption,
            'sort_order' => $request->sort_order
        ]);

        return redirect()->back()->with('success', 'Media berhasil diperbarui.');
    }

    /**
     * Hapus media + file fisiknya.
     */
    public function destroy($id)
    {
        $media = Media::findOrFail($id);

        // Hapus file dari storage
        if (Storage::disk('public')->exists($media->file_url)) {
            Storage::disk('public')->delete($media->file_url);
        }

        // Hapus data di database
        $media->delete();

        return redirect()->back()->with('success', 'Media berhasil dihapus.');
    }
}
