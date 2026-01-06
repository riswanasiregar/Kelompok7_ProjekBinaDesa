<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warga;

class WargaController extends Controller
{
    /**
     * Menampilkan daftar warga dengan pagination, filter dan search.
     */
public function index(Request $request)
{
    // Kolom yang bisa dicari
    $searchableColumns = ['nama', 'no_ktp', 'jenis_kelamin', 'agama', 'pekerjaan', 'telp','email'];
    $query = Warga::query();

    // Filter jenis kelamin jika ada
    if ($request->filled('jenis_kelamin')) {
        $query->where('jenis_kelamin', $request->jenis_kelamin);
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

    // Pagination 5 per halaman & dengan query string supaya search tetap ada di page berikutnya
    $data['dataWarga'] = $query->orderBy('nama', 'asc')->paginate(5)->withQueryString();

    // Ambil list jenis kelamin untuk filter dropdown
    $data['jenis_kelamin_list'] = ['Laki-laki', 'Perempuan'];

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
        $validatedData = $request->validate([
            'no_ktp' => 'required|digits:16|unique:warga,no_ktp',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|string|max:30',
            'pekerjaan' => 'required|string|max:50',
            'telp' => 'required|digits_between:10,20',
            'email' => 'required|email|max:100|unique:warga,email',
        ]);

        Warga::create($validatedData);

        return redirect()->route('warga.index')
                         ->with('success', 'Penambahan Data Berhasil!');
    }

    /**
     * Menampilkan form edit data warga.
     */
    public function edit($id)
    {
        $data['dataWarga'] = Warga::findOrFail($id);
        return view('admin.warga.edit', $data);
    }

    /**
     * Update data warga.
     */
    public function update(Request $request, $id)
    {
        $warga = Warga::findOrFail($id);

        $validatedData = $request->validate([
            'no_ktp' => 'required|digits:16|unique:warga,no_ktp,' . $id . ',warga_id',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|string|max:30',
            'pekerjaan' => 'required|string|max:50',
            'telp' => 'required|digits_between:10,20',
            'email' => 'required|email|max:100|unique:warga,email,' . $id . ',warga_id',
        ]);

        $warga->update($validatedData);

        return redirect()->route('warga.index')
                         ->with('success', 'Perubahan Data Berhasil!');
    }

    /**
     * Hapus data warga.
     */
    public function destroy($id)
    {
        $warga = Warga::findOrFail($id);
        $warga->delete();

        return redirect()->route('warga.index')
                         ->with('success', 'Data Berhasil Dihapus!');
    }
}
