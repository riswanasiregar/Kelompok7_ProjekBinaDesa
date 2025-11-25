@extends('layouts.admin.app')

@section('title', 'Tambah Program Bantuan')

@section('content')
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="#">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </a>
            </li>

            {{-- ROUTE FIX --}}
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
            <a href="{{ route('program_bantuan.index') }}"
               class="btn btn-outline-secondary d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
</div>

{{-- Error --}}
@if ($errors->any())
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>Terjadi kesalahan input </strong><br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

{{-- Success --}}
@if (session('success'))
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow components-section">
            <div class="card-body">

                {{-- ROUTE FIX → harus program_bantuan.store --}}
                <form action="{{ route('program_bantuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        {{-- KOLOM 1 --}}
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Utama</h5>

                            {{-- Kode --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text"
                                    class="form-control @error('kode') is-invalid @enderror"
                                    id="kode"
                                    name="kode"
                                    placeholder="Masukkan kode program"
                                    value="{{ old('kode') }}"
                                    required>
                                <label for="kode">Kode Program *</label>
                                @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nama Program --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text"
                                    class="form-control @error('nama_program') is-invalid @enderror"
                                    id="nama_program"
                                    name="nama_program"
                                    placeholder="Masukkan nama program"
                                    value="{{ old('nama_program') }}"
                                    required>
                                <label for="nama_program">Nama Program *</label>
                                @error('nama_program')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tahun --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="number"
                                    class="form-control @error('tahun') is-invalid @enderror"
                                    id="tahun"
                                    name="tahun"
                                    placeholder="Masukkan tahun"
                                    value="{{ old('tahun') }}"
                                    min="2000"
                                    max="2100">
                                <label for="tahun">Tahun</label>
                                @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- KOLOM 2 --}}
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Detail Program</h5>

                            {{-- Anggaran --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="number"
                                    step="0.01"
                                    class="form-control @error('anggaran') is-invalid @enderror"
                                    id="anggaran"
                                    name="anggaran"
                                    placeholder="Masukkan anggaran"
                                    value="{{ old('anggaran') }}">
                                <label for="anggaran">Anggaran</label>
                                @error('anggaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
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

                            {{-- Media File --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Upload Media (Opsional)</label>
                                <input type="file" name="media" class="form-control @error('media') is-invalid @enderror">
                                @error('media')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Caption Media --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text"
                                    class="form-control @error('caption') is-invalid @enderror"
                                    id="caption"
                                    name="caption"
                                    placeholder="Tulis caption media"
                                    value="{{ old('caption') }}">
                                <label for="caption">Caption Media</label>
                                @error('caption')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('program_bantuan.index') }}"
                                   class="btn btn-outline-gray-600">
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
}
.form-floating.form-floating-outline .form-control:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.2);
}
</style>

@endsection
