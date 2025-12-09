@extends('layouts.admin.app')

@section('title', 'Tambah Pendaftar Bantuan')

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
            <li class="breadcrumb-item"><a href="{{ route('pendaftar_bantuan.index') }}">Pendaftar Bantuan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Pendaftar Bantuan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Tambah Data Pendaftar Bantuan</h1>
            <p class="mb-0">Form untuk menambahkan data pendaftar bantuan baru</p>
        </div>
        <div>
            <a href="{{ route('pendaftar_bantuan.index') }}"
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
                <form action="{{ route('pendaftar_bantuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        {{-- KOLOM KIRI --}}
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Warga & Program</h5>

                            {{-- Pilih Warga --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <select
                                    id="warga_id"
                                    name="warga_id"
                                    class="form-select @error('warga_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Warga --</option>
                                    @foreach($warga as $w)
                                        <option value="{{ $w->warga_id }}" {{ old('warga_id') == $w->warga_id ? 'selected' : '' }}>
                                            {{ $w->nama }}
                                            @if($w->no_ktp)
                                                - {{ $w->no_ktp }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <label for="warga_id">Nama Warga <span class="text-danger">*</span></label>
                                @error('warga_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pilih Program Bantuan --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <select
                                    id="program_id"
                                    name="program_id"
                                    class="form-select @error('program_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Program Bantuan --</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->program_id }}" {{ old('program_id') == $program->program_id ? 'selected' : '' }}>
                                            {{ $program->nama_program }} ({{ $program->tahun }})
                                        </option>
                                    @endforeach
                                </select>
                                <label for="program_id">Program Bantuan <span class="text-danger">*</span></label>
                                @error('program_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status Seleksi --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <select
                                    id="status_seleksi"
                                    name="status_seleksi"
                                    class="form-select @error('status_seleksi') is-invalid @enderror">
                                    <option value="pending" {{ old('status_seleksi', 'pending') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="diterima" {{ old('status_seleksi') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="ditolak" {{ old('status_seleksi') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                <label for="status_seleksi">Status Seleksi</label>
                                @error('status_seleksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- KOLOM KANAN --}}
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Berkas Pendaftaran</h5>

                            {{-- Upload File --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="file"
                                    name="file_media"
                                    class="form-control @error('file_media') is-invalid @enderror"
                                    placeholder="Upload berkas">
                                <label for="file_media">Upload Berkas (Opsional)</label>
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
                                    placeholder="Tulis caption berkas"
                                    value="{{ old('caption') }}">
                                <label for="caption">Keterangan Berkas</label>
                                @error('caption')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <small class="text-muted">Format: JPG, PNG, PDF (Maks. 5MB)</small>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('pendaftar_bantuan.index') }}"
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
