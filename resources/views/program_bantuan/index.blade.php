
@extends('layouts.app')

@section('content')
@php use Illuminate\Support\Str; @endphp

<div class="container">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
    <div>
        <p class="text-muted mb-1 text-uppercase fw-semibold small">Pendaftaran</p>
        <h4 class="mb-0 fw-semibold">Data Program Bantuan</h4>
        <span class="text-muted small">Kelola program bantuan desa</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('program_bantuan.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah program
        </a>
    </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

  

    @if(isset($data) && $data->count() > 0)
        <div class="row g-4">
            @foreach($data as $item)
                <div class="col-md-4 col-lg-4 col-sm-6">
                    <div class="card shadow-sm h-100" style="transition: 0.3s;">
                        @if($item->media)
                            <img src="{{ asset('storage/program_bantuan/' . $item->media) }}" 
                                 class="card-img-top" 
                                 alt="Program Image" 
                                 style="height: 180px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/400x250?text=Tidak+Ada+Gambar" 
                                 class="card-img-top" 
                                 alt="No Image">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <!-- Nama program  -->
                            <h5 class="card-title mb-2" style="white-space: normal; font-weight: 600;">
                                {{ $item->nama_program }}
                            </h5>

                            <p class="card-text text-muted mb-1">
                                <strong>Kode:</strong> {{ $item->kode }}<br>
                                <strong>Tahun:</strong> {{ $item->tahun }}
                            </p>

                            <p class="text-secondary small flex-grow-1 text-start" style="white-space: pre-line;">
                                {{ $item->deskripsi }}
                            </p>

                            <!-- harga sejajar di bawah -->
                            <div class="">
                                <p class="fw-bold text-success fs-5 mb-0">
                                    Rp {{ number_format($item->anggaran, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 d-flex justify-content-between">
                            <a href="{{ route('program_bantuan.edit', $item->program_id) }}" 
                               class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('program_bantuan.destroy', $item->program_id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Yakin hapus program ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- pagination  -->
        <div class="mt-3">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info text-center mt-4">
            Belum ada data program bantuan. <br>
            <a href="{{ route('program_bantuan.create') }}" class="btn btn-primary mt-2">Tambah Sekarang</a>
        </div>
    @endif
</div>

<!-- efek hover  -->
<style>

.container-fluid {
    margin-top: 70px; /* sesuaikan 65–80px jika perlu */
    }
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}
</style>
@endsection
