<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WargaController extends Controller
{
    public function index(Request $request){
        $filterableColumns = ['jenis_kelamin'];
        $searchableColumns = ['nama', 'no_ktp', 'pekerjaan', 'telp', 'email'];

        $wargas = Warga::query()
            ->filter($request, $filterableColumns)
            ->when($request->filled('search'), function ($query) use ($request, $searchableColumns) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search, $searchableColumns) {
                    foreach ($searchableColumns as $column) {
                        $q->orWhere($column, 'like', '%' . $search . '%');
                    }
                });
            })
            ->orderByDesc('created_at')
            ->paginate(5)
            ->appends($request->query());

        return view('warga.index', compact('wargas'));
    }

    public function create()
    {
        return view('warga.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'no_ktp' => 'required|unique:warga,no_ktp',
            'nama' => 'required',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|string|max:30',
            'pekerjaan' => 'nullable|string|max:50',
            'telp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        Warga::create($data);
        return redirect()->route('warga.index')->with('success', 'Data warga berhasil ditambahkan.');
    }

    public function edit(Warga $warga)
    {
        return view('warga.edit', compact('warga'));
    }

    public function update(Request $request, Warga $warga)
    {
        $data = $request->validate([
            'no_ktp' => 'required|unique:warga,no_ktp,' . $warga->warga_id . ',warga_id',
            'nama' => 'required',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|string|max:30',
            'pekerjaan' => 'nullable|string|max:50',
            'telp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($warga->profile_picture) {
                Storage::disk('public')->delete($warga->profile_picture);
            }
            $data['profile_picture'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        $warga->update($data);
        return redirect()->route('warga.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    public function destroy(Warga $warga)
    {
        $warga->delete();
        return redirect()->route('warga.index')->with('success', 'Data warga berhasil dihapus.');
    }
}
