@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Program Bantuan</h2>

    <!-- Tampilkan error -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- untuk edit data -->
    <form action="{{ route('program_bantuan.update', $data->program_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Kode Program *</label>
            <input type="text" name="kode" class="form-control" required value="{{ old('kode', $data->kode) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Program *</label>
            <input type="text" name="nama_program" class="form-control" required value="{{ old('nama_program', $data->nama_program) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Tahun *</label>
            <input type="number" name="tahun" class="form-control" required value="{{ old('tahun', $data->tahun) }}" min="2020" max="2030">
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $data->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Anggaran *</label>
            <input type="number" name="anggaran" class="form-control" step="0.01" required value="{{ old('anggaran', $data->anggaran) }}" min="0">
        </div>

        <div class="mb-3">
            <label class="form-label">Ganti Foto (opsional)</label>
            <input type="file" name="media" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            <small class="form-text text-muted">Format: JPG, PNG, PDF. Maksimal 10MB.</small>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('program_bantuan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
