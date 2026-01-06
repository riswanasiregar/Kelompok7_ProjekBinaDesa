@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Edit Verifikasi Lapangan</h3>

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

    <form action="{{ route('verifikasi.update', $data->verifikasi_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Pendaftar *</label>
            <select name="pendaftar_bantuan_id" class="form-control" required>
                @foreach ($pendaftar as $p)
                    <option value="{{ $p->pendaftar_bantuan_id }}" 
                        {{ $data->pendaftar_bantuan_id == $p->pendaftar_bantuan_id ? 'selected' : '' }}>
                        {{ $p->warga->nama ?? 'Nama tidak ditemukan' }} - {{ $p->program->nama_program ?? '-' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Petugas *</label>
            <input type="text" name="petugas" class="form-control" value="{{ $data->petugas }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal *</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $data->tanggal->format('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" rows="3" class="form-control">{{ $data->catatan }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Skor (0 - 100) *</label>
            <input type="number" name="skor" class="form-control" min="0" max="100" value="{{ $data->skor }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ganti File (opsional)</label>
            <input type="file" name="media" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            <small class="form-text text-muted">Format: JPG, PNG, PDF. Maksimal 10MB.</small>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('verifikasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection