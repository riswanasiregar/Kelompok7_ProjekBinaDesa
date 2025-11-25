@extends('layouts.app')

@section('content')
@php use Carbon\Carbon; @endphp

<div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
    <div>
        <p class="text-muted mb-1 text-uppercase fw-semibold small">Pendaftaran</p>
        <h4 class="mb-0 fw-semibold">Data Pendaftar Bantuan</h4>
        <span class="text-muted small">Kelola pendaftar untuk program bantuan desa</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pendaftar-bantuan.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah Pendaftar
        </a>
    </div>
    </div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="ti ti-checks me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-75 stat-card">
            <div class="card-body">
                <p class="text-muted text-uppercase small mb-1">Total Pendaftar</p>
                <h3 class="fw-semibold">{{ $stats['total'] }}</h3>
                <span class="subtle text-primary">Semua Status</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-75 stat-card">
            <div class="card-body">
                <p class="text-muted text-uppercase small mb-">Diproses</p>
                <h3 class="fw-semibold text-warning">{{ $stats['diproses'] }}</h3>
                <span class="text-warning">Sedang Diproses</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-75 stat-card">
            <div class="card-body">
                <p class="text-muted text-uppercase small mb-1">Diterima</p>
                <h3 class="fw-semibold text-success">{{ $stats['diterima'] }}</h3>
                <span class="text-success">Lolos Seleksi</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-75 stat-card">
            <div class="card-body">
                <p class="text-muted text-uppercase small mb-1">Ditolak</p>
                <h3 class="fw-semibold text-danger">{{ $stats['ditolak'] }}</h3>
                <span class="text-danger">Tidak Lolos</span>
            </div>
        </div>
    </div>
</div>

<div class="">
    <div class="card-body">
        <h4 class="fw-semibold mb-3">Daftar Pendaftar</h4>
        @if ($data->isEmpty())
            <div class="text-center py-4">
                <p class="text-muted mb-2">Belum ada data pendaftar bantuan.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach ($data as $row)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-xs simple-card">
                            <div class="card-body d-flex flex-column gap-1">
                                <h6 class="fw-semibold mb-0">{{ $row->warga->nama }}</h6>
                                <p class="text-muted small mb-2">No KTP: {{ $row->warga->no_ktp ?? '-' }}</p>
                                <p class="small mb-1"><strong>Program:</strong> {{ $row->program->nama_program }}</p>
                                <p class="small mb-1"><strong>Tanggal Daftar:</strong> {{ Carbon::parse($row->tanggal_daftar)->translatedFormat('d F Y') }}</p>
                                <p class="small mb-1"><strong>Status:</strong> {{ $row->status }}</p>
                                <p class="small text-muted mb-0"><strong>Keterangan:</strong> {{ $row->keterangan ?? '-' }}</p>
                            </div>
                            <div class="card-footer bg-white border-0 d-flex justify-content-between">
                                <a href="{{ route('pendaftar-bantuan.edit', $row->pendaftar_bantuan_id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('pendaftar-bantuan.destroy', $row->pendaftar_bantuan_id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    /* Tambahan agar konten tidak tertimpa navbar */
    .container-fluid {
        margin-top: 70px; /* sesuaikan 65–80px jika perlu */
    }

    .stat-card .badge {font-size: 0.75rem;border-radius: 999px;}
    .status-badge {border-radius: 999px;padding: 0.35rem 0.85rem;font-weight: 600;}
    .simple-card {transition: .2s;}
    .simple-card:hover {transform: translateY(-4px); box-shadow: 0 6px 18px rgba(0,0,0,0.08);}
    .simple-card .card-body {min-height: 200px;}
</style>
@endpush

@endsection