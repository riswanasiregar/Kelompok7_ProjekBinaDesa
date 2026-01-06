@extends('layouts.app')

@section('content')
<div class="container">
    <div style="margin-top: 100px;">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 fw-semibold">Edit Pendaftar Bantuan</h3>
            </div>
            <div class="card-body">
                <!-- Tampilkan error -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pendaftar-bantuan.update', $data->pendaftar_bantuan_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Warga *</label>
                        <select name="warga_id" class="form-control" required>
                            @foreach ($warga as $w)
                                <option value="{{ $w->warga_id }}" {{ $data->warga_id == $w->warga_id ? 'selected' : '' }}>
                                    {{ $w->nama }} (NIK: {{ $w->no_ktp }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Program Bantuan *</label>
                        <select name="program_id" class="form-control" required>
                            @foreach ($program as $p)
                                <option value="{{ $p->program_id }}" {{ $data->program_id == $p->program_id ? 'selected' : '' }}>
                                    {{ $p->nama_program }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ $data->keterangan }}</textarea>
                    </div>



                    <div class="mb-3">
                        <label class="form-label">Ganti Foto (opsional)</label>
                        <input type="file" name="media" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        <small class="form-text text-muted">Format: JPG, PNG, PDF. Maksimal 10MB.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('pendaftar-bantuan.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
