{{-- Komponen sederhana untuk button lihat foto --}}
{{-- Cara pakai: @include('components.button-foto', ['item' => $item, 'table' => 'nama_tabel']) --}}

@props(['item', 'table' => ''])

@php
    // Cek apakah ada foto untuk item ini
    $foto = $item->media()->first();
@endphp

@if($foto)
    {{-- Jika ada foto, tampilkan button --}}
    <div class="mt-2">
        <a href="{{ asset('storage/' . $foto->file_path) }}" target="_blank" class="btn btn-info btn-sm">
            📷 Lihat Foto
        </a>
    </div>
@else
    {{-- Jika tidak ada foto --}}
    <div class="mt-2">
        <small class="text-muted">Tidak ada foto</small>
    </div>
@endif