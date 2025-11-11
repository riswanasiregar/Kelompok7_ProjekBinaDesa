@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Tambah Program Bantuan</h2>

    <form action="{{ route('program_bantuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Kode Program</label>
            <input type="text" name="kode" class="form-control" required value="{{ old('kode') }}">
        </div>

        <div class="mb-3">
            <label>Nama Program</label>
            <input type="text" name="nama_program" class="form-control" required value="{{ old('nama_program') }}">
        </div>

        <div class="mb-3">
            <label>Tahun</label>
            <input type="number" name="tahun" class="form-control" required value="{{ old('tahun') }}">
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Anggaran</label>
            <input type="number" name="anggaran" class="form-control" step="0.01" required value="{{ old('anggaran') }}">
        </div>

        <div class="mb-3">
            <label>Upload Media (opsional)</label>
            <input type="file" name="media" class="form-control" accept=".jpg,.png,.pdf">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('program_bantuan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
