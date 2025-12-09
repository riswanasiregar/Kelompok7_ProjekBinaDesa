@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Tambah Verifikasi Lapangan</h3>

    <form action="{{ route('verifikasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Pendaftar</label>
            <input name="pendaftar_id" class="form-control" required>
           
        </input>
        </div>

        <div class="mb-3">
            <label>Petugas</label>
            <input type="text" name="petugas" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="catatan" rows="3" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Skor (0 - 100)</label>
            <input type="number" name="skor" class="form-control" min="0" max="100" required>
        </div>

        <div class="mb-3">
            <label>Status Verifikasi</label>
            <select name="status_verifikasi" class="form-control" required>
                <option value="menunggu">Menunggu</option>
                <option value="diverifikasi">Diverifikasi</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Upload Media (Opsional)</label>
            <input type="file" name="file_media" class="form-control">
            <small class="text-muted">Format: jpg, jpeg, png, pdf (max 4MB)</small>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('verifikasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
