@extends('layouts.admin.app')

@section('title', 'Edit Verifikasi Lapangan')

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
            <li class="breadcrumb-item active" aria-current="page">Edit Verifikasi Lapangan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Edit Data Verifikasi Lapangan</h1>
            <p class="mb-0">Form untuk mengedit data verifikasi lapangan</p>
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
                <form action="{{ route('verifikasi_lapangan.update', $verifikasi->verifikasi_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- KOLOM KIRI --}}
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
                                            {{ old('pendaftar_id', $verifikasi->pendaftar_id) == $item->pendaftar_id ? 'selected' : '' }}>
                                            {{ $item->warga->nama }} - {{ $item->program->nama_program ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="pendaftar_id">Pendaftar Bantuan <span class="text-danger">*</span></label>
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
                                    value="{{ old('petugas', $verifikasi->petugas) }}"
                                    required>
                                <label for="petugas">Nama Petugas <span class="text-danger">*</span></label>
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
                                    value="{{ old('tanggal', $verifikasi->tanggal->format('Y-m-d')) }}"
                                    required>
                                <label for="tanggal">Tanggal Verifikasi <span class="text-danger">*</span></label>
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
                                    value="{{ old('skor', $verifikasi->skor) }}"
                                    min="0"
                                    max="100"
                                    oninput="updateSkorPreview(this.value)"
                                    required>
                                <label for="skor">Skor (0-100) <span class="text-danger">*</span></label>
                                @error('skor')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Preview Kategori Skor --}}
                            <div id="skorPreview" class="mb-4">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small fw-medium">Kategori:</span>
                                            @php
                                                $skor = old('skor', $verifikasi->skor);
                                                if($skor >= 85) {
                                                    $kategori = 'Sangat Baik';
                                                    $badgeClass = 'bg-label-success';
                                                } elseif($skor >= 70) {
                                                    $kategori = 'Baik';
                                                    $badgeClass = 'bg-label-info';
                                                } elseif($skor >= 55) {
                                                    $kategori = 'Cukup';
                                                    $badgeClass = 'bg-label-warning';
                                                } else {
                                                    $kategori = 'Kurang';
                                                    $badgeClass = 'bg-label-danger';
                                                }
                                            @endphp
                                            <span id="kategoriBadge" class="badge rounded-pill {{ $badgeClass }}">{{ $kategori }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Info Kategori Skor --}}
                            <div class="small fw-medium mb-2">Kategori Skor:</div>
                            <div class="demo-inline-spacing">
                                <span class="badge rounded-pill bg-label-success">85-100: Sangat Baik</span>
                                <span class="badge rounded-pill bg-label-info">70-84: Baik</span>
                                <span class="badge rounded-pill bg-label-warning">55-69: Cukup</span>
                                <span class="badge rounded-pill bg-label-danger">0-54: Kurang</span>
                            </div>
                        </div>

                        {{-- KOLOM KANAN --}}
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
                                    placeholder="Masukkan catatan hasil verifikasi lapangan">{{ old('catatan', $verifikasi->catatan) }}</textarea>
                                @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- Tampilkan Media Saat Ini --}}
                            @if($verifikasi->media)
                            <div class="mb-4">
                                <label class="form-label fw-bold">Berkas Saat Ini</label>
                                <div class="d-flex align-items-center gap-3 p-3 border rounded bg-light">
                                    @if($verifikasi->media->is_image)
                                        <img src="{{ $verifikasi->media->full_url }}"
                                             alt="{{ $verifikasi->media->caption }}"
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;"
                                             class="img-thumbnail">
                                    @else
                                        <div class="bg-white rounded p-2 text-center border" style="width: 60px; height: 60px;">
                                            <i class="fas fa-file text-primary fs-5"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <p class="mb-1 fw-bold">{{ $verifikasi->media->display_name }}</p>
                                        <small class="text-muted">{{ $verifikasi->media->caption }}</small>
                                    </div>
                                </div>
                                <small class="text-muted">Upload berkas baru untuk mengganti berkas saat ini</small>
                            </div>
                            @endif

                            {{-- Upload File Baru --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="file"
                                    name="file_media"
                                    class="form-control @error('file_media') is-invalid @enderror"
                                    placeholder="Upload berkas baru">
                                <label for="file_media">Upload Berkas Baru (Opsional)</label>
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
                                    value="{{ old('caption', $verifikasi->media->caption ?? '') }}">
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
                                <a href="{{ route('verifikasi_lapangan.index') }}"
                                   class="btn btn-outline-gray-600">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update Data
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

<script>
function updateSkorPreview(skor) {
    const preview = document.getElementById('skorPreview');
    const badge = document.getElementById('kategoriBadge');

    if (skor === '' || skor === null) {
        preview.style.display = 'none';
        return;
    }

    skor = parseInt(skor);
    let kategori = '';
    let badgeClass = '';

    if (skor >= 85) {
        kategori = 'Sangat Baik';
        badgeClass = 'bg-label-success';
    } else if (skor >= 70) {
        kategori = 'Baik';
        badgeClass = 'bg-label-info';
    } else if (skor >= 55) {
        kategori = 'Cukup';
        badgeClass = 'bg-label-warning';
    } else {
        kategori = 'Kurang';
        badgeClass = 'bg-label-danger';
    }

    badge.textContent = kategori;
    badge.className = `badge rounded-pill ${badgeClass}`;
    preview.style.display = 'block';
}

// Initialize preview
document.addEventListener('DOMContentLoaded', function() {
    const skorInput = document.getElementById('skor');
    updateSkorPreview(skorInput.value);
});
</script>

@endsection
