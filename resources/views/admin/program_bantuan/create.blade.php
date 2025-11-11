@extends('layouts.admin.app')

@section('title', 'Tambah Program Bantuan')

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
            <li class="breadcrumb-item"><a href="{{ route('program_bantuan.index') }}">Program Bantuan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Program Bantuan</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Tambah Data Program Bantuan</h1>
            <p class="mb-0">Form untuk menambahkan data program bantuan baru</p>
        </div>
        <div>
            <a href="{{ route('program_bantuan.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
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
                <form action="{{ route('program_bantuan.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Kolom 1 - Data Utama -->
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Utama</h5>

                            <!-- Kode -->
                            <div class="form-floating form-floating-outline mb-4">
                                <input
                                    type="text"
                                    class="form-control @error('kode') is-invalid @enderror"
                                    id="kode"
                                    name="kode"
                                    placeholder="Masukkan kode program"
                                    value="{{ old('kode') }}"
                                    required />
                                <label for="kode">Kode Program <span class="text-danger">*</span></label>
                                @error('kode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nama Program -->
<div class="form-floating form-floating-outline mb-4">
    <input
        type="text"
        class="form-control @error('nama_program') is-invalid @enderror"
        id="nama_program"
        name="nama_program"
        placeholder="Masukkan nama program"
        value="{{ old('nama_program') }}"
        required />
    <label for="nama_program">Nama Program <span class="text-danger">*</span></label>
    @error('nama_program')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                            <!-- Tahun -->
                            <div class="form-floating form-floating-outline mb-4">
                                <input
                                    type="number"
                                    class="form-control @error('tahun') is-invalid @enderror"
                                    id="tahun"
                                    name="tahun"
                                    placeholder="Masukkan tahun"
                                    value="{{ old('tahun') }}"
                                    min="2000"
                                    max="2100" />
                                <label for="tahun">Tahun</label>
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Kolom 2 - Detail Program -->
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Detail Program</h5>

                            <!-- Anggaran -->
                            <div class="form-floating form-floating-outline mb-4">
                                <input
                                    type="number"
                                    step="0.01"
                                    class="form-control @error('anggaran') is-invalid @enderror"
                                    id="anggaran"
                                    name="anggaran"
                                    placeholder="Masukkan anggaran"
                                    value="{{ old('anggaran') }}" />
                                <label for="anggaran">Anggaran</label>
                                @error('anggaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-4">
                                <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                                <textarea
                                    class="form-control @error('deskripsi') is-invalid @enderror"
                                    id="deskripsi"
                                    name="deskripsi"
                                    rows="4"
                                    placeholder="Masukkan deskripsi program">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('program_bantuan.index') }}" class="btn btn-outline-gray-600">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Simpan Data
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
