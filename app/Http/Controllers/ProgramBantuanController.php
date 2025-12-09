<?php

namespace App\Http\Controllers;

use App\Models\ProgramBantuan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProgramBantuanController extends Controller
{
    /**
     * Menampilkan semua program bantuan.
     */
    public function index(Request $request)
    {
        $filterableColumns = ['nama_program'];
        $searchableColumns = ['nama_program', 'tahun', 'deskripsi', 'anggaran'];

        $query = ProgramBantuan::with('media')->filter($request, $filterableColumns)
                            ->orderBy('tahun', 'desc');

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->tahun($request->tahun);
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

        // Pagination
        $pageData['ProgramBantuan'] = $query->paginate(5)->withQueryString();

        // Tahun list
        $pageData['tahun_list'] = ProgramBantuan::select('tahun')
                                               ->distinct()
                                               ->orderBy('tahun', 'desc')
                                               ->pluck('tahun');

        return view('admin.program_bantuan.index', $pageData);
    }

    /**
     * Menampilkan form tambah program.
     */
    public function create()
    {
        return view('admin.program_bantuan.create');
    }

    /**
     * Simpan program baru (MULTIPLE UPLOAD).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'         => 'required|string|max:20|unique:program_bantuan,kode',
            'nama_program' => 'required|string|max:255',
            'tahun'        => 'required|digits:4|integer|min:2000|max:' . (now()->year + 1),
            'deskripsi'    => 'nullable|string',
            'anggaran'     => 'required|numeric|min:0',

            // MULTIPLE FILE
            'media.*'      => 'nullable|file|max:5120',
            'caption'      => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $request) {

            $program = ProgramBantuan::create($validated);

            // MULTIPLE UPLOAD
            if ($request->hasFile('media')) {
                $sort = 1;

                foreach ($request->file('media') as $file) {
                    $path = $file->store('uploads/program', 'public');

                    Media::create([
                        'ref_table'  => 'program_bantuan',
                        'ref_id'     => $program->program_id,
                        'file_url'   => $path,
                        'caption'    => $request->caption,
                        'mime_type'  => $file->getClientMimeType(),
                        'sort_order' => $sort++
                    ]);
                }
            }
        });

        return redirect()->route('program_bantuan.index')
                         ->with('success', 'Program berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit program.
     */
    public function edit(ProgramBantuan $program_bantuan)
{
    $program = $program_bantuan->load('media');
    return view('admin.program_bantuan.edit', compact('program'));
}

public function deleteMedia($programId, $mediaId)
{
    $media = Media::where('media_id', $mediaId)
                  ->where('ref_table', 'program_bantuan')
                  ->where('ref_id', $programId)
                  ->first();

    if (!$media) {
        return response()->json(['success' => false], 404);
    }

    // Hapus file di storage
    if (Storage::disk('public')->exists($media->file_url)) {
        Storage::disk('public')->delete($media->file_url);
    }

    $media->delete();

    return response()->json(['success' => true]);
}

    /**
     * Update program bantuan (MULTIPLE UPLOAD TANPA HAPUS LAMA).
     */
    public function update(Request $request, ProgramBantuan $program_bantuan)
{
    $program = $program_bantuan;


    $validated = $request->validate([
        'kode'         => 'required|string|max:20|unique:program_bantuan,kode,' . $program->program_id . ',program_id',
        'nama_program' => 'required|string|max:255',
        'tahun'        => 'required|digits:4|integer|min:2000|max:' . (now()->year + 1),
        'deskripsi'    => 'nullable|string',
        'anggaran'     => 'required|numeric|min:0',
        'files.*'      => 'nullable|file|max:5120',
    ]);

    DB::transaction(function () use ($program, $validated, $request) {
        $program->update($validated);

        if ($request->hasFile('files')) {

           
            $startOrder = Media::where('ref_table', 'program_bantuan')
                               ->where('ref_id', $program->program_id)
                               ->max('sort_order') ?? 0;

            foreach ($request->file('files') as $file) {

                $startOrder++; // increment urutan

                // Simpan file
                $path = $file->store('program_bantuan', 'public');

                // Simpan ke tabel media
                Media::create([
                    'ref_table'  => 'program_bantuan',
                    'ref_id'     => $program->program_id,
                    'file_url'   => $path,
                    'caption'    => $file->getClientOriginalName(), // nama file
                    'mime_type'  => $file->getClientMimeType(),
                    'sort_order' => $startOrder,
                ]);
            }
        }

    });

    return redirect()
        ->route('program_bantuan.index', $program->program_id)
        ->with('success', 'Program berhasil diperbarui.');
}


    /**
     * Hapus program beserta media.
     */
    public function destroy(ProgramBantuan $program_bantuan)
    {
        try {
            DB::transaction(function () use ($program_bantuan) {

                // Hapus media fisik dan database
                foreach ($program_bantuan->media as $media) {
                    if (Storage::disk('public')->exists($media->file_url)) {
                        Storage::disk('public')->delete($media->file_url);
                    }
                    $media->delete();
                }

                $program_bantuan->delete();
            });

            return redirect()->route('program_bantuan.index')
                             ->with('success', 'Program berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('program_bantuan.index')
                             ->with('error', 'Gagal menghapus program: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail satu program.
     */
    public function show(ProgramBantuan $program_bantuan)
    {
        $ProgramBantuan = $program_bantuan->load(['media', 'pendaftar', 'penerima', 'penyaluran']);
        return view('admin.program_bantuan.show', compact('ProgramBantuan'));
    }
}
