<?php

namespace App\Http\Controllers;

use App\Models\ProgramBantuan;
use Illuminate\Http\Request;

class ProgramBantuanController extends Controller
{
    public function index()
    {
        $data = ProgramBantuan::orderByDesc('created_at')->paginate(2);
        return view('program_bantuan.index', compact('data'));
    }

    public function create()
    {
        return view('program_bantuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:program_bantuans',
            'nama_program' => 'required',
            'tahun' => 'required|digits:4',
            'anggaran' => 'required|numeric',
            'media' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);

        $input = $request->all();

        if ($request->hasFile('media')) {
            $fileName = time() . '_' . $request->file('media')->getClientOriginalName();
            $request->file('media')->storeAs('public/program_bantuan', $fileName);
            $input['media'] = $fileName;
        }

        ProgramBantuan::create($input);

        return redirect()->route('program_bantuan.index')->with('success', 'Program bantuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = ProgramBantuan::findOrFail($id);
        return view('program_bantuan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = ProgramBantuan::findOrFail($id);

        $request->validate([
            'kode' => 'required|unique:program_bantuans,kode,' . $id . ',program_id',
            'nama_program' => 'required',
            'tahun' => 'required|digits:4',
            'anggaran' => 'required|numeric',
            'media' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);

        $input = $request->all();

        if ($request->hasFile('media')) {
            $fileName = time() . '_' . $request->file('media')->getClientOriginalName();
            $request->file('media')->storeAs('public/program_bantuan', $fileName);
            $input['media'] = $fileName;
        }

        $data->update($input);

        return redirect()->route('program_bantuan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $data = ProgramBantuan::findOrFail($id);
        $data->delete();

        return redirect()->route('program_bantuan.index')->with('success', 'Data berhasil dihapus.');
    }
}

