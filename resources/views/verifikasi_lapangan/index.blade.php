@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Daftar Verifikasi Lapangan</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('verifikasi.create') }}" class="btn btn-primary">+ Tambah Verifikasi</a>
    </div>

    <div class="row g-3">
        @forelse ($verifikasi as $item)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->pendaftar->warga->nama ?? '-' }}</h5>
                        <p class="mb-1"><strong>Program:</strong> {{ $item->pendaftar->program->nama_program ?? '-' }}</p>
                        <p class="mb-1"><strong>Petugas:</strong> {{ $item->petugas }}</p>
                        <p class="mb-1"><strong>Tanggal:</strong> {{ $item->tanggal->format('d-m-Y') }}</p>
                        <p class="mb-1"><strong>Skor:</strong> {{ $item->skor }} ({{ $item->kategori_skor }})</p>
                        <span class="badge {{ $item->status_label['class'] }}">{{ $item->status_label['label'] }}</span>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="{{ route('verifikasi.edit', $item->verifikasi_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('verifikasi.destroy', $item->verifikasi_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf 
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center mb-0">Data tidak ditemukan</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $verifikasi->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
