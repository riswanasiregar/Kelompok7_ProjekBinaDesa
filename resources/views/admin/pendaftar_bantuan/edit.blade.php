@extends('layouts.admin.app')

@section('title', 'Edit Pendaftar Bantuan')

@section('content')
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
            <li class="breadcrumb-item"><a href="{{ route('pendaftar_bantuan.index') }}">Pendaftar Bantuan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Pendaftar Bantuan</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Edit Data Pendaftar Bantuan</h1>
            <p class="mb-0">Form untuk mengubah data pendaftar bantuan</p>
        </div>
        <div>
            <a href="{{ route('pendaftar_bantuan.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
</div>

<!-- Pesan Error di luar card -->
@if ($errors->any())
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>Terjadi kesalahan input </strong> <br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

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
    <div class="col-12">
        <div class="card border-0 shadow components-section">
            <div class="card-body">
                <!-- PERBAIKAN: Tambahkan parameter $pendaftar ke route update -->
                <form action="{{ route('pendaftar_bantuan.update', $pendaftar) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Kolom 1 - Data Warga -->
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Warga</h5>

                            <!-- Pilih Warga -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Nama Warga <span class="text-danger">*</span></label>
                                <select
                                    id="warga_id"
                                    name="warga_id"
                                    class="form-select @error('warga_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Warga --</option>
                                    @foreach($warga as $w)
                                        <option value="{{ $w->warga_id }}"
                                            {{ old('warga_id', $pendaftar->warga_id) == $w->warga_id ? 'selected' : '' }}>
                                            {{ $w->nama }}
                                            @if($w->nik)
                                                - {{ $w->nik }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('warga_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Daftar -->
                            <div class="form-floating form-floating-outline mb-4">
                                <input
                                    type="date"
                                    class="form-control @error('tanggal_daftar') is-invalid @enderror"
                                    id="tanggal_daftar"
                                    name="tanggal_daftar"
                                    value="{{ old('tanggal_daftar', \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->format('Y-m-d')) }}"
                                    required />
                                <label for="tanggal_daftar">Tanggal Daftar <span class="text-danger">*</span></label>
                                @error('tanggal_daftar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Kolom 2 - Data Program -->
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Program</h5>

                            <!-- Pilih Program Bantuan -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Program Bantuan <span class="text-danger">*</span></label>
                                <select
                                    id="program_id"
                                    name="program_id"
                                    class="form-select @error('program_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Program Bantuan --</option>
                                    @foreach($program as $p)
                                        <option value="{{ $p->program_id }}"
                                            {{ old('program_id', $pendaftar->program_id) == $p->program_id ? 'selected' : '' }}>
                                            {{ $p->nama_program }} ({{ $p->tahun }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('program_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <select
                                    id="status"
                                    name="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Diproses" {{ old('status', $pendaftar->status) == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="Diterima" {{ old('status', $pendaftar->status) == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="Ditolak" {{ old('status', $pendaftar->status) == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="mb-4">
                                <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                                <textarea
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    id="keterangan"
                                    name="keterangan"
                                    rows="3"
                                    placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan', $pendaftar->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('pendaftar_bantuan.index') }}" class="btn btn-outline-gray-600">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
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
.form-floating.form-floating-outline .form-control {
    border: 1px solid #d9dee3;
    border-radius: 0.375rem;
    transition: all 0.2s ease-in-out;
}

.form-floating.form-floating-outline .form-control:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.2);
}

.form-floating.form-floating-outline label {
    color: #6c757d;
    transition: all 0.2s ease-in-out;
}

.form-floating.form-floating-outline .form-control:focus ~ label,
.form-floating.form-floating-outline .form-control:not(:placeholder-shown) ~ label {
    color: #696cff;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    background: white;
    padding: 0 0.25rem;
    margin-left: -0.25rem;
}
</style>
@endsection
