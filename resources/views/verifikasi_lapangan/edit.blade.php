@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Edit Verifikasi Lapangan</h3>

    <form action="{{ route('verifikasi.update', $data->verifikasi_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Pendaftar</label>
            <select name="pendaftar_bantuan_id" class="form-control" required>
                @foreach ($pendaftar as $p)
                    <option value="{{ $p->pendaftar_bantuan_id }}" 
                        {{ $data->pendaftar_bantuan_id == $p->pendaftar_bantuan_id ? 'selected' : '' }}>
                        {{ $p->warga->nama ?? 'Nama tidak ditemukan' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Petugas</label>
            <input type="text" name="petugas" class="form-control" value="{{ $data->petugas }}" required>
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $data->tanggal->format('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="catatan" rows="3" class="form-control">{{ $data->catatan }}</textarea>
        </div>

        <div class="mb-3">
            <label>Skor</label>
            <input type="number" name="skor" class="form-control" min="0" max="100" value="{{ $data->skor }}" required>
        </div>

        <div class="mb-3">
            <label>Status Verifikasi</label>
            <select name="status_verifikasi" class="form-control" required>
                <option value="menunggu" {{ $data->status_verifikasi == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="diverifikasi" {{ $data->status_verifikasi == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                <option value="ditolak" {{ $data->status_verifikasi == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Media Lama</label><br>
            @if ($data->media->count())
                <a href="{{ asset('storage/' . $data->media->first()->file_path) }}" target="_blank" class="btn btn-info btn-sm">Lihat Media</a>
            @else
                <span class="text-muted">Tidak ada media</span>
            @endif
        </div>

        <div class="mb-3">
            <label>Upload Media Baru (Opsional)</label>
            <input type="file" name="file_media" class="form-control">
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('verifikasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
