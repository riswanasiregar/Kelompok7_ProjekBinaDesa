@extends('layouts.admin.app')

@section('title', 'Edit Penerima Bantuan')

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
            <li class="breadcrumb-item"><a href="{{ route('penerima_bantuan.index') }}">Penerima Bantuan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Penerima Bantuan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Edit Data Penerima Bantuan</h1>
            <p class="mb-0">Form untuk mengubah data penerima bantuan</p>
        </div>
        <div>
            <a href="{{ route('penerima_bantuan.index') }}"
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
                <form action="{{ route('penerima_bantuan.update', $penerima->penerima_id) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                                    onchange="updateProgramInfo()">
                                    <option value="">-- Pilih Program Bantuan --</option>
                                    @foreach($program as $p)
                                        <option value="{{ $p->program_id }}"
                                            {{ old('program_id', $penerima->program_id) == $p->program_id ? 'selected' : '' }}
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
                                            {{ old('warga_id', $penerima->warga_id) == $w->warga_id ? 'selected' : '' }}
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
                                    rows="3">{{ old('keterangan', $penerima->keterangan) }}</textarea>
                                <label for="keterangan">Keterangan (Opsional)</label>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Contoh: Alasan pemilihan, kondisi khusus, dll.</small>
                            </div>

                            {{-- Info Penyaluran (Readonly) --}}
                            @if($penerima->penyaluran()->exists())
                                <div class="alert alert-warning mb-4">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Peringatan
                                    </h6>
                                    <p class="mb-0">
                                        Penerima ini sudah memiliki riwayat penyaluran sebanyak
                                        <strong>{{ $penerima->jumlah_tahap_diberikan }} tahap</strong> dengan total
                                        <strong>Rp {{ number_format($penerima->total_diterima, 0, ',', '.') }}</strong>.
                                        Perubahan data tidak mempengaruhi riwayat penyaluran yang sudah ada.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('penerima_bantuan.index') }}"
                                   class="btn btn-outline-gray-600">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update Data
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update info program saat dipilih
        const programSelect = document.getElementById('program_id');
        const wargaSelect = document.getElementById('warga_id');
        const programInfo = document.getElementById('programInfo');
        const programDetail = document.getElementById('programDetail');
        const wargaInfo = document.getElementById('wargaInfo');
        const wargaDetail = document.getElementById('wargaDetail');

        // Fungsi untuk menampilkan info program
        function updateProgramInfo() {
            const selectedOption = programSelect.options[programSelect.selectedIndex];
            if (programSelect.value && selectedOption) {
                const anggaran = selectedOption.getAttribute('data-anggaran');
                const tahun = selectedOption.getAttribute('data-tahun');

                let infoText = `Program: ${selectedOption.text}`;
                if (anggaran && anggaran !== 'null') {
                    infoText += ` | Anggaran: Rp ${formatRupiah(anggaran)}`;
                }
                if (tahun && tahun !== 'null') {
                    infoText += ` | Tahun: ${tahun}`;
                }

                programDetail.textContent = infoText;
                programInfo.style.display = 'block';
            } else {
                programInfo.style.display = 'none';
            }
        }

        // Fungsi untuk menampilkan info warga
        function updateWargaInfo() {
            const selectedOption = wargaSelect.options[wargaSelect.selectedIndex];
            if (wargaSelect.value && selectedOption) {
                const nik = selectedOption.getAttribute('data-nik');
                const alamat = selectedOption.getAttribute('data-alamat');

                let infoText = `Nama: ${selectedOption.text}`;
                if (nik && nik !== 'null' && nik !== '-') {
                    infoText += ` | NIK: ${nik}`;
                }
                if (alamat && alamat !== 'null' && alamat !== '-') {
                    infoText += ` | Alamat: ${alamat.substring(0, 50)}${alamat.length > 50 ? '...' : ''}`;
                }

                wargaDetail.textContent = infoText;
                wargaInfo.style.display = 'block';
            } else {
                wargaInfo.style.display = 'none';
            }
        }

        // Format rupiah
        function formatRupiah(angka) {
            if (!angka) return '0';
            const number_string = angka.toString().replace(/[^,\d]/g, '');
            const split = number_string.split(',');
            const sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                const separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        // Event listeners
        programSelect.addEventListener('change', updateProgramInfo);
        wargaSelect.addEventListener('change', updateWargaInfo);

        // Inisialisasi info
        updateProgramInfo();
        updateWargaInfo();
    });
</script>
@endpush
