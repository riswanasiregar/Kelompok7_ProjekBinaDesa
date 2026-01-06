@extends('layouts.app')

@section('content')
@php use Illuminate\Support\Str; @endphp

<div class="container">
    <h3 class="mb-3">Daftar Program Bantuan</h3>

    @if(isset($data) && $data->count() > 0)
        <div class="row g-4">
            @foreach($data as $item)
                <div class="col-md-4 col-lg-4 col-sm-6">
                    <div class="card shadow-sm h-100" style="transition: 0.3s;">
                        <div class="card-body d-flex flex-column">

                            <!-- Nama program -->
                            <h5 class="card-title mb-2" style="white-space: normal; font-weight: 600;">
                                {{ $item->nama_program }}
                            </h5>

                            <p class="card-text text-muted mb-1">
                                <strong>Kode:</strong> {{ $item->kode }}<br>
                                <strong>Tahun:</strong> {{ $item->tahun }}
                            </p>

                            <!-- Deskripsi -->
                            @if($item->deskripsi)
                                <p class="text-secondary small flex-grow-1 text-start mb-2" style="white-space: pre-line;">
                                    <strong>Deskripsi:</strong><br>
                                    {{ $item->deskripsi }}
                                </p>
                            @else
                                <p class="text-muted small mb-2">
                                    <em>Tidak ada deskripsi</em>
                                </p>
                            @endif

                            <!-- Anggaran -->
                            <p class="fw-bold text-success fs-5 mb-2">
                                Rp {{ number_format($item->anggaran, 0, ',', '.') }}
                            </p>

                            <!-- Tombol lihat media -->
                            @php
                                // Gunakan accessor media_utama dari model
                                $foto = $item->media_utama;
                            @endphp

                            @if($foto)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $foto->file_url) }}" target="_blank" class="btn btn-info btn-sm">
                                        Lihat Foto
                                    </a>
                                </div>
                            @else
                                <div class="mb-2">
                                    <small class="text-muted">Tidak ada foto</small>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info text-center mt-4">
            Belum ada data program bantuan.
        </div>
    @endif
</div>

<style>
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}
</style>
@endsection
