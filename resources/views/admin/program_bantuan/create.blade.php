@extends('layouts.admin.app')

@section('title', 'Tambah Program Bantuan')
@section('content')
<div class="py-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center w-100 flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-2">Tambah Data Program Bantuan</h1>
        </div>
        <div>
            <a href="{{ route('admin.program_bantuan.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
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

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow components-section">
            <div class="card-body">

                <form action="{{ route('admin.program_bantuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h5 class="fw-bold text-gray-800 mb-4">Data Program Bantuan</h5>

                    {{-- Kode --}}
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text"
                            class="form-control @error('kode') is-invalid @enderror"
                            id="kode"
                            name="kode"
                            placeholder="Masukkan kode program"
                            value="{{ old('kode') }}"
                            required>
                        <label for="kode">Kode Program <span class="text-danger">*</span></label>
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
                        <label for="nama_program">Nama Program <span class="text-danger">*</span></label>
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
                            max="{{ date('Y') + 1 }}">
                        <label for="tahun">Tahun</label>
                        @error('tahun')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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

                    {{-- MULTIPLE MEDIA UPLOAD dengan Ikon --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Upload Media</label>

                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </span>
                            <input type="file"
                                   name="media[]"
                                   class="form-control @error('media.*') is-invalid @enderror"
                                   multiple
                                   id="mediaUpload">
                        </div>

                        @error('media.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <small class="text-muted d-block mt-1">Format: JPG, PNG, JPEG, GIF. Maksimal 5MB per file.</small>

                    </div>

                    {{-- Caption --}}
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

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.program_bantuan.index') }}"
                           class="btn btn-outline-gray-600">
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
}
.form-floating.form-floating-outline .form-control:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.2);
}
.file-item {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 4px 8px;
    margin: 2px;
    font-size: 12px;
    display: inline-block;
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('mediaUpload');
    const fileInfo = document.getElementById('fileInfo');

    fileInput.addEventListener('change', function(e) {
        const files = e.target.files;

        if (files.length > 0) {
            let fileListHTML = '';

            for (let i = 0; i < Math.min(files.length, 3); i++) {
                const file = files[i];
                fileListHTML += `<span class="file-item">${file.name}</span>`;
            }

            if (files.length > 3) {
                fileListHTML += `<span class="file-item">+${files.length - 3} file</span>`;
            }

            fileInfo.innerHTML = `
                <div>
                    <small class="text-success">
                        <i class="fas fa-check-circle me-1"></i>
                        ${files.length} file dipilih
                    </small>
                    <div class="mt-1">${fileListHTML}</div>
                </div>
            `;
        } else {
            fileInfo.innerHTML = '<small class="text-muted">Belum ada file dipilih</small>';
        }
    });
});
</script>
@endpush
