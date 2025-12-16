@extends('layouts.admin.app')

@section('title', 'Tambah Warga')
@section('content')
<div class="py-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center w-100 flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-2">Tambah  Data Warga</h1>
        </div>
        <div>
            <a href="{{ route('warga.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
@if ($errors->any())
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>Terjadi kesalahan input </strong> <br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

@if (session('success'))
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow components-section">
            <div class="card-body">
                <form action="{{ route('warga.store') }}" method="POST">
                    @csrf

                    <!-- Hanya 1 kolom -->
                    <h5 class="fw-bold text-gray-800 mb-4">Data Warga</h5>

                    <!-- No KTP -->
                    <div class="form-floating form-floating-outline mb-4">
                        <input
                            type="text"
                            class="form-control @error('no_ktp') is-invalid @enderror"
                            id="no_ktp"
                            name="no_ktp"
                            placeholder="Masukkan nomor KTP"
                            value="{{ old('no_ktp') }}"
                            required />
                        <label for="no_ktp">No. KTP <span class="text-danger">*</span></label>
                        @error('no_ktp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div class="form-floating form-floating-outline mb-4">
                        <input
                            type="text"
                            class="form-control @error('nama') is-invalid @enderror"
                            id="nama"
                            name="nama"
                            placeholder="Masukkan nama lengkap"
                            value="{{ old('nama') }}"
                            required />
                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="mb-4">
                        <select
                            id="jenis_kelamin"
                            name="jenis_kelamin"
                            class="form-select @error('jenis_kelamin') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Agama -->
                    <div class="form-floating form-floating-outline mb-4">
                        <input
                            type="text"
                            class="form-control @error('agama') is-invalid @enderror"
                            id="agama"
                            name="agama"
                            placeholder="Masukkan agama"
                            value="{{ old('agama') }}" />
                        <label for="agama">Agama</label>
                        @error('agama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pekerjaan -->
                    <div class="form-floating form-floating-outline mb-4">
                        <input
                            type="text"
                            class="form-control @error('pekerjaan') is-invalid @enderror"
                            id="pekerjaan"
                            name="pekerjaan"
                            placeholder="Masukkan pekerjaan"
                            value="{{ old('pekerjaan') }}" />
                        <label for="pekerjaan">Pekerjaan</label>
                        @error('pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-floating form-floating-outline mb-4">
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            placeholder="john@example.com"
                            value="{{ old('email') }}" />
                        <label for="email">Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Telp -->
                    <div class="form-floating form-floating-outline mb-4">
                        <input
                            type="tel"
                            class="form-control @error('telp') is-invalid @enderror"
                            id="telp"
                            name="telp"
                            placeholder="081234567890"
                            value="{{ old('telp') }}" />
                        <label for="telp">No. Telepon</label>
                        @error('telp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('warga.index') }}" class="btn btn-outline-gray-600">
                            <i class="fas fa-times me-2"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
.form-floating.form-floating-outline .form-control {
    border: 1px solid #d9dee3;
    border-radius: 0.375rem;
    transition: all 0.2s ease-in-out;
}

.form-floating.form-floating-outline .form-control:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.2);
}

.form-floating.form-floating-outline label {
    color: #6c757d;
    transition: all 0.2s ease-in-out;
}

.form-floating.form-floating-outline .form-control:focus ~ label,
.form-floating.form-floating-outline .form-control:not(:placeholder-shown) ~ label {
    color: #696cff;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    background: white;
    padding: 0 0.25rem;
    margin-left: -0.25rem;
}
</style>
@endsection
