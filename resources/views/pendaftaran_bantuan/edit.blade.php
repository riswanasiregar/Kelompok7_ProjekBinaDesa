@extends('layouts.app')

@section('content')

    <div class="container">
    <div style="margin-top: 100px;">
    <div class="card">
    <h3 class="mb-0 fw-semibold" >Edit Pendaftar Bantuan </h3>


    <form action="{{ route('pendaftar-bantuan.update', $data->pendaftar_bantuan_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Warga</label>
            <select name="warga_id" class="form-control" required>
                @foreach ($warga as $w)
                    <option value="{{ $w->warga_id }}" {{ $data->warga_id == $w->warga_id ? 'selected' : '' }}>
                        {{ $w->nama }} (NIK: {{ $w->no_ktp }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Program Bantuan</label>
            <select name="program_id" class="form-control" required>
                @foreach ($program as $p)
                    <option value="{{ $p->program_id }}" {{ $data->program_id == $p->program_id ? 'selected' : '' }}>
                        {{ $p->nama_program }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal Daftar</label>
            <input type="date" name="tanggal_daftar" value="{{ $data->tanggal_daftar }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="Diproses" {{ $data->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="Diterima" {{ $data->status == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="Ditolak" {{ $data->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control">{{ $data->keterangan }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
         <a href="{{ route('pendaftar-bantuan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection