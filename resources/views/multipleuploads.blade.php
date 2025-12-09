
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Upload File atau Gambar') }}</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('uploads.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('File') }}</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control @error('filename') is-invalid @enderror @error('filename.*') is-invalid @enderror" name="filename[]" required multiple>
                                @error('filename')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                @error('filename.*')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Upload') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- daftar upload  -->
    @isset($uploads)
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Riwayat Upload') }}</div>
                <div class="card-body p-0">
                    @if($uploads->isEmpty())
                        <p class="text-center my-3 text-muted">{{ __('Belum ada file yang diunggah.') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Nama File') }}</th>
                                        <th>{{ __('Aksi') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($uploads as $index => $upload)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ basename($upload->filename) }}</td>
                                            <td>
                                                <a href="{{ asset('storage/'.$upload->filename) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    {{ __('Lihat') }}
                                                </a>
                                                <form class="d-inline" method="POST" action="{{ route('uploads.destroy', $upload) }}" onsubmit="return confirm('Hapus file ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endisset
</div>
@endsection