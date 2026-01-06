@extends('layouts.admin.app')

@section('title', 'Edit Pendaftar Bantuan')

@section('content')
<div class="py-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center w-100 flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-2">Edit Data Pendaftar Bantuan</h1>
        </div>
        <div>
            <a href="{{ route('admin.pendaftar_bantuan.index') }}" class="btn btn-outline-secondary">
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

{{-- Success --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow components-section">
            <div class="card-body">

                <form action="{{ route('admin.pendaftar_bantuan.update', $pendaftar->pendaftar_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h5 class="fw-bold text-gray-800 mb-4">Data Pendaftar Bantuan</h5>

                    {{-- Pilih Warga --}}
                    <div class="form-floating form-floating-outline mb-4">
                        <select
                            id="warga_id"
                            name="warga_id"
                            class="form-select @error('warga_id') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Warga --</option>
                            @foreach($warga as $w)
                                <option value="{{ $w->warga_id }}" {{ old('warga_id', $pendaftar->warga_id) == $w->warga_id ? 'selected' : '' }}>
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
                                <option value="{{ $program->program_id }}" {{ old('program_id', $pendaftar->program_id) == $program->program_id ? 'selected' : '' }}>
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
                            <option value="pending" {{ old('status_seleksi', $pendaftar->status_seleksi) == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diterima" {{ old('status_seleksi', $pendaftar->status_seleksi) == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ old('status_seleksi', $pendaftar->status_seleksi) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <label for="status_seleksi">Status Seleksi</label>
                        @error('status_seleksi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- MULTIPLE MEDIA UPLOAD dengan Ikon --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Upload Berkas Pendaftaran</label>

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

                        <small class="text-muted d-block mt-1">Format: JPG, PNG, JPEG, GIF, PDF. Maksimal 5MB per file.</small>

                        <div class="mt-2" id="fileInfo">
                            <small class="text-muted">Belum ada file dipilih</small>
                        </div>
                    </div>

                    {{-- Caption --}}
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text"
                            class="form-control @error('caption') is-invalid @enderror"
                            id="caption"
                            name="caption"
                            placeholder="Tulis keterangan berkas"
                            value="{{ old('caption', $pendaftar->media->first()->caption ?? '') }}">
                        <label for="caption">Keterangan Berkas</label>
                        @error('caption')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.pendaftar_bantuan.index') }}"
                           class="btn btn-outline-gray-600">
                            <i class="fas fa-times me-2"></i> Batal
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<style>
.form-floating.form-floating-outline .form-control,
.form-floating.form-floating-outline .form-select {
    border: 1px solid #d9dee3;
    border-radius: 0.375rem;
}
.form-floating.form-floating-outline .form-control:focus,
.form-floating.form-floating-outline .form-select:focus {
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

    if (fileInput) {
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
    }
});
</script>
@endpush
