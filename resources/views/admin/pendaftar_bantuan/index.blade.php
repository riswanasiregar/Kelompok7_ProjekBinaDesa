@extends('layouts.admin.app')
@section('title', 'Pendaftar Bantuan')

@section('content')
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="#">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('pendaftar_bantuan.index') }}">Pendaftar Bantuan</a></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap mb-3">
        <div>
            <h1 class="h4">Data Pendaftar Bantuan</h1>
            <p class="mb-0">List seluruh pendaftar bantuan</p>
        </div>
        <div>
            <a href="{{ route('pendaftar_bantuan.create') }}" class="btn btn-success text-white">
                <i class="fas fa-plus me-1"></i> Tambah Pendaftar
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

{{-- Filter Program & Status --}}
<form method="GET" action="{{ route('pendaftar_bantuan.index') }}" class="mb-3 row g-2">
    <div class="col-md-3">
        <select name="program_id" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Program</option>
            @foreach($programs as $program)
                <option value="{{ $program->program_id }}" {{ request('program_id') == $program->program_id ? 'selected' : '' }}>
                    {{ $program->nama_program }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="status_seleksi" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status_seleksi')=='pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="diterima" {{ request('status_seleksi')=='diterima' ? 'selected' : '' }}>Diterima</option>
            <option value="ditolak" {{ request('status_seleksi')=='ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>
    <div class="col-md-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search"
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
            @if(request('search'))
                <a href="{{ route('pendaftar_bantuan.index') }}" class="btn btn-outline-secondary">Clear</a>
            @endif
        </div>
    </div>
</form>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Warga</th>
                                <th>Program Bantuan</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Media</th>
                                <th class="text-center rounded-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = ($pendaftar->currentPage()-1) * $pendaftar->perPage() + 1;
                            @endphp
                            @foreach($pendaftar as $data)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $data->warga->nama ?? 'N/A' }}</td>
                                    <td>{{ $data->program->nama_program ?? 'N/A' }}</td>
                                    <td>{{ $data->tanggal_daftar->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $data->status_label['class'] }}">
                                            {{ $data->status_label['label'] }}
                                        </span>
                                    </td>
                                    <td>{{ $data->keterangan ?? '-' }}</td>
                                    <td>
                                        @php $media = $data->media->first() @endphp
                                        @if($media && Storage::disk('public')->exists($media->path))
                                            <img src="{{ asset('storage/' . $media->path) }}" alt="Media"
                                                 style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Edit -->
                                            <a href="{{ route('pendaftar_bantuan.edit', $data) }}" class="btn btn-info btn-sm d-flex align-items-center">
                                                <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            <!-- Hapus -->
                                            <form action="{{ route('pendaftar_bantuan.destroy', $data) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center">
                                                    <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166
                                                                 m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084
                                                                 a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79
                                                                 m14.456 0a48.108 48.108 0 0 0-3.478-.397
                                                                 m-12 .562c.34-.059.68-.114 1.022-.165
                                                                 m0 0a48.11 48.11 0 0 1 3.478-.397
                                                                 m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201
                                                                 a51.964 51.964 0 0 0-3.32 0
                                                                 c-1.18.037-2.09 1.022-2.09 2.201v.916
                                                                 m7.5 0a48.667 48.667 0 0 0-7.5 0">
                                                        </path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3 d-flex justify-content-end">
                        {{ $pendaftar->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
