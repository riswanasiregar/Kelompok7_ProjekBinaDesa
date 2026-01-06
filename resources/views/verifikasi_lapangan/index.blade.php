@extends('layouts.app')

@section('content')
<div class="container">
    <div  style="margin-top: 60px;">
    <h3 class="mb-3">Daftar Verifikasi Lapangan</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <div class="row g-3">
        @forelse ($verifikasi as $item)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->pendaftar->warga->nama ?? 'Nama tidak ditemukan' }}</h5>
                        <p class="mb-1"><strong>Program:</strong> {{ $item->pendaftar->program->nama_program ?? 'Program tidak ditemukan' }}</p>
                        <p class="mb-1"><strong>Petugas:</strong> {{ $item->petugas }}</p>
                        <p class="mb-1"><strong>Tanggal:</strong> {{ $item->tanggal->format('d-m-Y') }}</p>
                        <p class="mb-1"><strong>Skor:</strong> {{ $item->skor }} ({{ $item->kategori_skor }})</p>

                        {{-- Tampilkan catatan verifikasi --}}
                        @if($item->catatan)
                            <div class="mb-2">
                                <p class="mb-1"><strong>Catatan:</strong></p>
                                <p class="text-secondary small" style="white-space: pre-line;">{{ $item->catatan }}</p>
                            </div>
                        @else
                            <p class="text-muted small mb-2">
                                <em>Tidak ada catatan</em>
                            </p>
                        @endif

                        <!-- button lihat foto -->
                        @php
                            $foto = $item->media()->first(); // Ambil foto pertama
                        @endphp
                        @if($foto)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $foto->file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                    Lihat Foto
                                </a>
                            </div>
                        @else
                            <div class="mt-2">
                                <small class="text-muted">Tidak ada foto</small>
                            </div>
                        @endif
                    </div>
                    
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center mb-0">Belum ada data verifikasi</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $verifikasi->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
