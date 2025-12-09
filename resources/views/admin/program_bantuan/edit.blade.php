@extends('layouts.admin.app')

@section('title', 'Edit Program Bantuan')

@section('content')
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="#">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('program_bantuan.index') }}">Program Bantuan</a></li>
            <li class="breadcrumb-item active">Edit Program Bantuan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div>
            <h1 class="h4">Edit Program Bantuan</h1>
            <p class="mb-0">Update informasi program bantuan</p>
        </div>
          <div>
            <a href="{{ route('program_bantuan.index') }}"
               class="btn btn-outline-secondary d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
</div>

{{-- Error --}}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible">
    <strong>Terdapat kesalahan input:</strong>
    <ul class="mt-2 mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Success --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card shadow border-0">
    <div class="card-body">
        <form action="{{ route('program_bantuan.update', $program->program_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Kiri --}}
                <div class="col-xl-6">
                    <h5 class="fw-bold mb-4">Data Program</h5>

                    {{-- Kode --}}
                    <div class="form-floating mb-4">
                        <input type="text" name="kode" id="kode"
                            class="form-control @error('kode') is-invalid @enderror"
                            value="{{ old('kode', $program->kode) }}" required>
                        <label for="kode">Kode Program *</label>
                    </div>

                    {{-- Nama --}}
                    <div class="form-floating mb-4">
                        <input type="text" name="nama_program" id="nama_program"
                            class="form-control @error('nama_program') is-invalid @enderror"
                            value="{{ old('nama_program', $program->nama_program) }}" required>
                        <label for="nama_program">Nama Program *</label>
                    </div>

                    {{-- Tahun --}}
                    <div class="form-floating mb-4">
                        <input type="number" name="tahun" id="tahun"
                            class="form-control @error('tahun') is-invalid @enderror"
                            value="{{ old('tahun', $program->tahun) }}">
                        <label for="tahun">Tahun</label>
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="col-xl-6">
                    <h5 class="fw-bold mb-4">Detail Program</h5>

                    {{-- Anggaran --}}
                    <div class="form-floating mb-4">
                        <input type="number" step="0.01" name="anggaran" id="anggaran"
                            class="form-control @error('anggaran') is-invalid @enderror"
                            value="{{ old('anggaran', $program->anggaran) }}">
                        <label for="anggaran">Anggaran</label>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                            class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $program->deskripsi) }}</textarea>
                    </div>

                    {{-- Media lama --}}
                    <h6 class="fw-bold mb-2">Media Terupload</h6>

                    @forelse ($program->media as $media)
                        <div class="media-item d-flex justify-content-between align-items-center p-3 border rounded mb-2">
                            <div class="d-flex align-items-center gap-3">
                                @if($media->is_image)
                                    <img src="{{ $media->full_url }}" style="width:60px;height:60px;object-fit:cover;border-radius:4px;">
                                @else
                                    <i class="fas fa-file fa-2x text-secondary"></i>
                                @endif

                                <div>
                                    <strong>{{ $media->display_name }}</strong><br>
                                    <small class="text-muted">{{ $media->mime_type }}</small>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-danger delete-media"
                                data-id="{{ $media->media_id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @empty
                        <div class="alert alert-info">Belum ada file</div>
                    @endforelse

                </div>
            </div>

            {{-- Upload baru --}}
            <div class="border-top pt-4 mt-4">
                <h5 class="fw-bold mb-3">Upload File Baru</h5>

                <input type="file" name="files[]" class="form-control" multiple
                       accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">

                <small class="text-muted">
                    Maksimal 5MB per file. Format: JPG, PNG, PDF, DOCX, XLSX.
                </small>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('program_bantuan.index') }}" class="btn btn-outline-secondary">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.querySelectorAll('.delete-media').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;

        if(!confirm("Hapus file ini?")) return;

        fetch("{{ url('program_bantuan/'.$program->program_id.'/media') }}/" + id, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                this.closest('.media-item').remove();
            }else{
                alert("Gagal menghapus media.");
            }
        });
    });
});
</script>

<style>
.form-floating.form-floating-outline .form-control {
    border: 1px solid #d9dee3;
    border-radius: 0.375rem;
}
.form-floating.form-floating-outline .form-control:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.2);
}
.media-item {
    transition: all 0.3s ease;
}
.media-item:hover {
    background-color: #f8f9fa !important;
}
</style>




@endsection
