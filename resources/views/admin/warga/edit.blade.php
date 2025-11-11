@extends('layouts.admin.app')

@section('title', 'Edit Warga')

@section('content')
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="#">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('warga.index') }}">Warga</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Warga</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Edit Data Warga</h1>
            <p class="mb-0">Form untuk mengubah data warga</p>
        </div>
<div>
            <a href="{{ route('warga.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
    <i class="fas fa-arrow-left me-2"></i> Kembali
</a>
        </div>
    </div>
</div>

<!-- Pesan Error di luar card -->
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
                <form action="{{ route('warga.update', $dataWarga->warga_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Kolom 1 - Data Pribadi -->
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Pribadi</h5>

                            <!-- No KTP -->
                            <div class="form-floating form-floating-outline mb-4">
                                <input
                                    type="text"
                                    class="form-control @error('no_ktp') is-invalid @enderror"
                                    id="no_ktp"
                                    name="no_ktp"
                                    placeholder="Masukkan nomor KTP"
                                    value="{{ old('no_ktp', $dataWarga->no_ktp) }}"
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
                                    value="{{ old('nama', $dataWarga->nama) }}"
                                    required />
                                <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select
                                    id="jenis_kelamin"
                                    name="jenis_kelamin"
                                    class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $dataWarga->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki
                                    </option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $dataWarga->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
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
                                    value="{{ old('agama', $dataWarga->agama) }}" />
                                <label for="agama">Agama</label>
                                @error('agama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Kolom 2 - Kontak & Pekerjaan -->
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Kontak & Pekerjaan</h5>

                            <!-- Pekerjaan -->
                            <div class="form-floating form-floating-outline mb-4">
                                <input
                                    type="text"
                                    class="form-control @error('pekerjaan') is-invalid @enderror"
                                    id="pekerjaan"
                                    name="pekerjaan"
                                    placeholder="Masukkan pekerjaan"
                                    value="{{ old('pekerjaan', $dataWarga->pekerjaan) }}" />
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
                                    value="{{ old('email', $dataWarga->email) }}" />
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
                                    value="{{ old('telp', $dataWarga->telp) }}" />
                                <label for="telp">No. Telepon</label>
                                @error('telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('warga.index') }}" class="btn btn-outline-gray-600">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
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
