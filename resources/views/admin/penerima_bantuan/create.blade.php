@extends('layouts.admin.app')

@section('title', 'Tambah Penerima Bantuan')

@section('content')
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="#">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('admin.penerima_bantuan.index') }}">Penerima Bantuan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Penerima Bantuan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Tambah Data Penerima Bantuan</h1>
            <p class="mb-0">Form untuk menambahkan data penerima bantuan baru</p>
        </div>
        <div>
            <a href="{{ route('admin.penerima_bantuan.index') }}"
               class="btn btn-outline-secondary d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
</div>

{{-- Error --}}
@if ($errors->any())
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>Terjadi kesalahan input </strong><br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

{{-- Success --}}
@if (session('success'))
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

{{-- Error khusus dari controller --}}
@if (session('error'))
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow components-section">
            <div class="card-body">
                <form action="{{ route('admin.penerima_bantuan.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        {{-- KOLOM KIRI --}}
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Program Bantuan</h5>

                            {{-- Pilih Program Bantuan --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <select
                                    id="program_id"
                                    name="program_id"
                                    class="form-select @error('program_id') is-invalid @enderror"
                                    required
                                    onchange="loadWargaByProgram()">
                                    <option value="">-- Pilih Program Bantuan --</option>
                                    @foreach($program as $p)
                                        <option value="{{ $p->program_id }}"
                                            {{ old('program_id') == $p->program_id ? 'selected' : '' }}
                                            data-anggaran="{{ $p->anggaran }}"
                                            data-tahun="{{ $p->tahun }}">
                                            {{ $p->nama_program }} ({{ $p->tahun }})
                                            @if($p->anggaran)
                                                - Rp {{ number_format($p->anggaran, 0, ',', '.') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <label for="program_id">Program Bantuan <span class="text-danger">*</span></label>
                                @error('program_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Info Program Terpilih --}}
                            <div id="programInfo" class="alert alert-info mb-4" style="display: none;">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="programDetail"></span>
                                </small>
                            </div>

                        </div>

                        {{-- KOLOM KANAN --}}
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Warga Penerima</h5>

                            {{-- Pilih Warga --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <select
                                    id="warga_id"
                                    name="warga_id"
                                    class="form-select @error('warga_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Warga --</option>
                                    @foreach($warga as $w)
                                        <option value="{{ $w->warga_id }}"
                                            {{ old('warga_id') == $w->warga_id ? 'selected' : '' }}
                                            data-nik="{{ $w->no_ktp ?? '-' }}"
                                            data-alamat="{{ $w->alamat ?? '-' }}">
                                            {{ $w->nama }}
                                            @if($w->no_ktp)
                                                - {{ $w->no_ktp }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <label for="warga_id">Nama Warga <span class="text-danger">*</span></label>
                                @error('warga_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Info Warga Terpilih --}}
                            <div id="wargaInfo" class="alert alert-info mb-4" style="display: none;">
                                <small>
                                    <i class="fas fa-user me-1"></i>
                                    <span id="wargaDetail"></span>
                                </small>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5 class="fw-bold text-gray-800 mb-4">Informasi Tambahan</h5>

                            {{-- Keterangan --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <textarea
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    id="keterangan"
                                    name="keterangan"
                                    placeholder="Tulis keterangan tambahan"
                                    rows="3">{{ old('keterangan') }}</textarea>
                                <label for="keterangan">Keterangan (Opsional)</label>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Contoh: Alasan pemilihan, kondisi khusus, dll.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.penerima_bantuan.index') }}"
                                   class="btn btn-outline-gray-600">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Simpan Data
                                </button>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<style>
.form-floating.form-floating-outline .form-control,
.form-floating.form-floating-outline .form-select {
    border: 1px solid #d9dee3;
    border-radius: 0.375rem;
}
.form-floating.form-floating-outline .form-control:focus,
.form-floating.form-floating-outline .form-select:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.2);
}
</style>

@endsection
