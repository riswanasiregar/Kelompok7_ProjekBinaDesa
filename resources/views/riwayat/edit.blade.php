@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Edit Penyaluran Bantuan</h3>

    <form action="{{ route('riwayat.update', $riwayat->penyaluran_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Penerima Bantuan</label>
            <select name="penerima_id" class="form-select @error('penerima_id') is-invalid @enderror">
                <option value="">-- Pilih Penerima --</option>
                @foreach($penerima as $p)
                    <option value="{{ $p->penerima_id }}" {{ old('penerima_id', $riwayat->penerima_id) == $p->penerima_id ? 'selected' : '' }}>
                        {{ $p->warga->nama ?? 'Tanpa Nama' }} - {{ $p->program->nama_program ?? 'Tanpa Program' }}
                    </option>
                @endforeach
            </select>
            @error('penerima_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Tahap Ke</label>
            <input type="number" name="tahap_ke" class="form-control @error('tahap_ke') is-invalid @enderror" value="{{ old('tahap_ke', $riwayat->tahap_ke) }}" min="1">
            @error('tahap_ke')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Penyaluran</label>
            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $riwayat->tanggal->format('Y-m-d')) }}">
            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Nilai Bantuan (Rp)</label>
            <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror" value="{{ old('nilai', $riwayat->nilai) }}">
            @error('nilai')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('riwayat.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection