@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Buat Akun Login</h1>
        <p class="text-muted">Buat akun login untuk {{ $pegawai->nama }}</p>
    </div>
    <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

@include('components.alert')

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="mb-4 p-3 bg-light rounded">
                    <h6 class="fw-bold mb-3">Informasi Pegawai</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> {{ $pegawai->nama }}</p>
                            <p><strong>NIP:</strong> {{ $pegawai->nip }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Divisi:</strong> {{ $pegawai->divisi?->nama_divisi }}</p>
                            <p><strong>Jabatan:</strong> {{ $pegawai->jabatan?->nama_jabatan }}</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('pegawai.storeAccount', $pegawai) }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle"></i> Password minimal 8 karakter.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Buat Akun Login</button>
                        <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
