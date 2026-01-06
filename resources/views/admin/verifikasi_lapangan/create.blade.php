@extends('layouts.admin.app')
@section('title', 'Tambah Verifikasi Lapangan')
@section('content')
<div class="py-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center w-100 flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-2">Tambah Verifikasi Lapangan</h1>
        </div>
        <div>
            <a href="{{ route('admin.verifikasi_lapangan.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

{{-- Success Message --}}
@if (session('success'))
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

{{-- Error Message --}}
@if ($errors->any())
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
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
    <div class="col-12 mb-4">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.verifikasi_lapangan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- KOLOM KIRI -->
                        <div class="col-md-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Verifikasi</h5>

                            <!-- Pendaftar -->
                            <div class="mb-4">
                                <label for="pendaftar_id" class="form-label fw-bold">Pendaftar Bantuan *</label>
                                <select class="form-select @error('pendaftar_id') is-invalid @enderror"
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
                                @error('pendaftar_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Petugas -->
                            <div class="mb-4">
                                <label for="petugas" class="form-label fw-bold">Nama Petugas *</label>
                                <input type="text"
                                    class="form-control @error('petugas') is-invalid @enderror"
                                    id="petugas"
                                    name="petugas"
                                    placeholder="Masukkan nama petugas"
                                    value="{{ old('petugas') }}"
                                    required>
                                @error('petugas')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal -->
                            <div class="mb-4">
                                <label for="tanggal" class="form-label fw-bold">Tanggal Verifikasi *</label>
                                <input type="date"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    id="tanggal"
                                    name="tanggal"
                                    value="{{ old('tanggal', date('Y-m-d')) }}"
                                    required>
                                @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Skor -->
                            <div class="mb-4">
                                <label for="skor" class="form-label fw-bold">Skor (0-100) *</label>
                                <input type="number"
                                    class="form-control @error('skor') is-invalid @enderror"
                                    id="skor"
                                    name="skor"
                                    placeholder="Masukkan skor"
                                    value="{{ old('skor') }}"
                                    min="0"
                                    max="100"
                                    required>
                                @error('skor')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Info Kategori Skor -->
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

                        <!-- KOLOM KANAN -->
                        <div class="col-md-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Detail & Media</h5>

                            <!-- Catatan -->
                            <div class="mb-4">
                                <label for="catatan" class="form-label fw-bold">Catatan Verifikasi</label>
                                <textarea
                                    class="form-control @error('catatan') is-invalid @enderror"
                                    id="catatan"
                                    name="catatan"
                                    rows="4"
                                    placeholder="Masukkan catatan hasil verifikasi lapangan...">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Media (Multiple Upload) -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-upload me-2"></i>Upload Bukti Verifikasi (Opsional)
                                </label>
                                <input type="file"
                                    name="media[]"
                                    class="form-control @error('media.*') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.pdf,.gif"
                                    multiple
                                    id="mediaInput">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Format: JPG, PNG, GIF, PDF (Maks. 5MB per file). Bisa upload beberapa file sekaligus.
                                </div>
                                @error('media.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview File -->
                            <div id="filePreview" class="mb-4" style="display: none;">
                                <label class="form-label fw-bold">File yang dipilih:</label>
                                <div id="fileList" class="border rounded p-2 bg-light"></div>
                            </div>

                            <!-- Caption Media -->
                            <div class="mb-4">
                                <label for="caption" class="form-label fw-bold">Keterangan File (Opsional)</label>
                                <input type="text"
                                    class="form-control @error('caption') is-invalid @enderror"
                                    id="caption"
                                    name="caption"
                                    placeholder="Tulis caption untuk file bukti"
                                    value="{{ old('caption') }}">
                                @error('caption')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.verifikasi_lapangan.index') }}"
                                   class="btn btn-outline-secondary">
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

@endsection

@push('scripts')
<script>
// Preview file yang dipilih
document.getElementById('mediaInput').addEventListener('change', function(e) {
    const filePreview = document.getElementById('filePreview');
    const fileList = document.getElementById('fileList');
    const files = e.target.files;

    if (files.length > 0) {
        filePreview.style.display = 'block';
        fileList.innerHTML = '';

        Array.from(files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item d-flex align-items-center gap-2 mb-2 p-2 bg-white border rounded';

            // Icon berdasarkan tipe file
            let icon = 'fa-file text-secondary';
            let iconColor = 'text-secondary';
            if (file.type.includes('image')) {
                icon = 'fa-image text-primary';
                iconColor = 'text-primary';
            } else if (file.type.includes('pdf')) {
                icon = 'fa-file-pdf text-danger';
                iconColor = 'text-danger';
            }

            fileItem.innerHTML = `
                <i class="fas ${icon} ${iconColor}"></i>
                <span class="flex-grow-1 small">${file.name}</span>
                <span class="badge bg-secondary">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
            `;

            fileList.appendChild(fileItem);
        });
    } else {
        filePreview.style.display = 'none';
        fileList.innerHTML = '';
    }
});

// Set tanggal default ke hari ini
document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('tanggal').value) {
        document.getElementById('tanggal').valueAsDate = new Date();
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

<style>
.file-item {
    transition: all 0.3s ease;
}
.file-item:hover {
    background-color: #f8f9fa !important;
    border-color: #dee2e6 !important;
}
</style>
@endpush
