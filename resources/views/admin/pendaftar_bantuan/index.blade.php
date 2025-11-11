@extends('layouts.admin.app')
@section('title', 'Pendaftar Bantuan')

@section('content')
<!-- Start main content -->
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="#">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item"><a href="#">Pendaftar Bantuan</a></li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Data Pendaftar Bantuan</h1>
            <p class="mb-0">List data seluruh pendaftar bantuan</p>
        </div>
        <div>
            <a href="{{ route('pendaftar_bantuan.create') }}" class="btn btn-success text-white">
                <i class="fas fa-plus me-1"></i> Tambah Pendaftar
            </a>
        </div>
    </div>
</div>

@if (session('success'))
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-pendaftar-bantuan" class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">No</th>
                                <th class="border-0">Nama Warga</th>
                                <th class="border-0">Program Bantuan</th>
                                <th class="border-0">Tanggal Daftar</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Keterangan</th>
                                <th class="border-0 rounded-end text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendaftar as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->warga->nama ?? 'N/A' }}</td>
                                    <td>{{ $data->program->nama_program ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->tanggal_daftar)->format('d/m/Y') }}</td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'Diproses' => ['label' => 'Diproses', 'class' => 'bg-label-warning'],
                                                'Diterima' => ['label' => 'Diterima', 'class' => 'bg-label-success'],
                                                'Ditolak' => ['label' => 'Ditolak', 'class' => 'bg-label-danger'],
                                            ];

                                            $config = $statusConfig[$data->status] ?? ['label' => $data->status, 'class' => 'bg-label-secondary'];
                                        @endphp
                                        <span class="badge rounded-pill {{ $config['class'] }}">{{ $config['label'] }}</span>
                                    </td>
                                    <td>{{ $data->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- PERBAIKAN: Gunakan $data sebagai parameter -->
                                            <a href="{{ route('pendaftar_bantuan.edit', $data) }}" class="btn btn-info btn-sm d-flex align-items-center">
                                                <svg class="icon icon-xs me-1" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            <!-- PERBAIKAN: Gunakan $data sebagai parameter -->
                                            <form action="{{ route('pendaftar_bantuan.destroy', $data) }}" method="POST" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method("DELETE")
                                                <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center">
                                                    <svg class="icon icon-xs me-1" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
