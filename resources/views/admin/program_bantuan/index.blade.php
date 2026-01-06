@extends('layouts.admin.app')
@section('title', 'Verifikasi Lapangan')
@section('content')
<div class="py-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center w-100 flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-2">Data Verifikasi Lapangan</h1>
        </div>
        <div>
            <a href="{{ route('admin.verifikasi_lapangan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Tambah Verifikasi Lapangan
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
@if (session('error'))
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

{{-- Filter & Search --}}
<form method="GET" action="{{ route('admin.verifikasi_lapangan.index') }}" class="mb-3">
    <div class="row g-2">
        {{-- Filter Pendaftar --}}
        <div class="col-md-3">
            <select name="pendaftar_id" class="form-select">
                <option value="">Semua Pendaftar</option>
                @foreach($pendaftarList as $pendaftar)
                    <option value="{{ $pendaftar->pendaftar_id }}"
                        {{ request('pendaftar_id') == $pendaftar->pendaftar_id ? 'selected' : '' }}>
                        {{ $pendaftar->warga->nama ?? 'N/A' }} - {{ $pendaftar->program->nama_program ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Petugas --}}
        <div class="col-md-2">
            <input type="text" name="petugas" class="form-control"
                   value="{{ request('petugas') }}" placeholder="Filter petugas">
        </div>

        {{-- Filter Tanggal Mulai --}}
        <div class="col-md-2">
            <input type="date" name="start_date" class="form-control"
                   value="{{ request('start_date') }}" placeholder="Tanggal mulai">
        </div>

        {{-- Filter Tanggal Akhir --}}
        <div class="col-md-2">
            <input type="date" name="end_date" class="form-control"
                   value="{{ request('end_date') }}" placeholder="Tanggal akhir">
        </div>

        {{-- Search --}}
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       value="{{ request('search') }}" placeholder="Cari petugas atau catatan..." aria-label="Search">
                <button type="submit" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M11 2q.396 0 .783.036a6 6 0 0 0-.699 1.966L11 4c-3.867 0-7 3.132-7 7s3.133 7 7 7a6.98 6.98 0 0 0 4.875-1.976l.15-.15A6.98 6.98 0 0 0 18 11l-.003-.085a6 6 0 0 0 1.966-.7a8.96 8.96 0 0 1-1.932 6.401l4.283 4.283l-1.415 1.414l-4.282-4.282A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9m5.53-.681a.507.507 0 0 1 .94 0l.254.611a4.37 4.37 0 0 0 2.25 2.326l.718.32a.53.53 0 0 1 0 .963l-.76.338a4.36 4.36 0 0 0-2.218 2.25l-.247.566a.506.506 0 0 1-.934 0l-.246-.565a4.36 4.36 0 0 0-2.22-2.251l-.76-.338a.53.53 0 0 1 0-.963l.718-.32a4.37 4.37 0 0 0 2.251-2.326z"/>
                    </svg>
                </button>
                @if(request('search') || request('pendaftar_id') || request('petugas') || request('start_date') || request('end_date'))
                    <a href="{{ route('admin.verifikasi_lapangan.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">No</th>
                                <th class="border-0">Pendaftar</th>
                                <th class="border-0">Program</th>
                                <th class="border-0">Petugas</th>
                                <th class="border-0">Tanggal</th>
                                <th class="border-0">Skor</th>
                                <th class="border-0">Kategori</th>
                                <th class="border-0">Catatan</th>
                                <th class="border-0">Media</th>
                                <th class="border-0 rounded-end text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = ($verifikasi->currentPage()-1) * $verifikasi->perPage() + 1;
                            @endphp
                            @foreach($verifikasi as $data)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $data->pendaftar->warga->nama ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $data->pendaftar->warga->no_ktp ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $data->pendaftar->program->nama_program ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $data->petugas }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $data->skor >= 70 ? 'success' : ($data->skor >= 50 ? 'warning' : 'danger') }}">
                                            {{ $data->skor }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($data->skor >= 70)
                                            <span class="badge bg-success">Baik</span>
                                        @elseif($data->skor >= 50)
                                            <span class="badge bg-warning">Cukup</span>
                                        @else
                                            <span class="badge bg-danger">Kurang</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->catatan)
                                            {{ Str::limit($data->catatan, 50) }}
                                            @if(strlen($data->catatan) > 50)
                                                <a href="#" data-bs-toggle="tooltip"
                                                   data-bs-title="{{ $data->catatan }}">[...]</a>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $mediaItems = $data->media->filter(function($media) {
                                                return Storage::disk('public')->exists($media->file_url);
                                            });
                                        @endphp

                                        @if($mediaItems->count() > 0)
                                            @if($mediaItems->count() == 1)
                                                {{-- Tampilkan single image atau PDF --}}
                                                @php
                                                    $media = $mediaItems->first();
                                                    $filePath = storage_path('app/public/' . $media->file_url);
                                                    $type = pathinfo($filePath, PATHINFO_EXTENSION);

                                                    if(in_array(strtolower($type), ['jpg', 'jpeg', 'png', 'gif'])) {
                                                        $imgData = base64_encode(file_get_contents($filePath));
                                                @endphp
                                                        <img src="data:image/{{ $type }};base64,{{ $imgData }}"
                                                             alt="Media"
                                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; cursor: pointer;"
                                                             data-bs-toggle="tooltip"
                                                             data-bs-title="Klik untuk lihat"
                                                             onclick="showMediaModal('{{ $media->file_url }}', '{{ $media->caption ?: 'Verifikasi Media' }}', '{{ $type }}')">
                                                @php
                                                    } else {
                                                @endphp
                                                        <div class="pdf-thumbnail"
                                                             style="width: 60px; height: 60px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer;"
                                                             onclick="showPdfModal('{{ asset('storage/' . $media->file_url) }}', '{{ $media->caption ?: 'Document PDF' }}')">
                                                            <i class="fas fa-file-pdf text-danger" style="font-size: 24px;"></i>
                                                        </div>
                                                @php
                                                    }
                                                @endphp
                                            @else
                                                {{-- Tampilkan carousel untuk multiple images --}}
                                                <div id="carousel-{{ $data->verifikasi_id }}" class="carousel slide" style="width: 120px; height: 80px;">
                                                    <div class="carousel-inner" style="border-radius: 4px; overflow: hidden;">
                                                        @foreach($mediaItems as $index => $media)
                                                            @php
                                                                $filePath = storage_path('app/public/' . $media->file_url);
                                                                $type = pathinfo($filePath, PATHINFO_EXTENSION);

                                                                if(in_array(strtolower($type), ['jpg', 'jpeg', 'png', 'gif'])) {
                                                                    $imgData = base64_encode(file_get_contents($filePath));
                                                            @endphp
                                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                        <img src="data:image/{{ $type }};base64,{{ $imgData }}"
                                                                             alt="Media {{ $index + 1 }}"
                                                                             style="width: 120px; height: 80px; object-fit: cover; cursor: pointer;"
                                                                             data-bs-toggle="tooltip"
                                                                             data-bs-title="Klik untuk lihat ({{ $index + 1 }}/{{ $mediaItems->count() }})"
                                                                             onclick="showMediaModal('{{ $media->file_url }}', '{{ $media->caption ?: 'Verifikasi Media ' . ($index + 1) }}', '{{ $type }}')">
                                                                    </div>
                                                            @php
                                                                } else {
                                                            @endphp
                                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                        <div style="width: 120px; height: 80px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; cursor: pointer;"
                                                                             onclick="showPdfModal('{{ asset('storage/' . $media->file_url) }}', '{{ $media->caption ?: 'Document PDF ' . ($index + 1) }}')">
                                                                            <i class="fas fa-file-pdf text-danger" style="font-size: 32px;"></i>
                                                                        </div>
                                                                    </div>
                                                            @php
                                                                }
                                                            @endphp
                                                        @endforeach
                                                    </div>
                                                    @if($mediaItems->count() > 1)
                                                        <button class="carousel-control-prev" type="button"
                                                                data-bs-target="#carousel-{{ $data->verifikasi_id }}"
                                                                data-bs-slide="prev"
                                                                style="width: 30px; height: 30px; top: 50%; transform: translateY(-50%); left: 0;">
                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                            <span class="visually-hidden">Previous</span>
                                                        </button>
                                                        <button class="carousel-control-next" type="button"
                                                                data-bs-target="#carousel-{{ $data->verifikasi_id }}"
                                                                data-bs-slide="next"
                                                                style="width: 30px; height: 30px; top: 50%; transform: translateY(-50%); right: 0;">
                                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                            <span class="visually-hidden">Next</span>
                                                        </button>
                                                    @endif
                                                </div>
                                                <div class="text-center mt-1">
                                                    <small class="text-muted">
                                                        <i class="fas fa-file-alt"></i> {{ $mediaItems->count() }} file
                                                    </small>
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.verifikasi_lapangan.show', $data->verifikasi_id) }}"
                                               class="btn btn-info btn-sm d-flex align-items-center"
                                               data-bs-toggle="tooltip" data-bs-title="Detail">
                                                <i class="fas fa-eye me-1"></i>
                                            </a>

                                            <a href="{{ route('admin.verifikasi_lapangan.edit', $data->verifikasi_id) }}"
                                               class="btn btn-warning btn-sm d-flex align-items-center"
                                               data-bs-toggle="tooltip" data-bs-title="Edit">
                                                <i class="fas fa-edit me-1"></i>
                                            </a>

                                            <form action="{{ route('admin.verifikasi_lapangan.destroy', $data->verifikasi_id) }}"
                                                  method="POST" style="display:inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus verifikasi ini?');">
                                                @csrf
                                                @method("DELETE")
                                                <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center"
                                                        data-bs-toggle="tooltip" data-bs-title="Hapus">
                                                    <i class="fas fa-trash me-1"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3 d-flex justify-content-end">
                        {{ $verifikasi->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Image/PDF -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="mediaModalBody">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a id="downloadLink" href="#" class="btn btn-primary" download>
                    <i class="fas fa-download me-2"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Carousel styling for white controls */
    .carousel-control-prev,
    .carousel-control-next {
        background-color: rgba(255, 255, 255, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.9) !important;
        border-radius: 50% !important;
        opacity: 0.8 !important;
        width: 30px !important;
        height: 30px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        transition: all 0.3s ease !important;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background-color: rgba(255, 255, 255, 1) !important;
        opacity: 1 !important;
        transform: translateY(-50%) scale(1.1) !important;
    }

    .carousel-control-prev {
        left: 5px !important;
    }

    .carousel-control-next {
        right: 5px !important;
    }

    /* White arrow icons */
    .carousel-control-prev-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/%3e%3c/svg%3e") !important;
        width: 12px !important;
        height: 12px !important;
    }

    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e") !important;
        width: 12px !important;
        height: 12px !important;
    }

    .carousel-item img {
        transition: transform 0.3s ease;
    }

    .carousel-item img:hover {
        transform: scale(1.05);
    }

    .pdf-thumbnail:hover {
        background: #e9ecef !important;
        transform: scale(1.05);
        transition: all 0.3s ease;
    }

    /* Modal styling */
    #mediaModalBody img {
        max-width: 100%;
        max-height: 70vh;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    #mediaModalBody iframe {
        width: 100%;
        height: 70vh;
        border: none;
        border-radius: 8px;
    }

    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
</style>
@endpush

@push('scripts')
<script>
    // Function to show media (image/PDF) in modal
    function showMediaModal(filePath, title, fileType) {
        const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
        const modalBody = document.getElementById('mediaModalBody');
        const modalTitle = document.getElementById('mediaModalLabel');
        const downloadLink = document.getElementById('downloadLink');

        modalTitle.textContent = title;

        // Clear previous content
        modalBody.innerHTML = '';

        // Get full URL
        const fullUrl = '{{ asset("storage/") }}' + '/' + filePath;

        if(['jpg', 'jpeg', 'png', 'gif'].includes(fileType.toLowerCase())) {
            // For images
            const img = document.createElement('img');
            img.src = fullUrl;
            img.alt = title;
            img.className = 'img-fluid';
            modalBody.appendChild(img);

            // Set download link for image
            downloadLink.href = fullUrl;
            downloadLink.download = title.toLowerCase().replace(/[^a-z0-9]/g, '-') + '.' + fileType;
        } else if(fileType.toLowerCase() === 'pdf') {
            // For PDF
            showPdfModal(fullUrl, title);
            return; // Exit early since PDF has its own modal
        } else {
            // For other file types
            const link = document.createElement('a');
            link.href = fullUrl;
            link.className = 'btn btn-primary';
            link.textContent = 'Download File';
            link.target = '_blank';
            modalBody.appendChild(link);

            // Set download link
            downloadLink.href = fullUrl;
            downloadLink.download = title.toLowerCase().replace(/[^a-z0-9]/g, '-') + '.' + fileType;
        }

        modal.show();
    }

    // Function to show PDF in iframe modal
    function showPdfModal(pdfUrl, title) {
        const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
        const modalBody = document.getElementById('mediaModalBody');
        const modalTitle = document.getElementById('mediaModalLabel');
        const downloadLink = document.getElementById('downloadLink');

        modalTitle.textContent = title;

        // Clear previous content
        modalBody.innerHTML = '';

        // Create iframe for PDF
        const iframe = document.createElement('iframe');
        iframe.src = pdfUrl + '#view=FitH';
        iframe.style.width = '100%';
        iframe.style.height = '70vh';
        iframe.style.border = 'none';

        modalBody.appendChild(iframe);

        // Set download link for PDF
        downloadLink.href = pdfUrl;
        downloadLink.download = title.toLowerCase().replace(/[^a-z0-9]/g, '-') + '.pdf';

        modal.show();
    }

    // Initialize tooltips and carousels
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize all carousels
        document.querySelectorAll('.carousel').forEach(carouselElement => {
            new bootstrap.Carousel(carouselElement, {
                interval: false,
                wrap: true
            });
        });

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
@endpush
