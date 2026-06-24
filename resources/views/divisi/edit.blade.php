@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Edit Divisi</h1>
        <p class="text-muted">Perbarui detail divisi.</p>
    </div>
    <a href="{{ route('divisi.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

@include('components.alert')

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('divisi.update', $divisi) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama_divisi" class="form-label">Nama Divisi</label>
                <input type="text" id="nama_divisi" name="nama_divisi" value="{{ old('nama_divisi', $divisi->nama_divisi) }}" class="form-control @error('nama_divisi') is-invalid @enderror" required>
                @error('nama_divisi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary">Perbarui</button>
        </form>
    </div>
</div>
@endsection
