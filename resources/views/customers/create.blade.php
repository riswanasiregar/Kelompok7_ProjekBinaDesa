@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="fw-semibold mb-4">Tambah Customer</h4>

    <form action="{{ route('customers.store') }}" method="POST" class="card p-4 shadow-sm border-0">
        @include('customers.partials.form', ['customer' => new \App\Models\Customer()])

        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

