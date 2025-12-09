@extends('layouts.admin.app')

@section('title', 'Tambah Verifikasi Lapangan')

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
            <li class="breadcrumb-item"><a href="{{ route('verifikasi_lapangan.index') }}">Verifikasi Lapangan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Verifikasi Lapangan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Tambah Data Verifikasi Lapangan</h1>
            <p class="mb-0">Form untuk menambahkan data verifikasi lapangan baru</p>
        </div>
        <div>
            <a href="{{ route('verifikasi_lapangan.index') }}"
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
                <form action="{{ route('verifikasi_lapangan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        {{-- KOLOM 1 --}}
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Verifikasi</h5>

                            {{-- Pendaftar --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <select class="form-control @error('pendaftar_id') is-invalid @enderror"
                                        id="pendaftar_id"
                                        name="pendaftar_id"
                                        required>
                                    <option value="">Pilih Pendaftar</option>
                                    @foreach($pendaftar as $item)
                                        <option value="{{ $item->pendaftar_id }}"
                                            {{ old('pendaftar_id') == $item->pendaftar_id ? 'selected' : '' }}>
                                            {{ $item->warga->nama }} - {{ $item->program->nama_program ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="pendaftar_id">Pendaftar Bantuan *</label>
                                @error('pendaftar_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Petugas --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text"
                                    class="form-control @error('petugas') is-invalid @enderror"
                                    id="petugas"
                                    name="petugas"
                                    placeholder="Masukkan nama petugas"
                                    value="{{ old('petugas') }}"
                                    required>
                                <label for="petugas">Nama Petugas *</label>
                                @error('petugas')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tanggal --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="date"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    id="tanggal"
                                    name="tanggal"
                                    value="{{ old('tanggal') }}"
                                    required>
                                <label for="tanggal">Tanggal Verifikasi *</label>
                                @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Skor --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="number"
                                    class="form-control @error('skor') is-invalid @enderror"
                                    id="skor"
                                    name="skor"
                                    placeholder="Masukkan skor"
                                    value="{{ old('skor') }}"
                                    min="0"
                                    max="100"
                                    required>
                                <label for="skor">Skor (0-100) *</label>
                                @error('skor')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- KOLOM 2 --}}
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Detail & Media</h5>

                            {{-- Catatan --}}
                            <div class="mb-4">
                                <label for="catatan" class="form-label fw-bold">Catatan Verifikasi</label>
                                <textarea
                                    class="form-control @error('catatan') is-invalid @enderror"
                                    id="catatan"
                                    name="catatan"
                                    rows="4"
                                    placeholder="Masukkan catatan hasil verifikasi lapangan">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- File Media --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Upload Bukti Verifikasi (Opsional)</label>
                                <input type="file"
                                    name="file_media"
                                    class="form-control @error('file_media') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.pdf">
                                <div class="form-text">Format: JPG, PNG, PDF (Maks. 4MB)</div>
                                @error('file_media')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Caption Media --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text"
                                    class="form-control @error('caption') is-invalid @enderror"
                                    id="caption"
                                    name="caption"
                                    placeholder="Tulis caption untuk file bukti"
                                    value="{{ old('caption') }}">
                                <label for="caption">Keterangan File</label>
                                @error('caption')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                          {{-- Info Kategori Skor --}}
        <div class="small fw-medium mb-1">Kategori Skor:</div>
        <div class="demo-inline-spacing">
            <span class="badge rounded-pill bg-label-success">85-100: Sangat Baik</span>
            <span class="badge rounded-pill bg-label-info">70-84: Baik</span>
            <span class="badge rounded-pill bg-label-warning">55-69: Cukup</span>
            <span class="badge rounded-pill bg-label-danger">0-54: Kurang</span>
        </div>




                    {{-- Tombol --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('verifikasi_lapangan.index') }}"
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
