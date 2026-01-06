@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Tambah Verifikasi Lapangan</h3>

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

    <form action="{{ route('verifikasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Pendaftar *</label>
            <select name="pendaftar_bantuan_id" class="form-control" required>
                <option value="">Pilih pendaftar</option>
                @foreach ($pendaftar as $p)
                    <option value="{{ $p->pendaftar_bantuan_id }}" {{ old('pendaftar_bantuan_id') == $p->pendaftar_bantuan_id ? 'selected' : '' }}>
                        {{ $p->warga->nama ?? 'Nama tidak ditemukan' }} - {{ $p->program->nama_program ?? '-' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Petugas *</label>
            <input type="text" name="petugas" class="form-control" required value="{{ old('petugas') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal *</label>
            <input type="date" name="tanggal" class="form-control" required value="{{ old('tanggal') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" rows="3" class="form-control">{{ old('catatan') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Skor (0 - 100) *</label>
            <input type="number" name="skor" class="form-control" min="0" max="100" required value="{{ old('skor') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Foto/File (opsional)</label>
            <input type="file" name="media" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            <small class="form-text text-muted">Format: JPG, PNG, PDF. Maksimal 10MB.</small>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('verifikasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
