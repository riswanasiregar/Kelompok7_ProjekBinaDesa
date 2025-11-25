<?php

namespace App\Http\Controllers;

use App\Models\ProgramBantuan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramBantuanController extends Controller {
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
    * Form tambah program.
    */

    public function create() {
        return view( 'admin.program_bantuan.create' );
    }

    /**
    * Simpan program baru.
    */

    public function store( Request $request ) {
        $validated = $request->validate( [
            'kode'         => 'required|string|max:20|unique:program_bantuan,kode',
            'nama_program' => 'required|string|max:255',
            'tahun'        => 'required|digits:4|integer|min:2000|max:' . ( now()->year + 1 ),
            'deskripsi'    => 'nullable|string',
            'anggaran'     => 'required|numeric|min:0',

            'media'        => 'nullable|file|max:5120',
            'caption'      => 'nullable|string|max:255',
        ] );

        // simpan program
        $program = ProgramBantuan::create( $validated );

        // simpan media jika ada
        if ( $request->hasFile( 'media' ) ) {
            $file = $request->file( 'media' );
            $path = $file->store( 'uploads/program', 'public' );

            Media::create( [
                'ref_table' => 'program_bantuan',
                'ref_id'    => $program->program_id,
                'file_url'  => $path,
                'caption'   => $request->caption,
                'mime_type' => $file->getClientMimeType(),
                'sort_order' => 1
            ] );
        }

        return redirect()->route( 'program_bantuan.index' )
        ->with( 'success', 'Program berhasil ditambahkan.' );
    }

    /**
    * Form edit program.
    */

    public function edit( ProgramBantuan $program_bantuan ) {
        $program = $program_bantuan;
        // alias agar konsisten

        return view( 'admin.program_bantuan.edit', compact( 'program' ) );
    }

    /**
    * Update program bantuan.
    */

    public function update( Request $request, ProgramBantuan $program_bantuan ) {
        $program = $program_bantuan;

        $validated = $request->validate( [
            'kode'         => 'required|string|max:20|unique:program_bantuan,kode,' . $program->program_id . ',program_id',
            'nama_program' => 'required|string|max:255',
            'tahun'        => 'required|digits:4|integer|min:2000|max:' . ( now()->year + 1 ),
            'deskripsi'    => 'nullable|string',
            'anggaran'     => 'required|numeric|min:0',

            'media'        => 'nullable|file|max:5120',
            'caption'      => 'nullable|string|max:255',
        ] );

        // update program
        $program->update( $validated );

        // upload media baru jika ada
        if ( $request->hasFile( 'media' ) ) {

            $file = $request->file( 'media' );
            $path = $file->store( 'uploads/program', 'public' );

            Media::create( [
                'ref_table'  => 'program_bantuan',
                'ref_id'     => $program->program_id,
                'file_url'   => $path,
                'caption'    => $request->caption,
                'mime_type'  => $file->getClientMimeType(),
                'sort_order' => Media::where( 'ref_table', 'program_bantuan' )
                ->where( 'ref_id', $program->program_id )
                ->max( 'sort_order' ) + 1,
            ] );
        }

        return redirect()->route( 'program_bantuan.index' )
        ->with( 'success', 'Program berhasil diperbarui.' );
    }

    /**
    * Hapus program beserta media.
    */

    public function destroy( $id ) {
        $program = ProgramBantuan::findOrFail( $id );

        foreach ( $program->media as $m ) {
            if ( Storage::disk( 'public' )->exists( $m->file_url ) ) {
                Storage::disk( 'public' )->delete( $m->file_url );
            }
            $m->delete();
        }

        $program->delete();

        return redirect()->route( 'program_bantuan.index' )
        ->with( 'success', 'Program berhasil dihapus.' );
    }

    /**
    * Menampilkan detail satu program.
    */

    public function show( $id ) {
        $ProgramBantuan = ProgramBantuan::with( [ 'media', 'pendaftar', 'penerima', 'penyaluran' ] )
        ->findOrFail( $id );

        return view( 'admin.program_bantuan.show', compact( 'ProgramBantuan' ) );
    }
}
