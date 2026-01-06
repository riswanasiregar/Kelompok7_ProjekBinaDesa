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
            <li class="breadcrumb-item"><a href="{{ route('admin.verifikasi_lapangan.index') }}">Verifikasi Lapangan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Verifikasi Lapangan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Edit Data Verifikasi Lapangan</h1>
            <p class="mb-0">Form untuk mengedit data verifikasi lapangan</p>
        </div>
        <div>
            <a href="{{ route('admin.verifikasi_lapangan.index') }}"
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
                <form action="{{ route('admin.verifikasi_lapangan.update', $verifikasi->verifikasi_id) }}" method="POST" enctype="multipart/form-data">
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
                                    value="{{ old('petugas', $verifikasi->petugas) }}"
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
                                    value="{{ old('tanggal', $verifikasi->tanggal->format('Y-m-d')) }}"
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
                                    value="{{ old('skor', $verifikasi->skor) }}"
                                    min="0"
                                    max="100"
                                    oninput="updateSkorPreview(this.value)"
                                    required>
                                <label for="skor">Skor (0-100) *</label>
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
                                                    $badgeClass = 'bg-success';
                                                } elseif($skor >= 70) {
                                                    $kategori = 'Baik';
                                                    $badgeClass = 'bg-info';
                                                } elseif($skor >= 55) {
                                                    $kategori = 'Cukup';
                                                    $badgeClass = 'bg-warning';
                                                } else {
                                                    $kategori = 'Kurang';
                                                    $badgeClass = 'bg-danger';
                                                }
                                            @endphp
                                            <span id="kategoriBadge" class="badge rounded-pill {{ $badgeClass }}">{{ $kategori }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Info Kategori Skor --}}
                            <div class="alert alert-info">
                                <div class="small fw-medium mb-2"><i class="fas fa-info-circle me-2"></i>Kategori Skor:</div>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-success">85-100: Sangat Baik</span>
                                    <span class="badge bg-info">70-84: Baik</span>
                                    <span class="badge bg-warning">55-69: Cukup</span>
                                    <span class="badge bg-danger">0-54: Kurang</span>
                                </div>
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
                                    rows="6"
                                    placeholder="Masukkan catatan hasil verifikasi lapangan...">{{ old('catatan', $verifikasi->catatan) }}</textarea>
                                @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Media yang Sudah Ada --}}
                            @if($verifikasi->media && $verifikasi->media->count() > 0)
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-images me-2"></i>Media Saat Ini ({{ $verifikasi->media->count() }} file)
                                </label>
                                <div id="existingMediaContainer">
                                    @foreach($verifikasi->media as $media)
                                        <div class="media-item border rounded p-3 mb-2 bg-light" id="media-{{ $media->media_id }}">
                                            <div class="d-flex align-items-center gap-3">
                                                {{-- Preview --}}
                                                <div>
                                                    @if(in_array(strtolower(pathinfo($media->file_url, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                        <img src="{{ asset('storage/' . $media->file_url) }}"
                                                             alt="{{ $media->caption }}"
                                                             style="width: 80px; height: 80px; object-fit: cover;"
                                                             class="rounded">
                                                    @else
                                                        <div class="bg-white rounded p-3 text-center border" style="width: 80px; height: 80px;">
                                                            <i class="fas fa-file-pdf text-danger fs-3"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Info --}}
                                                <div class="flex-grow-1">
                                                    <p class="mb-1 fw-bold">{{ basename($media->file_url) }}</p>
                                                    <small class="text-muted">{{ $media->caption ?? 'No caption' }}</small>
                                                </div>

                                                {{-- Action --}}
                                                <div>
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="deleteMedia({{ $verifikasi->verifikasi_id }}, {{ $media->media_id }})">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Upload File Baru (Multiple) --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-upload me-2"></i>Upload Media Baru (Opsional)
                                </label>
                                <input type="file"
                                    name="media[]"
                                    class="form-control @error('media.*') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    multiple
                                    id="mediaInput">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Format: JPG, PNG, PDF (Maks. 5MB per file). Bisa upload beberapa file sekaligus.
                                </div>
                                @error('media.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Preview File Baru --}}
                            <div id="filePreview" class="mb-4" style="display: none;">
                                <label class="form-label fw-bold">File yang akan ditambahkan:</label>
                                <div id="fileList" class="border rounded p-2 bg-light"></div>
                            </div>

                            {{-- Caption Media --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text"
                                    class="form-control @error('caption') is-invalid @enderror"
                                    id="caption"
                                    name="caption"
                                    placeholder="Tulis caption untuk file baru"
                                    value="{{ old('caption') }}">
                                <label for="caption">Keterangan File Baru (Opsional)</label>
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
                                <a href="{{ route('admin.verifikasi_lapangan.index') }}"
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
.media-item {
    transition: all 0.3s ease;
}
.media-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
#fileList .file-item {
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
#fileList .file-item i {
    color: #696cff;
}
</style>

<script>
// Update kategori skor real-time
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
        badgeClass = 'bg-success';
    } else if (skor >= 70) {
        kategori = 'Baik';
        badgeClass = 'bg-info';
    } else if (skor >= 55) {
        kategori = 'Cukup';
        badgeClass = 'bg-warning';
    } else {
        kategori = 'Kurang';
        badgeClass = 'bg-danger';
    }

    badge.textContent = kategori;
    badge.className = `badge rounded-pill ${badgeClass}`;
    preview.style.display = 'block';
}

// Hapus media via AJAX
function deleteMedia(verifikasiId, mediaId) {
    if (!confirm('Apakah Anda yakin ingin menghapus file ini?')) {
        return;
    }

    fetch(`/admin/verifikasi-lapangan/${verifikasiId}/media/${mediaId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`media-${mediaId}`).remove();

            // Cek apakah masih ada media
            const container = document.getElementById('existingMediaContainer');
            if (container.children.length === 0) {
                container.parentElement.remove();
            }

            alert('Media berhasil dihapus');
        } else {
            alert('Gagal menghapus media');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus media');
    });
}

// Preview file baru yang dipilih
document.getElementById('mediaInput').addEventListener('change', function(e) {
    const filePreview = document.getElementById('filePreview');
    const fileList = document.getElementById('fileList');
    const files = e.target.files;

    if (files.length > 0) {
        filePreview.style.display = 'block';
        fileList.innerHTML = '';

        Array.from(files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';

            // Icon berdasarkan tipe file
            let icon = 'fa-file';
            if (file.type.includes('image')) {
                icon = 'fa-image';
            } else if (file.type.includes('pdf')) {
                icon = 'fa-file-pdf';
            }

            fileItem.innerHTML = `
                <i class="fas ${icon}"></i>
                <span class="flex-grow-1">${file.name}</span>
                <span class="badge bg-secondary">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
            `;

            fileList.appendChild(fileItem);
        });
    } else {
        filePreview.style.display = 'none';
        fileList.innerHTML = '';
    }
});

// Initialize preview on page load
document.addEventListener('DOMContentLoaded', function() {
    const skorInput = document.getElementById('skor');
    updateSkorPreview(skorInput.value);
});
</script>

@endsection
