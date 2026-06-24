@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">Dashboard Pegawai</h1>
                    <p class="text-muted">Ringkasan akun dan informasi singkat untuk pegawai.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="card-title">Nama</h5>
                    <p class="card-text fs-5">{{ $user->name }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="card-title">Email</h5>
                    <p class="card-text fs-5">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="card-title">Role</h5>
                    <p class="card-text fs-5">{{ $user->role?->label() ?? ucfirst($user->role?->value ?? '') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="card-title">Informasi Pegawai</h5>
                    <p class="card-text text-muted">Dashboard pegawai ini adalah tampilan awal sementara. Fitur presensi mandiri sekarang tersedia di menu "Presensi Saya".</p>
                    <a href="{{ route('pegawai.presensi') }}" class="btn btn-primary mt-3">Pergi ke Presensi Saya</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
