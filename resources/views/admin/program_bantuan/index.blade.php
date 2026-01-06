@extends('layouts.admin.app')
@section('title', 'Program Bantuan')
@section('content')
<div class="py-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center w-100 flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-2">Data Program Bantuan</h1>
        </div>
        <div>
            <a href="{{ route('program_bantuan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Tambah Program Bantuan
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

{{-- Filter Tahun & Search --}}
<form method="GET" action="{{ route('program_bantuan.index') }}" class="mb-3">
    <div class="row g-2">
        {{-- Filter Tahun --}}
        <div class="col-md-3">
            <select name="tahun" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Tahun</option>
                @foreach($tahun_list as $tahun)
                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                        {{ $tahun }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Search --}}
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       value="{{ request('search') }}" placeholder="Search" aria-label="Search">
                <button type="submit" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M11 2q.396 0 .783.036a6 6 0 0 0-.699 1.966L11 4c-3.867 0-7 3.132-7 7s3.133 7 7 7a6.98 6.98 0 0 0 4.875-1.976l.15-.15A6.98 6.98 0 0 0 18 11l-.003-.085a6 6 0 0 0 1.966-.7a8.96 8.96 0 0 1-1.932 6.401l4.283 4.283l-1.415 1.414l-4.282-4.282A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9m5.53-.681a.507.507 0 0 1 .94 0l.254.611a4.37 4.37 0 0 0 2.25 2.326l.718.32a.53.53 0 0 1 0 .963l-.76.338a4.36 4.36 0 0 0-2.218 2.25l-.247.566a.506.506 0 0 1-.934 0l-.246-.565a4.36 4.36 0 0 0-2.22-2.251l-.76-.338a.53.53 0 0 1 0-.963l.718-.32a4.37 4.37 0 0 0 2.251-2.326z"/></svg>
                </button>
                @if(request('search'))
                    <a href="{{ request()->fullUrlWithQuery(['search'=> null]) }}" class="btn btn-outline-secondary ml-3">Clear</a>
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
                                <th class="border-0">Kode</th>
                                <th class="border-0">Nama Program</th>
                                <th class="border-0">Tahun</th>
                                <th class="border-0">Deskripsi</th>
                                <th class="border-0">Anggaran</th>
                                <th class="border-0">Media</th>
                                <th class="border-0 rounded-end text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = ($ProgramBantuan->currentPage()-1) * $ProgramBantuan->perPage() + 1;
                            @endphp
                            @foreach($ProgramBantuan as $data)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $data->kode }}</td>
                                    <td>{{ $data->nama_program }}</td>
                                    <td>{{ $data->tahun }}</td>
                                    <td>{{ $data->deskripsi }}</td>
                                    <td>{{ $data->anggaran_formatted }}</td>

                                    <td>
                                        @php
                                            $mediaItems = $data->media->filter(function($media) {
                                                return Storage::disk('public')->exists($media->file_url);
                                            });
                                        @endphp

                                        @if($mediaItems->count() > 0)
                                            @if($mediaItems->count() == 1)
                                                {{-- Tampilkan single image --}}
                                                @php
                                                    $media = $mediaItems->first();
                                                    $filePath = storage_path('app/public/' . $media->file_url);
                                                    $type = pathinfo($filePath, PATHINFO_EXTENSION);
                                                    $imgData = base64_encode(file_get_contents($filePath));
                                                @endphp
                                                <img src="data:image/{{ $type }};base64,{{ $imgData }}"
                                                     alt="Media"
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;"
                                                     data-bs-toggle="tooltip"
                                                     data-bs-title="Klik untuk zoom"
                                                     onclick="showImageModal('data:image/{{ $type }};base64,{{ $imgData }}', '{{ $data->nama_program }}')">
                                            @else
                                                {{-- Tampilkan carousel untuk multiple images --}}
                                                <div id="carousel-{{ $data->program_id }}" class="carousel slide" style="width: 120px; height: 80px;">
                                                    <div class="carousel-inner" style="border-radius: 4px; overflow: hidden;">
                                                        @foreach($mediaItems as $index => $media)
                                                            @php
                                                                $filePath = storage_path('app/public/' . $media->file_url);
                                                                $type = pathinfo($filePath, PATHINFO_EXTENSION);
                                                                $imgData = base64_encode(file_get_contents($filePath));
                                                            @endphp
                                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                <img src="data:image/{{ $type }};base64,{{ $imgData }}"
                                                                     alt="Media {{ $index + 1 }}"
                                                                     style="width: 120px; height: 80px; object-fit: cover; cursor: pointer;"
                                                                     data-bs-toggle="tooltip"
                                                                     data-bs-title="Klik untuk zoom ({{ $index + 1 }}/{{ $mediaItems->count() }})"
                                                                     onclick="showImageModal('data:image/{{ $type }};base64,{{ $imgData }}', '{{ $data->nama_program }} - Gambar {{ $index + 1 }}')">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if($mediaItems->count() > 1)
                                                        <button class="carousel-control-prev" type="button"
                                                                data-bs-target="#carousel-{{ $data->program_id }}"
                                                                data-bs-slide="prev"
                                                                style="width: 30px; height: 30px; top: 50%; transform: translateY(-50%); left: 0;">
                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                            <span class="visually-hidden">Previous</span>
                                                        </button>
                                                        <button class="carousel-control-next" type="button"
                                                                data-bs-target="#carousel-{{ $data->program_id }}"
                                                                data-bs-slide="next"
                                                                style="width: 30px; height: 30px; top: 50%; transform: translateY(-50%); right: 0;">
                                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                            <span class="visually-hidden">Next</span>
                                                        </button>
                                                    @endif
                                                </div>
                                                <div class="text-center mt-1">
                                                    <small class="text-muted">
                                                        <i class="fas fa-images"></i> {{ $mediaItems->count() }} gambar
                                                    </small>
                                                </div>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('program_bantuan.edit', $data->program_id) }}"
                                               class="btn btn-info btn-sm d-flex align-items-center">
                                                <svg class="icon icon-xs me-1" data-slot="icon" fill="none" stroke-width="1.5"
                                                     stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                                     aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"></path>
                                                </svg>
                                                Edit
                                            </a>

                                            <form action="{{ route('program_bantuan.destroy', $data->program_id) }}"
                                                  method="POST" style="display:inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method("DELETE")
                                                <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center">
                                                    <svg class="icon icon-xs me-1" fill="none" stroke-width="1.5" stroke="currentColor"
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166
                                                                 m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084
                                                                 a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79
                                                                 m14.456 0a48.108 48.108 0 0 0-3.478-.397
                                                                 m-12 .562c.34-.059.68-.114 1.022-.165
                                                                 m0 0a48.11 48.11 0 0 1 3.478-.397
                                                                 m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201
                                                                 a51.964 51.964 0 0 0-3.32 0
                                                                 c-1.18.037-2.09 1.022-2.09 2.201v.916
                                                                 m7.5 0a48.667 48.667 0 0 0-7.5 0">
                                                        </path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                   <div class="mt-3 d-flex justify-content-end">
    {{ $ProgramBantuan->links('pagination::bootstrap-5') }}
</div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Image Zoom -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 70vh;">
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

@push('scripts')
<script>
    // Function to show image in modal
    function showImageModal(imageSrc, title) {
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModalLabel').textContent = title;

        // Set download link
        const downloadLink = document.getElementById('downloadLink');
        downloadLink.href = imageSrc;
        downloadLink.download = title.toLowerCase().replace(/[^a-z0-9]/g, '-') + '.png';

        modal.show();
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Initialize all carousels
        document.querySelectorAll('.carousel').forEach(carouselElement => {
            new bootstrap.Carousel(carouselElement, {
                interval: false, // Disable auto-slide for table carousels
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

    /* Modal image styling */
    #modalImage {
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>
@endpush
