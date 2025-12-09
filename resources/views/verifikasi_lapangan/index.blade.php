@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Daftar Verifikasi Lapangan</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('verifikasi.create') }}" class="btn btn-primary">+ Tambah Verifikasi</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pendaftar</th>
                <th>Petugas</th>
                <th>Tanggal</th>
                <th>Skor</th>
                <th>Status</th>
                <th>Media</th>
                <th width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($verifikasi as $item)
                <tr>
                    <td>{{ $item->verifikasi_id }}</td>
                    <td>{{ $item->pendaftar->warga->nama ?? '-' }}</td>
                    <td>{{ $item->petugas }}</td>
                    <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                    <td>
                        {{ $item->skor }} <br>
                        <small class="text-muted">({{ $item->kategori_skor }})</small>
                    </td>
                    <td>
                        <span class="badge {{ $item->status_label['class'] }}">
                            {{ $item->status_label['label'] }}
                        </span>
                    </td>
                    <td>
                        @if ($item->media->count())
                            <a href="{{ asset('storage/' . $item->media->first()->file_path) }}" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                        @else
                            <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('verifikasi.edit', $item->verifikasi_id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('verifikasi.destroy', $item->verifikasi_id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf 
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $verifikasi->links() }}
</div>
@endsection
