@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="fw-semibold mb-4">Edit Customer</h4>

    <form action="{{ route('customers.update', $customer) }}" method="POST" class="card p-4 shadow-sm border-0">
        @method('PUT')
        @include('customers.partials.form', ['customer' => $customer])

        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

