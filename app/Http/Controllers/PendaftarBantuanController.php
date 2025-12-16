<?php

namespace App\Http\Controllers;

use App\Models\PendaftarBantuan;
use App\Models\ProgramBantuan;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftarBantuanController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = PendaftarBantuan::with(['warga', 'program'])
            ->when(!Auth::user()->isAdmin(), function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest('tanggal_daftar');

        $data = $dataQuery->paginate(9)->appends($request->query());

        $statsBase = PendaftarBantuan::query()
            ->when(!Auth::user()->isAdmin(), function ($query) {
                $query->where('user_id', Auth::id());
            });

        $stats = [
            'total' => $statsBase->count(),
            'diproses' => (clone $statsBase)->where('status', 'Diproses')->count(),
            'diterima' => (clone $statsBase)->where('status', 'Diterima')->count(),
            'ditolak' => (clone $statsBase)->where('status', 'Ditolak')->count(),
        ];

        return view('pendaftaran_bantuan.index', compact('data', 'stats'));
    }

    public function create()
    {
        $warga = Warga::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        $program = ProgramBantuan::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->get();
        return view('pendaftaran_bantuan.create', compact('warga', 'program'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id' => 'required|exists:warga,warga_id',
            'program_id' => 'required|exists:program_bantuans,program_id',
            'tanggal_daftar' => 'required|date',
            'status' => 'required|in:Diproses,Diterima,Ditolak',
            'keterangan' => 'nullable|string',
        ]);

        if (!Auth::user()->isAdmin()) {
            $validWarga = Warga::where('user_id', Auth::id())->where('warga_id', $validated['warga_id'])->exists();
            $validProgram = ProgramBantuan::where('user_id', Auth::id())->where('program_id', $validated['program_id'])->exists();

            if (!$validWarga || !$validProgram) {
                abort(403, 'Data tidak valid untuk akun Anda.');
            }
        }

        PendaftarBantuan::create(
            collect($validated)->only([
                'warga_id',
                'program_id',
                'tanggal_daftar',
                'status',
                'keterangan',
            ])->merge(['user_id' => Auth::id()])->toArray()
        );

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = PendaftarBantuan::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $warga = Warga::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        $program = ProgramBantuan::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('pendaftaran_bantuan.edit', compact('data', 'warga', 'program'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'warga_id' => 'required|exists:warga,warga_id',
            'program_id' => 'required|exists:program_bantuans,program_id',
            'tanggal_daftar' => 'required|date',
            'status' => 'required|in:Diproses,Diterima,Ditolak',
            'keterangan' => 'nullable|string',
        ]);

        if (!Auth::user()->isAdmin()) {
            $validWarga = Warga::where('user_id', Auth::id())->where('warga_id', $validated['warga_id'])->exists();
            $validProgram = ProgramBantuan::where('user_id', Auth::id())->where('program_id', $validated['program_id'])->exists();

            if (!$validWarga || !$validProgram) {
                abort(403, 'Data tidak valid untuk akun Anda.');
            }
        }

        $data = PendaftarBantuan::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);
        $data->update(collect($validated)->only([
            'warga_id',
            'program_id',
            'tanggal_daftar',
            'status',
            'keterangan',
        ])->toArray());

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $data = PendaftarBantuan::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $data->delete();

        return redirect()->route('pendaftar-bantuan.index')
            ->with('success', 'Data berhasil dihapus');
    }
}