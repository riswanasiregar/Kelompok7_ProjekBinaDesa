@extends('layouts.app')

@section('content')
<div class="container">
    <div  style="margin-top: 60px;">
    <h3 class="mb-3">Daftar Pendaftar Bantuan</h3>
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
        <div class="d-flex gap-2">
            <a href="{{ route('pendaftar-bantuan.create') }}" class="btn btn-primary">Tambah Pendaftar</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(isset($data) && $data->count() > 0)
        <div class="row">
            @foreach($data as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->warga->nama ?? 'Warga tidak ditemukan' }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $item->program->nama_program ?? 'Program tidak ditemukan' }}</h6>
                            <p class="card-text">
                                <strong>Tanggal:</strong> {{ $item->tanggal_daftar }}<br>
                                <strong>Status:</strong> 
                                <span class="badge bg-{{ $item->status == 'Diterima' ? 'success' : ($item->status == 'Ditolak' ? 'danger' : 'warning') }}">
                                    {{ $item->status }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $item->keterangan }}</small>
                            </p>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between">
                            <a href="{{ route('pendaftar-bantuan.edit', $item->pendaftar_bantuan_id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pendaftar-bantuan.destroy', $item->pendaftar_bantuan_id) }}" method="POST" onsubmit="return confirm('Yakin nih mau hapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info">Belum ada data pendaftar.</div>
    @endif
</div>
@endsection