@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Tambah Jabatan</h1>
        <p class="text-muted">Buat data jabatan baru untuk master data.</p>
    </div>
    <a href="{{ route('jabatan.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

@include('components.alert')

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('jabatan.store') }}">
            @csrf

            <div class="mb-3">
                <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                <input type="text" id="nama_jabatan" name="nama_jabatan" value="{{ old('nama_jabatan') }}" class="form-control @error('nama_jabatan') is-invalid @enderror" required>
                @error('nama_jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
