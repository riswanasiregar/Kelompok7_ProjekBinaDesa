@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-1 text-uppercase fw-semibold small">Pendaftaran</p>
        <h4 class="mb-0 fw-semibold">Tambah Pendaftar Bantuan</h4>
    </div>
    <a href="{{ route('pendaftar-bantuan.index') }}" class="btn btn-light btn-sm">
        <i class="ti ti-arrow-left me-1"></i> Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <strong>Periksa lagi!</strong>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('pendaftar-bantuan.store') }}" method="POST" class="row gy-3">
            @csrf

            <div class="col-12 col-md-6">
                <label class="form-label">Nama Warga <span class="text-danger">*</span></label>
                <input type="text" name="warga_name" class="form-control warga-input" list="warga-list"
                    placeholder="Ketik nama warga..." value="{{ old('warga_name') }}" autocomplete="off" required>
                <input type="hidden" name="warga_id" value="{{ old('warga_id') }}">
                <datalist id="warga-list">
                    @foreach ($warga as $w)
                        <option value="{{ $w->nama }}" data-id="{{ $w->warga_id }}" data-ktp="{{ $w->no_ktp }}"></option>
                    @endforeach
                </datalist>
                <small class="text-muted">Ketik minimal 3 huruf lalu pilih nama warga yang muncul.</small>
                <div class="invalid-feedback d-block warga-feedback" style="display:none;"></div>
                <div class="mt-2">
                    <a href="{{ route('warga.create') }}" class="btn btn-link btn-sm p-0">
                        <i class="ti ti-user-plus me-1"></i> Tambah warga baru
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Program Bantuan <span class="text-danger">*</span></label>
                <select name="program_id" class="form-select" required>
                    <option value="" disabled selected>Pilih program</option>
                    @foreach ($program as $p)
                        <option value="{{ $p->program_id }}" {{ old('program_id') == $p->program_id ? 'selected' : '' }}>
                            {{ $p->nama_program }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Tanggal Daftar <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_daftar" value="{{ old('tanggal_daftar') }}" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    @foreach (['Diproses', 'Diterima', 'Ditolak'] as $status)
                        <option value="{{ $status }}" {{ old('status', 'Diproses') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" rows="3" class="form-control" placeholder="Opsional">{{ old('keterangan') }}</textarea>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                <a href="{{ route('pendaftar-bantuan.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.warga-input');
        const optionMap = {};
        document.querySelectorAll('#warga-list option').forEach(option => {
            if (option.dataset.id) {
                optionMap[option.value] = option.dataset.id;
            }
        });

        inputs.forEach(input => {
            const wrapper = input.closest('.col-12');
            const hidden = wrapper.querySelector('input[name="warga_id"]');
            const feedback = wrapper.querySelector('.warga-feedback');

            function syncWarga() {
                const value = input.value.trim();
                if (value && optionMap[value]) {
                    hidden.value = optionMap[value];
                    feedback.style.display = 'none';
                } else {
                    hidden.value = '';
                    if (value.length > 0) {
                        feedback.style.display = 'block';
                        feedback.textContent = 'Nama tidak ditemukan. Silakan pilih dari daftar Data Warga atau tambahkan warga baru.';
                    } else {
                        feedback.style.display = 'none';
                    }
                }
            }

            ['change', 'blur', 'input'].forEach(evt => input.addEventListener(evt, syncWarga));
            syncWarga();
        });
    });
</script>
@endpush
@endsection