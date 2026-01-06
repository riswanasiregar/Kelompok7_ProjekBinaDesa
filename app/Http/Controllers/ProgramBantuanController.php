<?php

namespace App\Http\Controllers;

use App\Models\ProgramBantuan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramBantuanController extends Controller
{
    // Halaman utama - tampilkan semua program bantuan
    public function index()
    {
        {
        // Ambil data program beserta media (eager loading)
        $data = ProgramBantuan::with('media')
            ->orderByDesc('created_at')
            ->paginate(6);

        return view('
        program_bantuan.index', compact('data'));
    }

        // Ambil semua program bantuan milik user yang login
        $data = ProgramBantuan::orderByDesc('created_at')
            ->paginate(6);

        return view('program_bantuan.index', compact('data'));
    }

    // Halaman form tambah program bantuan baru
    public function create()
    {
        return view('program_bantuan.create');
    }

    // Simpan program bantuan baru ke database
    public function store(Request $request)
    {
        // Cek apakah semua input sudah diisi dengan benar
        $request->validate([
            'kode' => 'required|unique:program_bantuans',
            'nama_program' => 'required',
            'tahun' => 'required|digits:4',
            'anggaran' => 'required|numeric',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        // Simpan data program bantuan ke database
        $program = ProgramBantuan::create([
            'kode' => $request->kode,
            'nama_program' => $request->nama_program,
            'tahun' => $request->tahun,
            'deskripsi' => $request->deskripsi, // Tambah deskripsi yang hilang
            'anggaran' => $request->anggaran,
        ]);

        // Jika ada foto yang diupload
        if ($request->hasFile('media')) {
            $file = $request->file('media');

            // Buat nama file yang unik
            $namaFile = time() . '_' . $file->getClientOriginalName();

            // Pastikan folder ada
            $folderPath = storage_path('app/public/program_bantuan');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            // Simpan file dengan cara yang sederhana
            $file->move($folderPath, $namaFile);

            // Simpan info foto ke database
            Media::create([
                'ref_table' => 'program_bantuan',
                'ref_id' => $program->program_id,
                'file_path' => 'program_bantuan/' . $namaFile,
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->route('program_bantuan.index')
            ->with('success', 'Program bantuan berhasil ditambahkan');
    }

    // Halaman form edit program bantuan


    // Update data program bantuan
    public function update(Request $request, $id)
    {


        // Cek apakah semua input sudah diisi dengan benar
        $request->validate([
            'kode' => 'required|unique:program_bantuans,kode,' . $id . ',program_id',
            'nama_program' => 'required',
            'tahun' => 'required|digits:4',
            'anggaran' => 'required|numeric',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        // Update data program bantuan
        $program->update([
            'kode' => $request->kode,
            'nama_program' => $request->nama_program,
            'tahun' => $request->tahun,
            'deskripsi' => $request->deskripsi, // Tambah deskripsi yang hilang
            'anggaran' => $request->anggaran,
        ]);

        // Jika ada foto baru yang diupload
        if ($request->hasFile('media')) {
            // Hapus foto lama jika ada
            $fotoLama = Media::where('ref_table', 'program_bantuan')
                ->where('ref_id', $program->program_id)
                ->first();

            if ($fotoLama) {
                // Hapus file fisik
                $pathLama = storage_path('app/public/' . $fotoLama->file_path);
                if (file_exists($pathLama)) {
                    unlink($pathLama);
                }
                $fotoLama->delete();
            }

            // Simpan foto baru
            $file = $request->file('media');
            $namaFile = time() . '_' . $file->getClientOriginalName();

            // Pastikan folder ada
            $folderPath = storage_path('app/public/program_bantuan');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            // Simpan file dengan cara yang sederhana
            $file->move($folderPath, $namaFile);

            Media::create([
                'ref_table' => 'program_bantuan',
                'ref_id' => $program->program_id,
                'file_path' => 'program_bantuan/' . $namaFile,
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->route('program_bantuan.index')
            ->with('success', 'Data program bantuan berhasil diupdate');
    }

    // Halaman detail program bantuan
    public function show($id)
    {
        return redirect()->route('program_bantuan.index');
    }
}
