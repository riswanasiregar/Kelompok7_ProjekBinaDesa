@extends('layouts.app')

@section('content')
<div class="container">
    <div  style="margin-top: 100px;">
    <h3 class="mb-3">Daftar Penerima Bantuan</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4 col-lg-3">
            <select name="program_id" class="form-select">
                <option value="">-- Semua Program --</option>
                @foreach($program as $p)
                    <option value="{{ $p->program_id }}" {{ request('program_id')==$p->program_id?'selected':'' }}>
                        {{ $p->nama_program ?? $p->program_nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-lg-3 d-flex gap-2">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('penerima.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <a href="{{ route('penerima.create') }}" class="btn btn-success mb-3">+ Tambah Penerima</a>

    <div class="row g-3">
        @forelse($penerima as $item)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->program->nama_program ?? '-' }}</h5>
                        <p class="mb-1"><strong>Warga:</strong> {{ $item->warga->nama ?? '-' }}</p>
                        <p class="mb-1"><strong>Keterangan:</strong> {{ $item->keterangan ?? '-' }}</p>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="{{ route('penerima.edit',$item->penerima_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('penerima.destroy',$item->penerima_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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
        {{ $penerima->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

