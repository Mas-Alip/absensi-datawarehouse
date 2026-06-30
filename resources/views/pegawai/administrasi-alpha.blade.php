@extends('layouts.app')

@section('title', 'Administrasi Alpha')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                <div>
                    <h1 class="h3 mb-1">Administrasi Alpha</h1>
                    <p class="text-muted mb-0">Halaman administrasi sederhana untuk dokumen internal pegawai yang dapat diunduh dalam format PDF.</p>
                </div>
                <a href="{{ route('pegawai.administrasi-alpha.pdf') }}" class="btn btn-primary">
                    <i class="bi bi-download me-2"></i> Download PDF
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="card-title">Informasi Dokumen</h5>
                    <p class="card-text text-muted">Modul ini dibuat sebagai fasilitas administrasi ringan untuk pegawai, tanpa mengubah alur presensi atau modul dashboard yang sudah berjalan.</p>

                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item px-0">
                            <strong>Nama Pegawai</strong>
                            <div class="text-muted">{{ $user->name }}</div>
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Email</strong>
                            <div class="text-muted">{{ $user->email }}</div>
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Status Akses</strong>
                            <div class="text-muted">{{ $user->role?->label() ?? ucfirst($user->role?->value ?? '') }}</div>
                        </li>
                    </ul>

                    <form action="{{ route('pegawai.administrasi-alpha.pdf') }}" method="GET" class="mt-4">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="5" class="form-control @error('keterangan') is-invalid @enderror" required minlength="10" placeholder="Contoh: Saya tidak masuk kerja...">{{ old('keterangan') }}</textarea>
                        <div class="form-text">Isi alasan ketidakhadiran pegawai. Minimal 10 karakter.</div>
                        @error('keterangan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-download me-2"></i> Download PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="card-title">Langkah Cepat</h5>
                    <p class="card-text text-muted">Isi keterangan dan unduh dokumen PDF yang siap dipakai untuk kebutuhan administrasi internal.</p>
                    <a href="{{ route('pegawai.administrasi-alpha') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-pencil-square me-2"></i> Isi Formulir
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
