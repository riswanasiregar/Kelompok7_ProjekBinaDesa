@extends('layouts.app')

@section('content')
<div class="container">
   <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
    <div>
        <p class="text-muted mb-1 text-uppercase fw-semibold small">Pendaftaran</p>
        <h4 class="mb-0 fw-semibold">Data warga</h4>
        <span class="text-muted small">Kelola data warga desa</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('warga.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah data
        </a>
    </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    @if($wargas->count() > 0)
        <div class="row g-4">
            @foreach($wargas as $warga)
                <div class="col-md-4 col-lg-4 col-sm-6">
                    <div class="card shadow-sm h-100" style="transition: 0.3s;">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2 fw-semibold text-dark">
                                {{ $warga->nama }}
                            </h5>

                            <p class="card-text text-muted mb-1">
                                <strong>No KTP:</strong> {{ $warga->no_ktp }}<br>
                                <strong>Jenis Kelamin:</strong> {{ $warga->jenis_kelamin ?? '-' }}<br>
                                <strong>Agama:</strong> {{ $warga->agama ?? '-' }}<br>
                                <strong>Pekerjaan:</strong> {{ $warga->pekerjaan ?? '-' }}<br>
                                <strong>Telepon:</strong> {{ $warga->telp ?? '-' }}<br>
                                <strong>Email:</strong> {{ $warga->email ?? '-' }}
                            </p>
                        </div>

                        <div class="card-footer bg-white border-0 d-flex justify-content-between">
                            <a href="{{ route('warga.edit', $warga->warga_id) }}"
                               class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('warga.destroy', $warga->warga_id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin hapus data warga ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center mt-4">
            Belum ada data warga. <br>
            <a href="{{ route('warga.create') }}" class="btn btn-primary mt-2">Tambah Sekarang</a>
        </div>
    @endif
</div>

<!-- Efek hover -->
<style>
 /* Tambahan agar konten tidak tertimpa navbar */
    .container-fluid {
        margin-top: 70px; 
    }
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}
</style>
@endsection
