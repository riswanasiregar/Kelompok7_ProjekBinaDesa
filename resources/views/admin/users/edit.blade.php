@extends('layouts.admin.app')

@section('title', 'Edit User')

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
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit User</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Edit Data User</h1>
            <p class="mb-0">Form untuk mengubah data user</p>
        </div>
        <div>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
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
                <form action="{{ route('users.update', $dataUsers->id) }}" method="POST" id="editUserForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Kolom 1 - Data Utama -->
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Utama</h5>

                            <!-- Nama -->
                            <div class="form-floating form-floating-outline mb-4">
                                <input
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    placeholder="Masukkan nama lengkap"
                                    value="{{ old('name', $dataUsers->name) }}"
                                    required
                                    autofocus />
                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-floating form-floating-outline mb-4">
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    placeholder="Masukkan alamat email"
                                    value="{{ old('email', $dataUsers->email) }}"
                                    required />
                                <label for="email">Email <span class="text-danger">*</span></label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                                    {{-- Role --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <select
                                    id="role"
                                    name="role"
                                    class="form-select @error('role') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Warga" {{ old('role') == 'Warga' ? 'selected' : '' }}>Warga</option>
                                </select>
                                <label for="role">Role</label>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Kolom 2 - Keamanan -->
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Keamanan</h5>

                            <!-- Password -->
                            <div class="form-floating form-floating-outline mb-4">
                                <input
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    placeholder="Kosongkan jika tidak ingin mengubah"
                                    minlength="8" />
                                <label for="password">Password Baru</label>
                                <div class="form-text text-muted">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="form-floating form-floating-outline mb-4">
                                <input
                                    type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Konfirmasi password baru" />
                                <label for="password_confirmation">Konfirmasi Password Baru</label>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading fw-bold">Informasi:</h6>
                                <ul class="mb-0">
                                    <li>Field dengan tanda <span class="text-danger">*</span> wajib diisi</li>
                                    <li>Password hanya perlu diisi jika ingin mengubah password user</li>
                                    <li>Email harus unik dan belum digunakan oleh user lain</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-gray-600">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
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
}
.form-floating.form-floating-outline .form-control:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.2);
}
</style>

@endsection
