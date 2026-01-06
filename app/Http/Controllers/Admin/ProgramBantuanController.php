<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramBantuan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgramBantuanController extends Controller
{
    /**
     * =========================
     * INDEX
     * =========================
     */
    public function index(Request $request)
    {
        $searchableColumns = ['nama_program', 'tahun', 'deskripsi', 'anggaran'];

        $query = ProgramBantuan::with(['media' => function ($q) {
            $q->where('ref_table', 'program_bantuan')
              ->orderBy('sort_order');
        }])->orderBy('tahun', 'desc');

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        return view('admin.program_bantuan.index', [
            'ProgramBantuan' => $query->paginate(5)->withQueryString(),
            'tahun_list'     => ProgramBantuan::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun'),
        ]);
    }

    /**
     * =========================
     * CREATE
     * =========================
     */
    public function create()
    {
        return view('admin.program_bantuan.create');
    }

    /**
     * =========================
     * STORE (MULTI UPLOAD)
     * =========================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'         => 'required|string|max:20|unique:program_bantuan,kode',
            'nama_program' => 'required|string|max:255',
            'tahun'        => 'required|digits:4|integer|min:2000|max:' . (now()->year + 1),
            'deskripsi'    => 'nullable|string',
            'anggaran'     => 'required|numeric|min:0',

            'media.*'      => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'caption'      => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $validated) {

            $program = ProgramBantuan::create($validated);

            if ($request->hasFile('media')) {

                $order = 1;

                foreach ($request->file('media') as $file) {

                    $path = $file->store('program_bantuan', 'public');

                    Media::create([
                        'ref_table'  => 'program_bantuan',
                        'ref_id'     => $program->program_id,
                        'file_url'   => $path,
                        'caption'    => $request->caption,
                        'mime_type'  => $file->getClientMimeType(),
                        'sort_order' => $order++,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.program_bantuan.index')
            ->with('success', 'Program bantuan berhasil ditambahkan.');
    }

    /**
     * =========================
     * EDIT
     * =========================
     */
    public function edit(ProgramBantuan $program_bantuan)
    {
        $program = $program_bantuan->load(['media' => function ($q) {
            $q->where('ref_table', 'program_bantuan')
              ->orderBy('sort_order');
        }]);

        return view('admin.program_bantuan.edit', compact('program'));
    }

    /**
     * =========================
     * UPDATE (TANPA HAPUS MEDIA LAMA)
     * =========================
     */
    public function update(Request $request, ProgramBantuan $program_bantuan)
    {
        $validated = $request->validate([
            'kode'         => 'required|string|max:20|unique:program_bantuan,kode,' . $program_bantuan->program_id . ',program_id',
            'nama_program' => 'required|string|max:255',
            'tahun'        => 'required|digits:4|integer|min:2000|max:' . (now()->year + 1),
            'deskripsi'    => 'nullable|string',
            'anggaran'     => 'required|numeric|min:0',

            'media.*'      => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        DB::transaction(function () use ($request, $program_bantuan, $validated) {

            $program_bantuan->update($validated);

            if ($request->hasFile('media')) {

                $order = Media::where('ref_table', 'program_bantuan')
                              ->where('ref_id', $program_bantuan->program_id)
                              ->max('sort_order') ?? 0;

                foreach ($request->file('media') as $file) {

                    $order++;

                    $path = $file->store('program_bantuan', 'public');

                    Media::create([
                        'ref_table'  => 'program_bantuan',
                        'ref_id'     => $program_bantuan->program_id,
                        'file_url'   => $path,
                        'caption'    => $file->getClientOriginalName(),
                        'mime_type'  => $file->getClientMimeType(),
                        'sort_order' => $order,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.program_bantuan.index')
            ->with('success', 'Program bantuan berhasil diperbarui.');
    }

    /**
     * =========================
     * DELETE SINGLE MEDIA (AJAX)
     * =========================
     */
    public function deleteMedia($programId, $mediaId)
    {
        $media = Media::where('media_id', $mediaId)
            ->where('ref_table', 'program_bantuan')
            ->where('ref_id', $programId)
            ->firstOrFail();

        if (Storage::disk('public')->exists($media->file_url)) {
            Storage::disk('public')->delete($media->file_url);
        }

        $media->delete();

        return response()->json(['success' => true]);
    }

    /**
     * =========================
     * DESTROY PROGRAM + MEDIA
     * =========================
     */
    public function destroy(ProgramBantuan $program_bantuan)
    {
        DB::transaction(function () use ($program_bantuan) {

            foreach ($program_bantuan->media as $media) {

                if (Storage::disk('public')->exists($media->file_url)) {
                    Storage::disk('public')->delete($media->file_url);
                }

                $media->delete();
            }

            $program_bantuan->delete();
        });

        return redirect()
            ->route('admin.program_bantuan.index')
            ->with('success', 'Program bantuan berhasil dihapus.');
    }

    /**
     * =========================
     * SHOW DETAIL
     * =========================
     */
    public function show(ProgramBantuan $program_bantuan)
    {
        $ProgramBantuan = $program_bantuan->load([
            'media' => function ($q) {
                $q->where('ref_table', 'program_bantuan')->orderBy('sort_order');
            },
            'pendaftar',
            'penerima',
            'penyaluran'
        ]);

        return view('admin.program_bantuan.show', compact('ProgramBantuan'));
    }
}
