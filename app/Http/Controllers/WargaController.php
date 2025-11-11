<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;

class WargaController extends Controller
{
    /**
     * Menampilkan daftar warga.
     */
    public function index()
    {
        $data['dataWarga'] = Warga::all();
        return view('admin.warga.index', $data);
    }

    /**
     * Menampilkan form tambah warga.
     */
    public function create()
    {
        return view('admin.warga.create');
    }

    /**
     * Simpan data warga baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'no_ktp' => 'required|digits:16|unique:warga,no_ktp',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|string|max:30',
            'pekerjaan' => 'required|string|max:50',
            'telp' => 'required|digits_between:10,20',
            'email' => 'required|email|max:100|unique:warga,email',
        ], [
            'no_ktp.required' => 'Nomor KTP wajib diisi.',
            'no_ktp.digits' => 'Nomor KTP harus terdiri dari 16 digit.',
            'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
            'nama.required' => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'agama.required' => 'Agama wajib diisi.',
            'pekerjaan.required' => 'Pekerjaan wajib diisi.',
            'telp.required' => 'Nomor telepon wajib diisi.',
            'email.required' => 'Email wajib diisi.',
        ]);

        // Simpan ke database
        Warga::create($validatedData);

        return redirect()->route('warga.index')
                         ->with('success', 'Penambahan Data Berhasil!');
    }

    /**
     * Menampilkan form edit data warga.
     */
    public function edit(string $id)
    {
        $data['dataWarga'] = Warga::findOrFail($id);
        return view('admin.warga.edit', $data);
    }

    /**
     * Update data warga.
     */
    public function update(Request $request, string $id)
    {
        $warga = Warga::findOrFail($id);

        // Validasi saat update (agar unique bisa dikecualikan untuk data sendiri)
        $validatedData = $request->validate([
            'no_ktp' => 'required|digits:16|unique:warga,no_ktp,' . $id,
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|string|max:30',
            'pekerjaan' => 'required|string|max:50',
            'telp' => 'required|digits_between:10,20',
            'email' => 'required|email|max:100|unique:warga,email,' . $id,
        ]);

        // Update data
        $warga->update($validatedData);

        return redirect()->route('warga.index')
                         ->with('success', 'Perubahan Data Berhasil!');
    }

    /**
     * Hapus data warga.
     */
    public function destroy(string $id)
    {
        $warga = Warga::findOrFail($id);
        $warga->delete();

        return redirect()->route('warga.index')
                         ->with('success', 'Data Berhasil Dihapus!');
    }
}
