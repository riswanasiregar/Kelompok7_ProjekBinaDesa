@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Program Bantuan</h2>

    <form action="{{ route('program_bantuan.update', $data->program_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Kode Program</label>
            <input type="text" name="kode" class="form-control" required value="{{ old('kode', $data->kode) }}">
        </div>

        <div class="mb-3">
            <label>Nama Program</label>
            <input type="text" name="nama_program" class="form-control" required value="{{ old('nama_program', $data->nama_program) }}">
        </div>

        <div class="mb-3">
            <label>Tahun</label>
            <input type="number" name="tahun" class="form-control" required value="{{ old('tahun', $data->tahun) }}">
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $data->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Anggaran</label>
            <input type="number" name="anggaran" class="form-control" step="0.01" required value="{{ old('anggaran', $data->anggaran) }}">
        </div>

        <div class="mb-3">
            <label>Media Saat Ini</label><br>
            @if($data->media)
                <a href="{{ asset('storage/program_bantuan/' . $data->media) }}" target="_blank">{{ $data->media }}</a>
            @else
                <em>Tidak ada file</em>
            @endif
        </div>

        <div class="mb-3">
            <label>Ganti Media (opsional)</label>
            <input type="file" name="media" class="form-control" accept=".jpg,.png,.pdf">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('program_bantuan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
