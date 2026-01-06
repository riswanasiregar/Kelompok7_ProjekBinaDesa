@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Edit Penyaluran Bantuan</h3>

    <!-- Tampilkan error  -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('riwayat.update', $penyaluran->penyaluran_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Penerima Bantuan *</label>
            <select name="penerima_id" class="form-control" required>
                <option value="">-- Pilih Penerima --</option>
                @foreach($penerima as $p)
                    <option value="{{ $p->penerima_id }}" {{ old('penerima_id', $penyaluran->penerima_id) == $p->penerima_id ? 'selected' : '' }}>
                        {{ $p->warga->nama ?? 'Tanpa Nama' }} - {{ $p->program->nama_program ?? 'Tanpa Program' }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="program_id" value="{{ $penyaluran->program_id }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Tahap Ke *</label>
            <input type="number" name="tahap_ke" class="form-control" value="{{ old('tahap_ke', $penyaluran->tahap_ke) }}" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Penyaluran *</label>
            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $penyaluran->tanggal->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nilai Bantuan (Rp) *</label>
            <input type="number" name="nilai" class="form-control" value="{{ old('nilai', $penyaluran->nilai) }}" min="0" step="0.01" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Caption/Keterangan</label>
            <textarea name="caption" class="form-control" rows="3">{{ old('caption') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Ganti File (opsional)</label>
            <input type="file" name="media" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            <small class="form-text text-muted">Format: JPG, PNG, PDF. Maksimal 10MB.</small>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('riwayat.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection