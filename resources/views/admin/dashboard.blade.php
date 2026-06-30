@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Dashboard Admin</h4>
                </div>
                <div class="card-body">
                    <p class="lead">Selamat datang, {{ auth()->user()->name }}.</p>
                    <p>Anda masuk sebagai <strong>{{ auth()->user()->role?->label() ?? ucfirst(auth()->user()->role?->value ?? '') }}</strong>.</p>
                    <div class="alert alert-info" role="alert">
                        Sistem role sudah diaktifkan. Halaman ini hanya dapat diakses oleh Admin.
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">Status Sistem</h5>
                                        <span class="badge bg-success">Online</span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="border rounded-3 p-3 h-100">
                                                <div class="small text-muted">Status Server</div>
                                                <div class="fw-semibold"><i class="bi bi-hdd-network me-2 text-success"></i>Aktif</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="border rounded-3 p-3 h-100">
                                                <div class="small text-muted">Status Database</div>
                                                <div class="fw-semibold"><i class="bi bi-database-check me-2 text-success"></i>Siap</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="border rounded-3 p-3 h-100">
                                                <div class="small text-muted">Status ETL</div>
                                                <div class="fw-semibold"><i class="bi bi-arrow-repeat me-2 text-warning"></i>Siap</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="border rounded-3 p-3 h-100">
                                                <div class="small text-muted">Mode Aplikasi</div>
                                                <div class="fw-semibold"><i class="bi bi-router me-2 text-primary"></i>{{ env('APP_MODE', 'INTRANET') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Divisi</h5>
                                    <p class="card-text">Kelola data divisi organisasi.</p>
                                    <a href="{{ route('divisi.index') }}" class="btn btn-primary">Buka Divisi</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-secondary h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Jabatan</h5>
                                    <p class="card-text">Kelola data jabatan dan struktur kerja.</p>
                                    <a href="{{ route('jabatan.index') }}" class="btn btn-secondary">Buka Jabatan</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-success h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Pegawai</h5>
                                    <p class="card-text">Kelola data pegawai karyawan.</p>
                                    <a href="{{ route('pegawai.index') }}" class="btn btn-success">Buka Pegawai</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-info h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Absensi</h5>
                                    <p class="card-text">Kelola data absensi pegawai dan lihat laporan kehadiran.</p>
                                    <a href="{{ route('absensi.index') }}" class="btn btn-info">Buka Absensi</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning h-100">
                                <div class="card-body">
                                    <h5 class="card-title">ETL Warehouse</h5>
                                    <p class="card-text">Jalankan proses ETL untuk memindahkan data absensi ke data warehouse.</p>
                                    <form action="{{ route('etl.run') }}" method="POST">
                                        @csrf
                                        <button class="btn btn-warning">Jalankan ETL</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-dark h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Informasi Sistem</h5>
                                    <p class="card-text">Lihat detail identitas sistem, mode intranet, dan status implementasi lokal.</p>
                                    <a href="{{ route('admin.informasi-sistem') }}" class="btn btn-dark">Buka Informasi Sistem</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
