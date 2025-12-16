<?php

namespace App\Http\Controllers;

use App\Models\PenerimaBantuan;
use App\Models\ProgramBantuan;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaBantuanController extends Controller
{
    /**
     * Tampilkan daftar penerima bantuan.
     */
    public function index(Request $request)
    {
        $filterableColumns = ['program_id'];
        $searchableColumns = ['keterangan'];

        $query = PenerimaBantuan::with(['program', 'warga', 'penyaluran']);

        // Filterable columns
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->$column);
            }
        }

        // Filter Status
        if ($request->filled('status')) {
            if ($request->status == 'sudah_menerima') {
                $query->sudahMenerima();
            } elseif ($request->status == 'belum_menerima') {
                $query->belumMenerima();
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

        // Filter by warga name (through relationship)
        if ($request->filled('warga_nama')) {
            $query->whereHas('warga', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->warga_nama . '%');
            });
        }

        // Order & pagination
        $penerima = $query->orderBy('created_at', 'desc')
                         ->paginate(10)
                         ->withQueryString();

        $program = ProgramBantuan::all();
        $warga = Warga::all();

        return view('penerima.index', compact('penerima', 'program', 'warga'));
    }

    /**
     * Form tambah penerima.
     */
    public function create()
    {
        $program = ProgramBantuan::all();
        $warga = Warga::all();

        return view('penerima.create', compact('program', 'warga'));
    }

    /**
     * Simpan data penerima baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:program_bantuans,program_id',
            'warga_id' => 'required|exists:warga,warga_id',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:Sudah Menerima,Belum Menerima'
        ]);

        // Cek apakah warga sudah terdaftar sebagai penerima dalam program yang sama
        $existing = PenerimaBantuan::where('program_id', $request->program_id)
                                   ->where('warga_id', $request->warga_id)
                                   ->first();

        if ($existing) {
            return back()->with('error', 'Warga ini sudah menjadi penerima di program ini.')->withInput();
        }

        // Gunakan transaction
        DB::transaction(function () use ($validated) {
            PenerimaBantuan::create($validated);
        });

        return redirect()->route('penerima.index')->with('success', 'Penerima bantuan berhasil ditambahkan.');
    }

    /**
     * Detail penerima bantuan.
     */
    public function show(PenerimaBantuan $penerima)
    {
        $penerima->load(['program', 'warga', 'penyaluran']);
        return view('penerima.show', compact('penerima'));
    }

    /**
     * Form edit penerima bantuan.
     */
    public function edit(PenerimaBantuan $penerima)
    {
        $program = ProgramBantuan::all();
        $warga = Warga::all();

        return view('penerima.edit', compact('penerima', 'program', 'warga'));
    }

    /**
     * Update data penerima bantuan.
     */
    public function update(Request $request, PenerimaBantuan $penerima)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:program_bantuans,program_id',
            'warga_id' => 'required|exists:warga,warga_id',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:Sudah Menerima,Belum Menerima'
        ]);

        // Cek duplikasi (kecuali jika data tidak berubah)
        $existing = PenerimaBantuan::where('program_id', $request->program_id)
                                   ->where('warga_id', $request->warga_id)
                                   ->where('penerima_id', '!=', $penerima->penerima_id)
                                   ->first();

        if ($existing) {
            return back()->with('error', 'Warga ini sudah terdaftar sebagai penerima di program tersebut.')->withInput();
        }

        // Gunakan transaction
        DB::transaction(function () use ($penerima, $validated) {
            $penerima->update($validated);
        });

        return redirect()->route('penerima.index')->with('success', 'Data penerima bantuan berhasil diperbarui.');
    }

    /**
     * Hapus penerima bantuan.
     */
    public function destroy(PenerimaBantuan $penerima)
    {
        try {
            DB::transaction(function () use ($penerima) {
                // Cek apakah sudah menerima penyaluran → tidak boleh dihapus
                if ($penerima->penyaluran()->exists()) {
                    throw new \Exception('Tidak dapat menghapus penerima yang sudah memiliki riwayat penyaluran.');
                }

                $penerima->delete();
            });

            return redirect()->route('penerima.index')->with('success', 'Data penerima bantuan berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('penerima.index')
                             ->with('error', 'Gagal menghapus penerima: ' . $e->getMessage());
        }
    }
}