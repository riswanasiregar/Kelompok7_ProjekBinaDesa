@extends('layouts.app')

@section('content')
<div class="container">
    <div  style="margin-top: 100px;">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0 fw-semibold">Tambah Pendaftar Bantuan</h3>
            <a href="{{ route('pendaftar-bantuan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <a href="{{ route('pendaftar-bantuan.index') }}" class="btn btn-secondary mb-3">Kembali</a>

            <form action="{{ route('pendaftar-bantuan.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Nama Warga</label>
                    <select name="warga_id" class="form-control" required>
                        <option value="">-- Pilih Warga --</option>
                        @foreach ($warga as $w)
                            <option value="{{ $w->warga_id }}">{{ $w->nama }} (NIK: {{ $w->no_ktp }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Program Bantuan</label>
                    <select name="program_id" class="form-control" required>
                        <option value="">-- Pilih Program --</option>
                        @foreach ($program as $p)
                            <option value="{{ $p->program_id }}">{{ $p->nama_program }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tanggal Daftar</label>
                    <input type="date" name="tanggal_daftar" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Diproses">Diproses</option>
                        <option value="Diterima">Diterima</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection