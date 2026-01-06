@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="fw-semibold mb-4">Edit Data Warga</h4>

    <form action="{{ route('admin.warga.update', $warga->warga_id) }}" method="POST" class="card p-4 shadow-sm border-0" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>No KTP</label>
            <input type="text" name="no_ktp" class="form-control" value="{{ old('no_ktp', $warga->no_ktp) }}" required>
        </div>
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $warga->nama) }}" required>
        </div>
        <div class="mb-3">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select">
                <option value="">Pilih</option>
                <option value="Laki-laki" {{ old('jenis_kelamin', $warga->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin', $warga->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Agama</label>
            <input type="text" name="agama" class="form-control" value="{{ old('agama', $warga->agama) }}">
        </div>
        <div class="mb-3">
            <label>Pekerjaan</label>
            <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan', $warga->pekerjaan) }}">
        </div>
        <div class="mb-3">
            <label>No Telepon</label>
            <input type="text" name="telp" class="form-control" value="{{ old('telp', $warga->telp) }}">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $warga->email) }}">
        </div>
        <div class="mb-3">
            <label>Foto Profil</label>
            @if($warga->profile_picture)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$warga->profile_picture) }}" alt="Foto {{ $warga->nama }}" class="rounded" style="max-width: 120px;">
                </div>
            @endif
            <input type="file" name="profile_picture" class="form-control">
            <small class="text-muted">Format: jpg, jpeg, png (maks 2MB)</small>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.warga.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
