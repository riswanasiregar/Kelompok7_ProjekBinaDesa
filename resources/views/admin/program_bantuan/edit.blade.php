@extends('layouts.admin.app')

@section('title', 'Edit Program Bantuan')

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

            <li class="breadcrumb-item"><a href="{{ route('program_bantuan.index') }}">Program Bantuan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Program Bantuan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Edit Data Program Bantuan</h1>
            <p class="mb-0">Perbarui data program bantuan</p>
        </div>

        <div>
            <a href="{{ route('program_bantuan.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
</div>


{{-- Error --}}
@if ($errors->any())
<div class="alert alert-danger">
    <strong>Terjadi kesalahan:</strong>
    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="card border-0 shadow components-section">
    <div class="card-body">

        {{-- FORM UPDATE --}}
        <form action="{{ route('program_bantuan.update', $program->program_id) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- KOLOM 1 --}}
                <div class="col-xl-6">
                    <h5 class="fw-bold text-gray-800 mb-4">Data Utama</h5>

                    {{-- Kode --}}
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" name="kode" id="kode"
                               class="form-control @error('kode') is-invalid @enderror"
                               value="{{ old('kode', $program->kode) }}" required>
                        <label for="kode">Kode Program *</label>
                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" name="nama_program" id="nama_program"
                               class="form-control @error('nama_program') is-invalid @enderror"
                               value="{{ old('nama_program', $program->nama_program) }}" required>
                        <label for="nama_program">Nama Program *</label>
                        @error('nama_program')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tahun --}}
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="number" name="tahun" id="tahun"
                               class="form-control @error('tahun') is-invalid @enderror"
                               value="{{ old('tahun', $program->tahun) }}" min="2000" max="2100">
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
                        <input type="number" name="anggaran" id="anggaran"
                               step="0.01"
                               class="form-control @error('anggaran') is-invalid @enderror"
                               value="{{ old('anggaran', $program->anggaran) }}">
                        <label for="anggaran">Anggaran</label>
                        @error('anggaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                                  class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $program->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- AMBIL MEDIA RELASI --}}
                    @php
                        $media = $program->media->first() ?? null;
                    @endphp

                    {{-- Media Saat Ini --}}
                    @if ($media)
                    <div class="mb-3">
                        <p class="fw-bold">Media Saat Ini:</p>
                        <img src="{{ asset('storage/'.$media->file_url) }}"
                             class="img-thumbnail mb-2"
                             style="max-height: 160px">

                        <p><strong>Caption:</strong> {{ $media->caption }}</p>
                    </div>
                    @endif

                    {{-- Upload Media Baru --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Upload Media Baru (Opsional)</label>
                        <input type="file" name="media"
                               class="form-control @error('media') is-invalid @enderror">
                        @error('media')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Caption Baru --}}
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" name="caption" id="caption"
                               class="form-control @error('caption') is-invalid @enderror"
                               value="{{ old('caption') }}">
                        <label for="caption">Caption Media Baru</label>
                        @error('caption')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>


            {{-- TOMBOL --}}
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('program_bantuan.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
            </div>

        </form>

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
