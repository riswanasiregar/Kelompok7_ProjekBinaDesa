@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="fw-semibold mb-4">Tambah Data Warga</h4>

    <form action="{{ route('warga.store') }}" method="POST" class="card p-4 shadow-sm border-0" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>No KTP</label>
            <input type="text" name="no_ktp" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select">
                <option value="">Pilih</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Agama</label>
            <input type="text" name="agama" class="form-control">
        </div>
        <div class="mb-3">
            <label>Pekerjaan</label>
            <input type="text" name="pekerjaan" class="form-control">
        </div>
        <div class="mb-3">
            <label>No Telepon</label>
            <input type="text" name="telp" class="form-control">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label>Foto Profil</label>
            <input type="file" name="profile_picture" class="form-control">
            <small class="text-muted">Format: jpg, jpeg, png (maks 2MB)</small>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('warga.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
