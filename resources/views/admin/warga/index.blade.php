@extends('layouts.admin.app')
@section('title', 'Warga')
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
            <li class="breadcrumb-item"><a href="#">Warga</a></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Data Warga</h1>
            <p class="mb-0">List seluruh warga</p>
        </div>
        <div>
            <a href="{{ route('warga.create') }}" class="btn btn-success text-white">
                <i class="fas fa-plus me-1"></i> Tambah Warga
            </a>
        </div>
    </div>
</div>

@if (session('success'))
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

{{-- Filter & Search --}}
<form method="GET" action="{{ route('warga.index') }}" class="mb-3">
    <div class="row g-2">
        {{-- Filter Jenis Kelamin --}}
        <div class="col-md-3">
            <select name="jenis_kelamin" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Jenis Kelamin</option>
                <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        {{-- Search --}}
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       value="{{ request('search') }}" placeholder="Search" aria-label="Search">
                <button type="submit" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                         viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11 2q.396 0 .783.036a6 6 0 0 0-.699 1.966L11 4c-3.867 0-7 3.132-7 7s3.133 7 7 7a6.98 6.98 0 0 0 4.875-1.976l.15-.15A6.98 6.98 0 0 0 18 11l-.003-.085a6 6 0 0 0 1.966-.7a8.96 8.96 0 0 1-1.932 6.401l4.283 4.283l-1.415 1.414l-4.282-4.282A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9m5.53-.681a.507.507 0 0 1 .94 0l.254.611a4.37 4.37 0 0 0 2.25 2.326l.718.32a.53.53 0 0 1 0 .963l-.76.338a4.36 4.36 0 0 0-2.218 2.25l-.247.566a.506.506 0 0 1-.934 0l-.246-.565a4.36 4.36 0 0 0-2.22-2.251l-.76-.338a.53.53 0 0 1 0-.963l.718-.32a4.37 4.37 0 0 0 2.251-2.326z"/>
                    </svg>
                </button>
                @if(request('search'))
                    <a href="{{ request()->fullUrlWithQuery(['search'=> null]) }}" class="btn btn-outline-secondary ml-3">Clear</a>
                @endif
            </div>
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
                                <th class="border-0">No</th>
                                <th class="border-0">No. KTP</th>
                                <th class="border-0">Nama Lengkap</th>
                                <th class="border-0">Jenis Kelamin</th>
                                <th class="border-0">Agama</th>
                                <th class="border-0">Pekerjaan</th>
                                <th class="border-0">Email</th>
                                <th class="border-0">No. Telepon</th>
                                <th class="border-0 rounded-end text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = ($dataWarga->currentPage() - 1) * $dataWarga->perPage() + 1;
                            @endphp
                            @foreach($dataWarga as $data)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $data->no_ktp }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->jenis_kelamin }}</td>
                                    <td>{{ $data->agama }}</td>
                                    <td>{{ $data->pekerjaan }}</td>
                                    <td>{{ $data->email }}</td>
                                    <td>{{ $data->telp }}</td>
                                   <td class="text-center">
    <div class="d-flex justify-content-center gap-2">

        <!-- Tombol Edit -->
        <a href="{{ route('warga.edit', $data->warga_id) }}"
           class="btn btn-info btn-sm d-flex align-items-center">
            <svg class="icon icon-xs me-1" data-slot="icon" fill="none" stroke-width="1.5"
                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"></path>
            </svg>
            Edit
        </a>

        <!-- Tombol Hapus -->
        <form action="{{ route('warga.destroy', $data->warga_id) }}"
              method="POST" style="display:inline"
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
            @csrf
            @method("DELETE")
            <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center">
                <svg class="icon icon-xs me-1" fill="none" stroke-width="1.5" stroke="currentColor"
                     viewBox="0 0 24 24">
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
                        {{ $dataWarga->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
