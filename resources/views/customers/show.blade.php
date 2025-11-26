@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-1 text-uppercase fw-semibold small">Customer Detail</p>
            <h4 class="mb-0 fw-semibold">{{ $customer->name }}</h4>
        </div>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-5">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">Informasi Umum</div>
                <div class="card-body">
                    <p><strong>Email:</strong> {{ $customer->email ?? '-' }}</p>
                    <p><strong>Telepon:</strong> {{ $customer->phone ?? '-' }}</p>
                    <p><strong>Alamat:</strong> {{ $customer->address ?? '-' }}</p>
                    <p><strong>Catatan:</strong> {{ $customer->notes ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">File Pendukung</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('uploads.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="ref_table" value="pelanggan">
                        <input type="hidden" name="ref_id" value="{{ $customer->id }}">

                        <div class="mb-3">
                            <label class="form-label">Pilih File</label>
                            <input type="file" class="form-control" name="filename[]" multiple required>
                            <small class="text-muted">Bisa upload lebih dari satu file (doc, docx, pdf, jpg, jpeg, png - maks 2MB)</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-upload me-1"></i> Upload File
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span>Daftar File</span>
                </div>
                <div class="card-body p-0">
                    @if($files->isEmpty())
                        <p class="text-center text-muted py-4 mb-0">Belum ada file pendukung.</p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($files as $file)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ basename($file->filename) }}</strong>
                                        <div class="small text-muted">{{ $file->created_at?->format('d M Y H:i') }}</div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ asset('storage/'.$file->filename) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                                        <form action="{{ route('uploads.destroy', $file) }}" method="POST" onsubmit="return confirm('Hapus file ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

