@extends('layouts.admin.app')

@section('title', 'Tambah Riwayat Penyaluran Bantuan')

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
            <li class="breadcrumb-item"><a href="{{ route('riwayat_penyaluran_bantuan.index') }}">Riwayat Penyaluran Bantuan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Riwayat Penyaluran</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Tambah Data Penyaluran Bantuan</h1>
            <p class="mb-0">Form untuk menambahkan data penyaluran bantuan baru</p>
        </div>
        <div>
            <a href="{{ route('riwayat_penyaluran_bantuan.index') }}"
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
                <form action="{{ route('riwayat_penyaluran_bantuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        {{-- KOLOM KIRI --}}
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Data Penyaluran</h5>

                            {{-- Program Bantuan --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <select
                                    id="program_id"
                                    name="program_id"
                                    class="form-select @error('program_id') is-invalid @enderror"
                                    required
                                    onchange="filterPenerimaByProgram()">
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

                            {{-- Penerima Bantuan --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <select
                                    id="penerima_id"
                                    name="penerima_id"
                                    class="form-select @error('penerima_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Penerima Bantuan --</option>
                                    @foreach($penerima as $p)
                                        @if($p->warga)
                                            <option value="{{ $p->penerima_id }}"
                                                {{ old('penerima_id') == $p->penerima_id ? 'selected' : '' }}
                                                data-program="{{ $p->program_id }}"
                                                data-nama="{{ $p->warga->nama }}"
                                                data-nik="{{ $p->warga->no_ktp ?? '' }}">
                                                {{ $p->warga->nama }}
                                                @if($p->warga->no_ktp)
                                                    - {{ $p->warga->no_ktp }}
                                                @endif
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <label for="penerima_id">Penerima Bantuan <span class="text-danger">*</span></label>
                                @error('penerima_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Hanya menampilkan penerima dari program yang dipilih</small>
                            </div>

                            {{-- Tanggal Penyaluran --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="date"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    id="tanggal"
                                    name="tanggal"
                                    value="{{ old('tanggal', date('Y-m-d')) }}"
                                    required>
                                <label for="tanggal">Tanggal Penyaluran <span class="text-danger">*</span></label>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- KOLOM KANAN --}}
                        <div class="col-xl-6">
                            <h5 class="fw-bold text-gray-800 mb-4">Detail Penyaluran</h5>

                            {{-- Tahap Ke --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="number"
                                    class="form-control @error('tahap_ke') is-invalid @enderror"
                                    id="tahap_ke"
                                    name="tahap_ke"
                                    placeholder="Masukkan tahap ke"
                                    value="{{ old('tahap_ke', 1) }}"
                                    min="1"
                                    required>
                                <label for="tahap_ke">Tahap Ke <span class="text-danger">*</span></label>
                                @error('tahap_ke')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Contoh: 1, 2, 3, dst.</small>
                            </div>

                            {{-- Nilai Penyaluran --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="number"
                                    class="form-control @error('nilai') is-invalid @enderror"
                                    id="nilai"
                                    name="nilai"
                                    placeholder="Masukkan nilai penyaluran"
                                    value="{{ old('nilai') }}"
                                    min="0"
                                    step="1000"
                                    required>
                                <label for="nilai">Nilai Penyaluran (Rp) <span class="text-danger">*</span></label>
                                @error('nilai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: angka tanpa titik/koma</small>
                            </div>

                            {{-- Info Total yang Sudah Diterima --}}
                            <div id="totalInfo" class="alert alert-info mb-4" style="display: none;">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="totalText"></span>
                                </small>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5 class="fw-bold text-gray-800 mb-4">Dokumen Pendukung</h5>

                            {{-- File Media --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Upload Bukti Penyaluran (Opsional)</label>
                                <input type="file"
                                    name="file_media"
                                    class="form-control @error('file_media') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.pdf">
                                <div class="form-text">Format: JPG, PNG, PDF (Maks. 4MB)</div>
                                @error('file_media')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Caption Media --}}
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text"
                                    class="form-control @error('caption') is-invalid @enderror"
                                    id="caption"
                                    name="caption"
                                    placeholder="Tulis caption untuk file bukti"
                                    value="{{ old('caption') }}">
                                <label for="caption">Keterangan File</label>
                                @error('caption')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Contoh: Bukti transfer, kwitansi, foto serah terima, dll.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('riwayat_penyaluran_bantuan.index') }}"
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

@endsection

@push('scripts')
<script>
// Fungsi untuk filter penerima berdasarkan program yang dipilih
function filterPenerimaByProgram() {
    const programId = document.getElementById('program_id').value;
    const penerimaSelect = document.getElementById('penerima_id');
    const semuaOption = penerimaSelect.querySelector('option[value=""]');

    // Reset dan sembunyikan semua opsi kecuali yang pertama
    for (let i = 1; i < penerimaSelect.options.length; i++) {
        penerimaSelect.options[i].style.display = 'none';
    }

    // Tampilkan hanya penerima yang sesuai dengan program yang dipilih
    if (programId) {
        for (let i = 1; i < penerimaSelect.options.length; i++) {
            const option = penerimaSelect.options[i];
            const optionProgramId = option.getAttribute('data-program');

            if (optionProgramId == programId) {
                option.style.display = '';
            }
        }

        // Reset nilai penerima jika tidak sesuai dengan program
        if (penerimaSelect.value) {
            const selectedOption = penerimaSelect.options[penerimaSelect.selectedIndex];
            const selectedProgramId = selectedOption.getAttribute('data-program');

            if (selectedProgramId != programId) {
                penerimaSelect.value = '';
            }
        }

        // Cek total penyaluran sebelumnya
        cekTotalPenyaluran();
    } else {
        // Jika program tidak dipilih, tampilkan semua
        for (let i = 1; i < penerimaSelect.options.length; i++) {
            penerimaSelect.options[i].style.display = '';
        }
        document.getElementById('totalInfo').style.display = 'none';
    }
}

// Fungsi untuk cek total penyaluran sebelumnya
function cekTotalPenyaluran() {
    const programId = document.getElementById('program_id').value;
    const penerimaId = document.getElementById('penerima_id').value;

    if (programId && penerimaId) {
        // Kirim request AJAX untuk mendapatkan total penyaluran
        fetch(`/api/total-penyaluran/${programId}/${penerimaId}`)
            .then(response => response.json())
            .then(data => {
                const totalInfo = document.getElementById('totalInfo');
                const totalText = document.getElementById('totalText');

                if (data.total > 0) {
                    totalText.textContent = `Penerima ini sudah menerima total Rp ${formatRupiah(data.total)} dari program ini (${data.jumlah_tahap} tahap).`;
                    totalInfo.style.display = 'block';
                } else {
                    totalInfo.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('totalInfo').style.display = 'none';
            });
    }
}

// Fungsi untuk format Rupiah
function formatRupiah(angka) {
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Event listener untuk perubahan penerima
document.getElementById('penerima_id').addEventListener('change', function() {
    cekTotalPenyaluran();
});

// Panggil fungsi filter saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    filterPenerimaByProgram();

    // Validasi form sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const programId = document.getElementById('program_id').value;
        const penerimaId = document.getElementById('penerima_id').value;

        if (!programId || !penerimaId) {
            e.preventDefault();
            alert('Silakan pilih program dan penerima bantuan.');
            return false;
        }

        // Validasi nilai tidak boleh 0
        const nilai = document.getElementById('nilai').value;
        if (nilai <= 0) {
            e.preventDefault();
            alert('Nilai penyaluran harus lebih dari 0.');
            document.getElementById('nilai').focus();
            return false;
        }
    });
});

// Format nilai input dengan Rupiah
document.getElementById('nilai').addEventListener('input', function(e) {
    const value = e.target.value.replace(/[^0-9]/g, '');
    e.target.value = value;
});
</script>
@endpush

@push('styles')
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

/* Style untuk form validation */
.is-invalid {
    border-color: #dc3545 !important;
}
.invalid-feedback {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}
</style>
@endpush
