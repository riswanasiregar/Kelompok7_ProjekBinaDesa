<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Warga</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; width: 400px; margin: auto; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        input, select { width: 100%; padding: 8px; margin: 6px 0 12px 0; border: 1px solid #ccc; border-radius: 4px; }
        button, a { background: #28a745; color: white; padding: 10px 14px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        button:hover, a:hover { background: #1e7e34; }
        label { font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Warga</h1>
    <form action="{{ route('warga.update', $warga->warga_id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>No KTP</label>
        <input type="text" name="no_ktp" value="{{ $warga->no_ktp }}" required>

        <label>Nama</label>
        <input type="text" name="nama" value="{{ $warga->nama }}" required>

        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin">
            <option value="">-- Pilih --</option>
            <option value="Laki-laki" {{ $warga->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ $warga->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>

        <label>Agama</label>
        <input type="text" name="agama" value="{{ $warga->agama }}">

        <label>Pekerjaan</label>
        <input type="text" name="pekerjaan" value="{{ $warga->pekerjaan }}">

        <label>Telp</label>
        <input type="text" name="telp" value="{{ $warga->telp }}">

        <label>Email</label>
        <input type="email" name="email" value="{{ $warga->email }}">

        <button type="submit">Update</button>
        <a href="{{ route('warga.index') }}">Kembali</a>
    </form>
</div>

</body>
</html>
