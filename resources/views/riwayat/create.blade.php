@extends('layouts.app')

@section('content')
<div class="container">
    <div  style="margin-top: 100px;">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Tambah Penyaluran Bantuan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('riwayat.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="penerima_id" class="form-label">Penerima Bantuan</label>
                    <select name="penerima_id" id="penerima_id" class="form-control @error('penerima_id') is-invalid @enderror" required onchange="updateProgramId()">
                        <option value="">-- Pilih Penerima --</option>
                        @foreach($penerima as $p)
                            <option value="{{ $p->penerima_id }}" data-program-id="{{ $p->program_id }}" {{ old('penerima_id') == $p->penerima_id ? 'selected' : '' }}>
                                {{ $p->warga->nama ?? 'Tanpa Nama' }} - {{ $p->program->nama_program ?? 'Tanpa Program' }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="program_id" id="program_id" value="{{ old('program_id') }}">
                    @error('penerima_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tahap_ke" class="form-label">Tahap Ke</label>
                        <input type="number" name="tahap_ke" class="form-control @error('tahap_ke') is-invalid @enderror" value="{{ old('tahap_ke', 1) }}" min="1" required>
                        @error('tahap_ke') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tanggal" class="form-label">Tanggal Penyaluran</label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nilai" class="form-label">Nilai Bantuan (Rp)</label>
                    <input type="text" name="nilai" id="nilai" class="form-control @error('nilai') is-invalid @enderror" value="{{ old('nilai') }}" placeholder="Contoh: 300.000" required onkeyup="formatRupiah(this)">
                    @error('nilai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('riwayat.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateProgramId() {
        var select = document.getElementById('penerima_id');
        var selectedOption = select.options[select.selectedIndex];
        var programId = selectedOption.getAttribute('data-program-id');
        document.getElementById('program_id').value = programId;
    }

    // Jalankan saat halaman dimuat (jika ada old input)
    document.addEventListener("DOMContentLoaded", function() {
        if(document.getElementById('penerima_id').value) {
            updateProgramId();
        }
    });

    function formatRupiah(element) {
        let value = element.value.replace(/[^,\d]/g, '').toString();
        let split = value.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        element.value = rupiah;
    }
</script>
@endsection