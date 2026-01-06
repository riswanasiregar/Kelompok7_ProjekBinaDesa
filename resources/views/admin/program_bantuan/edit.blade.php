@extends('layouts.admin.app')

@section('title', 'Edit Program Bantuan')

@section('content')
<div class="py-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center w-100 flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-2">Edit Data Program Bantuan</h1>
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

                    <form action="{{ route('admin.program_bantuan.update', $program->program_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h5 class="fw-bold text-gray-800 mb-4">Data Program Bantuan</h5>

                        {{-- Kode --}}
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" name="kode" placeholder="Masukkan kode program" value="{{ old('kode', $program->kode) }}" required>
                            <label for="kode">Kode Program <span class="text-danger">*</span></label>
                            @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Program --}}
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control @error('nama_program') is-invalid @enderror" id="nama_program" name="nama_program" placeholder="Masukkan nama program" value="{{ old('nama_program', $program->nama_program) }}" required>
                            <label for="nama_program">Nama Program <span class="text-danger">*</span></label>
                            @error('nama_program')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tahun --}}
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="number" class="form-control @error('tahun') is-invalid @enderror" id="tahun" name="tahun" placeholder="Masukkan tahun" value="{{ old('tahun', $program->tahun) }}" min="2000" max="{{ date('Y') + 1 }}">
                            <label for="tahun">Tahun</label>
                            @error('tahun')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Anggaran --}}
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="number" step="0.01" class="form-control @error('anggaran') is-invalid @enderror" id="anggaran" name="anggaran" placeholder="Masukkan anggaran" value="{{ old('anggaran', $program->anggaran) }}">
                            <label for="anggaran">Anggaran</label>
                            @error('anggaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi program">{{ old('deskripsi', $program->deskripsi) }}</textarea>
                            @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Media Terupload --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold mb-0">Media Terupload</label>
                                <span class="badge bg-primary">{{ $program->media->count() }} media</span>
                            </div>

                            <div class="row" id="media-container">
                                @forelse ($program->media as $media)
                                <div class="col-md-4 col-lg-3 mb-3 media-item" data-media-id="{{ $media->media_id }}">
                                    <div class="card border h-100">
                                        <div class="position-relative">
                                            @if(Storage::disk('public')->exists($media->file_url))
                                            @php
                                            $filePath = storage_path('app/public/' . $media->file_url);
                                            $type = pathinfo($filePath, PATHINFO_EXTENSION);
                                            $imgData = base64_encode(file_get_contents($filePath));
                                            $isImage = in_array($type, ['jpg', 'jpeg', 'png', 'gif']);
                                            @endphp

                                            @if($isImage)
                                            <img src="data:image/{{ $type }};base64,{{ $imgData }}"
                                                 class="card-img-top media-image"
                                                 style="height: 150px; object-fit: cover;"
                                                 alt="{{ basename($media->file_url) }}"
                                                 data-url="{{ asset('storage/' . $media->file_url) }}"
                                                 data-name="{{ basename($media->file_url) }}">
                                            @else
                                            <div class="card-img-top bg-light d-flex flex-column align-items-center justify-content-center" style="height: 150px;">
                                                <i class="fas fa-file fa-3x text-secondary mb-2"></i>
                                                <small class="text-muted text-center px-2">{{ strtoupper($type) }}</small>
                                            </div>
                                            @endif
                                            @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <i class="fas fa-file fa-3x text-secondary"></i>
                                            </div>
                                            @endif

                                            {{-- Action Menu --}}
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item view-media-btn" href="#"
                                                               data-url="{{ Storage::disk('public')->exists($media->file_url) ? asset('storage/' . $media->file_url) : '#' }}"
                                                               data-name="{{ basename($media->file_url) }}">
                                                                <i class="fas fa-eye me-2"></i> Lihat
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item edit-media-btn" href="#"
                                                               data-id="{{ $media->media_id }}"
                                                               data-caption="{{ $media->caption ?? '' }}">
                                                                <i class="fas fa-edit me-2"></i> Edit Caption
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger delete-media-btn" href="#"
                                                               data-id="{{ $media->media_id }}"
                                                               data-name="{{ basename($media->file_url) }}">
                                                                <i class="fas fa-trash me-2"></i> Hapus
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-truncate mb-1" title="{{ basename($media->file_url) }}">
                                                {{ basename($media->file_url) }}
                                            </h6>
                                            <p class="card-text small text-muted mb-1">
                                                {{ $media->mime_type ?? 'File' }}
                                            </p>
                                            @if($media->caption)
                                            <p class="card-text small text-dark media-caption">
                                                {{ Str::limit($media->caption, 50) }}
                                            </p>
                                            @else
                                            <p class="card-text small text-muted media-caption">Tidak ada caption</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> Belum ada media terupload
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Upload Media Baru --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Upload Media Baru</label>

                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </span>
                                <input type="file" name="media[]" class="form-control @error('media.*') is-invalid @enderror" multiple id="mediaUpload">
                            </div>

                            @error('media.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <small class="text-muted d-block mt-1">Format: JPG, PNG, JPEG, GIF, PDF, DOC, DOCX. Maksimal 5MB per file.</small>

                            <div class="mt-3" id="filePreview">
                                <small class="text-muted">Belum ada file dipilih</small>
                            </div>
                        </div>

                        {{-- Caption untuk Media Baru --}}
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control @error('caption') is-invalid @enderror" id="caption" name="caption" placeholder="Tulis caption untuk media baru" value="{{ old('caption') }}">
                            <label for="caption">Caption untuk Media Baru (Opsional)</label>
                            @error('caption')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.program_bantuan.index') }}" class="btn btn-outline-gray-600">
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

    <!-- Modal untuk Preview Media -->
    <div class="modal fade" id="viewMediaModal" tabindex="-1" aria-labelledby="viewMediaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMediaModalLabel">Preview Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center" id="modalMediaContent">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a id="modalDownloadLink" href="#" class="btn btn-primary" download>
                        <i class="fas fa-download me-2"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Edit Caption Media -->
    <div class="modal fade" id="editCaptionModal" tabindex="-1" aria-labelledby="editCaptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCaptionModalLabel">Edit Caption Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCaptionForm">
                    <div class="modal-body">
                        <input type="hidden" id="editMediaId" name="media_id">
                        <div class="mb-3">
                            <label for="editCaptionText" class="form-label">Caption</label>
                            <textarea class="form-control" id="editCaptionText" name="caption" rows="3" placeholder="Masukkan caption untuk media ini"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk Konfirmasi Hapus -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus media <strong id="deleteMediaName"></strong>?</p>
                    <p class="text-danger small mb-0">Aksi ini tidak dapat dibatalkan dan file akan dihapus secara permanen.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .media-image {
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .media-image:hover {
            transform: scale(1.02);
        }

        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .dropdown-menu {
            min-width: 180px;
        }

        #filePreview .preview-item {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 10px;
            background: #f8f9fa;
        }

        .preview-item .file-icon {
            font-size: 24px;
        }

        .preview-item .file-size {
            font-size: 12px;
            color: #6c757d;
        }

        .badge-file {
            background: #e7f3ff;
            color: #0066cc;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }

        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            min-width: 300px;
        }
    </style>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let mediaToDelete = null;

        // ========== VIEW MEDIA ==========
        document.addEventListener('click', function(e) {
            // View media dari tombol dropdown
            if (e.target.closest('.view-media-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.view-media-btn');
                const url = btn.dataset.url;
                const name = btn.dataset.name;
                viewMedia(url, name);
            }

            // View media dari klik gambar
            if (e.target.closest('.media-image')) {
                e.preventDefault();
                const img = e.target.closest('.media-image');
                const url = img.dataset.url;
                const name = img.dataset.name;
                viewMedia(url, name);
            }
        });

        function viewMedia(url, name) {
            const modalContent = document.getElementById('modalMediaContent');
            const downloadLink = document.getElementById('modalDownloadLink');
            const modalTitle = document.getElementById('viewMediaModalLabel');

            // Cek tipe file
            const extension = name.split('.').pop().toLowerCase();
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension);
            const isPDF = extension === 'pdf';

            if (isImage) {
                modalContent.innerHTML = `<img src="${url}" class="img-fluid rounded" style="max-height: 70vh;" alt="${name}">`;
            } else if (isPDF) {
                modalContent.innerHTML = `
                    <div class="p-3">
                        <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                        <h6>${name}</h6>
                        <p class="text-muted">File PDF</p>
                        <iframe src="${url}" class="w-100" style="height: 400px; border: none;"></iframe>
                    </div>
                `;
            } else {
                modalContent.innerHTML = `
                    <div class="p-5 text-center">
                        <i class="fas fa-file fa-5x text-secondary mb-3"></i>
                        <h6>${name}</h6>
                        <p class="text-muted">File tidak dapat dipreview</p>
                    </div>
                `;
            }

            downloadLink.href = url;
            downloadLink.download = name;
            modalTitle.textContent = name;

            const modal = new bootstrap.Modal(document.getElementById('viewMediaModal'));
            modal.show();
        }

        // ========== EDIT CAPTION ==========
        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-media-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.edit-media-btn');
                const mediaId = btn.dataset.id;
                const caption = btn.dataset.caption || '';

                document.getElementById('editMediaId').value = mediaId;
                document.getElementById('editCaptionText').value = caption;

                const modal = new bootstrap.Modal(document.getElementById('editCaptionModal'));
                modal.show();
            }
        });

        // Submit edit caption form
        document.getElementById('editCaptionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const mediaId = document.getElementById('editMediaId').value;
            const caption = document.getElementById('editCaptionText').value;

            fetch(`/admin/program_bantuan/media/${mediaId}/update-caption`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ caption: caption })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update caption di card yang sesuai
                    const mediaCard = document.querySelector(`.media-item[data-media-id="${mediaId}"]`);
                    if (mediaCard) {
                        const captionElement = mediaCard.querySelector('.media-caption');
                        if (caption.trim()) {
                            captionElement.textContent = caption.length > 50 ? caption.substring(0, 50) + '...' : caption;
                            captionElement.classList.remove('text-muted');
                            captionElement.classList.add('text-dark');
                        } else {
                            captionElement.textContent = 'Tidak ada caption';
                            captionElement.classList.remove('text-dark');
                            captionElement.classList.add('text-muted');
                        }
                    }

                    // Tutup modal
                    bootstrap.Modal.getInstance(document.getElementById('editCaptionModal')).hide();

                    // Tampilkan notifikasi sukses
                    showToast('Caption berhasil diperbarui', 'success');
                } else {
                    showToast('Gagal memperbarui caption', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memperbarui caption', 'error');
            });
        });

        // ========== DELETE MEDIA ==========
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-media-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.delete-media-btn');
                const mediaId = btn.dataset.id;
                const mediaName = btn.dataset.name;

                mediaToDelete = mediaId;
                document.getElementById('deleteMediaName').textContent = mediaName;

                const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                modal.show();
            }
        });

        // Konfirmasi delete
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!mediaToDelete) return;

            fetch(`/admin/program_bantuan/media/${mediaToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hapus card dari DOM
                    const mediaElement = document.querySelector(`.media-item[data-media-id="${mediaToDelete}"]`);
                    if (mediaElement) {
                        mediaElement.remove();
                    }

                    // Update counter
                    updateMediaCounter();

                    // Tutup modal
                    bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();

                    // Tampilkan notifikasi sukses
                    showToast('Media berhasil dihapus', 'success');
                } else {
                    showToast(data.message || 'Gagal menghapus media', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menghapus media', 'error');
            })
            .finally(() => {
                mediaToDelete = null;
            });
        });

        // ========== FILE UPLOAD PREVIEW ==========
        const mediaUpload = document.getElementById('mediaUpload');
        const filePreview = document.getElementById('filePreview');

        if (mediaUpload) {
            mediaUpload.addEventListener('change', function() {
                const files = Array.from(this.files);

                if (files.length === 0) {
                    filePreview.innerHTML = '<small class="text-muted">Belum ada file dipilih</small>';
                    return;
                }

                let previewHTML = '';
                let totalSize = 0;

                files.forEach((file, index) => {
                    totalSize += file.size;
                    const fileSize = formatBytes(file.size);
                    const fileIcon = getFileIcon(file.type);

                    previewHTML += `
                        <div class="preview-item d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="${fileIcon} text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-medium text-truncate" style="max-width: 200px;">${file.name}</div>
                                    <div class="file-size">${fileSize}</div>
                                </div>
                            </div>
                            <span class="badge-file">${file.type.split('/')[1]?.toUpperCase() || 'FILE'}</span>
                        </div>
                    `;
                });

                previewHTML += `
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Total: ${files.length} file • ${formatBytes(totalSize)}
                        </small>
                    </div>
                `;

                filePreview.innerHTML = previewHTML;
            });
        }

        // ========== HELPER FUNCTIONS ==========
        function updateMediaCounter() {
            const mediaCount = document.querySelectorAll('.media-item').length;
            const counterBadge = document.querySelector('.badge.bg-primary');
            if (counterBadge) {
                counterBadge.textContent = `${mediaCount} media`;
            }

            // Jika tidak ada media, tampilkan pesan
            if (mediaCount === 0) {
                const container = document.getElementById('media-container');
                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Belum ada media terupload
                        </div>
                    </div>
                `;
            }
        }

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function getFileIcon(mimeType) {
            if (mimeType.startsWith('image/')) return 'fas fa-file-image';
            if (mimeType === 'application/pdf') return 'fas fa-file-pdf';
            if (mimeType.includes('word')) return 'fas fa-file-word';
            if (mimeType.includes('excel') || mimeType.includes('sheet')) return 'fas fa-file-excel';
            if (mimeType.includes('powerpoint') || mimeType.includes('presentation')) return 'fas fa-file-powerpoint';
            return 'fas fa-file';
        }

        function showToast(message, type = 'info') {
            // Hapus toast sebelumnya
            const existingToasts = document.querySelectorAll('.toast');
            existingToasts.forEach(toast => toast.remove());

            const toastClass = {
                'success': 'bg-success text-white',
                'error': 'bg-danger text-white',
                'info': 'bg-info text-white'
            }[type] || 'bg-info text-white';

            const toastId = 'toast-' + Date.now();
            const toastHTML = `
                <div id="${toastId}" class="toast ${toastClass}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;

            // Buat container jika belum ada
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                document.body.appendChild(container);
            }

            container.insertAdjacentHTML('beforeend', toastHTML);

            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 3000
            });

            toast.show();

            // Hapus dari DOM setelah hilang
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }
    });
</script>
@endpush
