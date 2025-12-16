@extends('layouts.admin.app')

@section('title', 'Riwayat Penyaluran Bantuan')

@section('content')
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="#">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('riwayat_penyaluran_bantuan.index') }}">Riwayat Penyaluran Bantuan</a></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Data Riwayat Penyaluran Bantuan</h1>
            <p class="mb-0">List seluruh penyaluran bantuan yang telah dilakukan</p>
        </div>
        <div>
            <a href="{{ route('riwayat_penyaluran_bantuan.create') }}" class="btn btn-success text-white">
                <i class="fas fa-plus me-1"></i> Tambah Penyaluran
            </a>
        </div>
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

{{-- Filter --}}
<form method="GET" action="{{ route('riwayat_penyaluran_bantuan.index') }}" class="mb-3">
    <div class="row g-2">
        {{-- Filter Program --}}
        <div class="col-md-3">
            <select name="program_id" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Program</option>
                @foreach($program as $p)
                    <option value="{{ $p->program_id }}" {{ request('program_id') == $p->program_id ? 'selected' : '' }}>
                        {{ $p->nama_program }} ({{ $p->tahun }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Penerima --}}
        <div class="col-md-3">
            <select name="penerima_id" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Penerima</option>
                @foreach($penerima as $p)
                    <option value="{{ $p->penerima_id }}" {{ request('penerima_id') == $p->penerima_id ? 'selected' : '' }}>
                        {{ $p->warga->nama ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Tahun --}}
        <div class="col-md-2">
            <input type="number"
                   name="tahun"
                   class="form-control"
                   value="{{ request('tahun') }}"
                   placeholder="Tahun"
                   min="2000"
                   max="{{ date('Y') + 5 }}">
        </div>

        {{-- Search --}}
        <div class="col-md-4">
            <div class="input-group">
                <input type="text"
                       name="search"
                       class="form-control"
                       value="{{ request('search') }}"
                       placeholder="Cari nama penerima"
                       aria-label="Search">
                <button type="submit" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M11 2q.396 0 .783.036a6 6 0 0 0-.699 1.966L11 4c-3.867 0-7 3.132-7 7s3.133 7 7 7a6.98 6.98 0 0 0 4.875-1.976l.15-.15A6.98 6.98 0 0 0 18 11l-.003-.085a6 6 0 0 0 1.966-.7a8.96 8.96 0 0 1-1.932 6.401l4.283 4.283l-1.415 1.414l-4.282-4.282A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9m5.53-.681a.507.507 0 0 1 .94 0l.254.611a4.37 4.37 0 0 0 2.25 2.326l.718.32a.53.53 0 0 1 0 .963l-.76.338a4.36 4.36 0 0 0-2.218 2.25l-.247.566a.506.506 0 0 1-.934 0l-.246-.565a4.36 4.36 0 0 0-2.22-2.251l-.76-.338a.53.53 0 0 1 0-.963l.718-.32a4.37 4.37 0 0 0 2.251-2.326z"/>
                    </svg>
                </button>
                @if(request('search') || request('program_id') || request('penerima_id') || request('tahun'))
                    <a href="{{ route('riwayat_penyaluran_bantuan.index') }}" class="btn btn-outline-secondary ms-2">
                        Reset
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
                                <th class="border-0">Tanggal</th>
                                <th class="border-0">Penerima</th>
                                <th class="border-0">Program</th>
                                <th class="border-0">Tahap</th>
                                <th class="border-0">Nilai</th>
                                <th class="border-0">Dokumen</th>
                                <th class="border-0 rounded-end text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = ($penyaluran->currentPage()-1) * $penyaluran->perPage() + 1;
                            @endphp
                            @foreach($penyaluran as $data)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($data->tanggal)->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $data->penerima->warga->nama ?? 'N/A' }}</strong>
                                        @if($data->penerima->warga->no_ktp ?? false)
                                            <br>
                                            <small class="text-muted">NIK: {{ $data->penerima->warga->no_ktp }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $data->program->nama_program ?? 'N/A' }}</strong>
                                        @if($data->program->tahun ?? false)
                                            <br>
                                            <small class="text-muted">Tahun: {{ $data->program->tahun }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            Tahap {{ $data->tahap_ke }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            Rp {{ number_format($data->nilai, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $media = $data->media->first();
                                        @endphp
                                        @if($media)
                                            @php
                                                // Cek apakah file ada di storage
                                                $fileExists = Storage::disk('public')->exists($media->file_url);
                                                $fileUrl = $media->file_url;
                                                $publicUrl = asset('storage/' . $fileUrl);
                                                $extension = pathinfo($fileUrl, PATHINFO_EXTENSION);
                                                $extensionLower = strtolower($extension);
                                                $isImage = in_array($extensionLower, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                                            @endphp

                                            @if($fileExists)
                                                @if($isImage)
                                                    {{-- Untuk gambar: gunakan URL langsung, jangan base64 --}}
                                                    <a href="{{ $publicUrl }}"
                                                       target="_blank"
                                                       class="text-decoration-none"
                                                       title="{{ $media->caption ?? 'Lihat gambar' }}">
                                                        <img src="{{ $publicUrl }}"
                                                             alt="Dokumen"
                                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"
                                                             onerror="this.onerror=null; this.style.display='none'; this.parentNode.innerHTML='<div class=\'text-center\'><i class=\'fas fa-image text-secondary fa-2x\'></i><br><small>Gambar</small></div>';">
                                                    </a>
                                                @else
                                                    {{-- Untuk file non-gambar --}}
                                                    <a href="{{ $publicUrl }}"
                                                       target="_blank"
                                                       class="text-decoration-none"
                                                       title="{{ $media->caption ?? 'Download dokumen' }}">
                                                        <div class="text-center">
                                                            @if($extensionLower == 'pdf')
                                                                <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                                                <br>
                                                                <small class="text-muted">PDF</small>
                                                            @elseif(in_array($extensionLower, ['doc', 'docx']))
                                                                <i class="fas fa-file-word text-primary fa-2x"></i>
                                                                <br>
                                                                <small class="text-muted">DOC</small>
                                                            @elseif(in_array($extensionLower, ['xls', 'xlsx']))
                                                                <i class="fas fa-file-excel text-success fa-2x"></i>
                                                                <br>
                                                                <small class="text-muted">Excel</small>
                                                            @else
                                                                <i class="fas fa-file text-secondary fa-2x"></i>
                                                                <br>
                                                                <small class="text-muted">{{ strtoupper($extension) }}</small>
                                                            @endif
                                                        </div>
                                                    </a>
                                                @endif

                                                @if($media->caption)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($media->caption, 20) }}</small>
                                                @endif

                                                {{-- Debug info (hanya tampil di mode debug) --}}
                                                @if(config('app.debug'))
                                                    <br>
                                                    <small class="text-muted" style="font-size: 0.7rem;">
                                                        Path: {{ $fileUrl }}
                                                    </small>
                                                @endif
                                            @else
                                                <span class="text-warning" title="File tidak ditemukan: {{ $fileUrl }}">
                                                    <i class="fas fa-exclamation-triangle"></i> File hilang
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            {{-- Detail --}}
                                            <a href="{{ route('riwayat_penyaluran_bantuan.show', $data->penyaluran_id) }}"
                                               class="btn btn-info btn-sm d-flex align-items-center"
                                               title="Detail">
                                                <svg class="icon icon-xs me-1" fill="none" stroke-width="1.5"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                </svg>
                                                Detail
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('riwayat_penyaluran_bantuan.edit', $data->penyaluran_id) }}"
                                               class="btn btn-warning btn-sm d-flex align-items-center"
                                               title="Edit">
                                                <svg class="icon icon-xs me-1" fill="none" stroke-width="1.5"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                                </svg>
                                                Edit
                                            </a>

                                            {{-- Hapus --}}
                                            <form action="{{ route('riwayat_penyaluran_bantuan.destroy', $data->penyaluran_id) }}"
                                                  method="POST"
                                                  style="display:inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penyaluran ini?');">
                                                @csrf
                                                @method("DELETE")
                                                <button type="submit"
                                                        class="btn btn-danger btn-sm d-flex align-items-center"
                                                        title="Hapus">
                                                    <svg class="icon icon-xs me-1" fill="none" stroke-width="1.5"
                                                         stroke="currentColor" viewBox="0 0 24 24">
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
                                                                 m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if($penyaluran->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <svg class="icon icon-xxs text-gray-400 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.801 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.801 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                            </svg>
                                            Tidak ada data penyaluran
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    @if($penyaluran->hasPages())
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $penyaluran->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal untuk menampilkan gambar --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 70vh;"
                     onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTQgMkg2QTIgMiAwIDAgMCA0IDR2MTZhMiAyIDAgMCAwIDIgMmgxMmEyIDIgMCAwIDAgMi0yVjh6IiBzdHJva2U9IiM2YzYzNmQiIHN0cm9rZS13aWR0aD0iMiIvPjxwYXRoIGQ9Ik0xNCAydjZoNiIgc3Ryb2tlPSIjNmM2MzZkIiBzdHJva2Utd2lkdGg9IjIiLz48L3N2Zz4=';">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showImage(imageSrc, title) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModalLabel').textContent = title || 'Dokumen Penyaluran';
        var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }
</script>
@endpush

@push('styles')
<style>
    .table td, .table th {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .icon {
        width: 1rem;
        height: 1rem;
    }

    /* Style untuk gambar thumbnail */
    .doc-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        transition: transform 0.2s;
    }

    .doc-thumbnail:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endpush
