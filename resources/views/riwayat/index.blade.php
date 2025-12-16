@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Riwayat Penyaluran Bantuan</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tampilkan Error Validasi jika ada --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(isset($program))
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
            <a href="{{ route('riwayat.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    @endif

    <a href="{{ route('riwayat.create') }}" class="btn btn-success mb-3">+ Tambah Penyaluran</a>

    <div class="row g-3">
        @forelse($penyaluran as $item)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->program->nama_program ?? '-' }}</h5>
                        <p class="mb-1"><strong>Warga:</strong> {{ $item->penerima->warga->nama ?? '-' }}</p>
                        <p class="mb-1"><strong>Tanggal:</strong> {{ $item->tanggal->format('d-m-Y') }}</p>
                        <p class="mb-1"><strong>Tahap:</strong> {{ $item->tahap_ke }}</p>
                        <p class="mb-1"><strong>Nilai:</strong> Rp {{ number_format($item->nilai, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="{{ route('riwayat.edit', $item->penyaluran_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('riwayat.destroy', $item->penyaluran_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center mb-0">Belum ada data</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">{{ $penyaluran->links('pagination::bootstrap-5') }}</div>
</div>
@endsection