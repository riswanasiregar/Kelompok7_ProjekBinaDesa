@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Tambah Penerima Bantuan</h3>

    <form action="{{ route('penerima.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Program Bantuan</label>
            <select name="program_id" class="form-select @error('program_id') is-invalid @enderror">
                <option value="">-- Pilih Program --</option>
                @foreach($program as $p)
                    <option value="{{ $p->program_id }}" {{ old('program_id')==$p->program_id?'selected':'' }}>
                        {{ $p->nama_program ?? $p->program_nama }}
                    </option>
                @endforeach
            </select>
            @error('program_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Warga</label>
            <select name="warga_id" class="form-select @error('warga_id') is-invalid @enderror">
                <option value="">-- Pilih Warga --</option>
                @foreach($warga as $w)
                    <option value="{{ $w->warga_id }}" {{ old('warga_id')==$w->warga_id?'selected':'' }}>
                        {{ $w->nama }}
                    </option>
                @endforeach
            </select>
            @error('warga_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
        </div>
          <!-- Tambahkan field status -->
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror">
                <option value="">-- Pilih Status --</option>
                <option value="Sudah Menerima" {{ old('status')=='Aktif' ? 'selected' : '' }}>Sudah Menerima</option>
                <option value="Belum Menerima" {{ old('status')=='Nonaktif' ? 'selected' : '' }}>Belum Menerima</option>
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('penerima.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection



