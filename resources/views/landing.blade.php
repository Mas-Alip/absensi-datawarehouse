@extends('layouts.landing')

@section('title', 'Sistem Data Warehouse Absensi Pegawai')

@section('content')
    <section class="landing-hero d-flex align-items-center">
        <div class="container py-5">
            <div class="row align-items-center gy-5">
                <div class="col-lg-7 hero-content">
                    <span class="landing-badge mb-4 d-inline-flex align-items-center"><i class="bi bi-bar-chart-fill"></i> Data-Driven HR</span>
                    <h1 class="display-5 fw-semibold text-white mb-4">SISTEM DATA WAREHOUSE ABSENSI PEGAWAI</h1>
                    <p class="lead text-white-75 mb-4">Sistem Monitoring Kehadiran Pegawai Berbasis Data Warehouse, ETL, dan Dashboard Analitik untuk Mendukung Pengambilan Keputusan Manajemen.</p>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg rounded-pill px-4 py-3 shadow">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3">
                                <i class="bi bi-speedometer2 me-2"></i>Masuk Dashboard
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card rounded-4 bg-white bg-opacity-10 border-0 shadow-lg p-4 text-white">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <small class="text-uppercase text-white-50">Realtime Data</small>
                                <h3 class="h5 mb-0">Status Kehadiran Hari Ini</h3>
                            </div>
                            <i class="bi bi-clock-history fs-2"></i>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="rounded-4 p-3 bg-white bg-opacity-15">
                                    <span class="d-block text-black">Total Pegawai</span>
                                    <strong class="fs-3 text-info">{{ number_format($stats['totalPegawai']) }}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rounded-4 p-3 bg-white bg-opacity-15">
                                    <span class="d-block text-black">Hadir</span>
                                    <strong class="fs-3 text-success">{{ number_format($stats['totalHadir']) }}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rounded-4 p-3 bg-white bg-opacity-15">
                                    <span class="d-block text-black">Izin</span>
                                    <strong class="fs-3 text-warning">{{ number_format($stats['totalIzin']) }}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rounded-4 p-3 bg-white bg-opacity-15">
                                    <span class="d-block text-black">Sakit</span>
                                    <strong class="fs-3 text-danger">{{ number_format($stats['totalSakit']) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-primary fw-semibold">Statistik Sistem</span>
                <h2 class="fw-semibold">Ringkasan Kehadiran dan Aktivitas Hari Ini</h2>
                <p class="text-muted mx-auto" style="max-width: 680px;">Lihat kinerja kehadiran pegawai secara ringkas melalui dashboard profesional yang siap untuk presentasi skripsi dan laporan manajemen.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-primary rounded-pill p-3"><i class="bi bi-people-fill fs-5"></i></span>
                            <span class="text-muted small">Total Pegawai</span>
                        </div>
                        <h3 class="fw-bold">{{ number_format($stats['totalPegawai']) }}</h3>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-success rounded-pill p-3"><i class="bi bi-check2-circle fs-5"></i></span>
                            <span class="text-muted small">Hadir Hari Ini</span>
                        </div>
                        <h3 class="fw-bold text-success">{{ number_format($stats['totalHadir']) }}</h3>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-warning rounded-pill p-3"><i class="bi bi-file-earmark-text fs-5"></i></span>
                            <span class="text-muted small">Izin Hari Ini</span>
                        </div>
                        <h3 class="fw-bold text-warning">{{ number_format($stats['totalIzin']) }}</h3>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-danger rounded-pill p-3"><i class="bi bi-heart-pulse fs-5"></i></span>
                            <span class="text-muted small">Sakit Hari Ini</span>
                        </div>
                        <h3 class="fw-bold text-danger">{{ number_format($stats['totalSakit']) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-primary fw-semibold">Fitur Unggulan</span>
                <h2 class="fw-semibold">Solusi Presensi yang Lengkap dan Profesional</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Presensi Mandiri Pegawai</h5>
                            <i class="bi bi-person-check fs-3 text-primary"></i>
                        </div>
                        <p class="text-muted">Pegawai dapat melakukan presensi sendiri secara digital dan terstruktur.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Monitoring Kehadiran Realtime</h5>
                            <i class="bi bi-tv-fill fs-3 text-info"></i>
                        </div>
                        <p class="text-muted">Pantau kehadiran pegawai dalam waktu nyata dan ambil tindakan cepat.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Upload Bukti Izin dan Sakit</h5>
                            <i class="bi bi-cloud-arrow-up-fill fs-3 text-warning"></i>
                        </div>
                        <p class="text-muted">Unggah bukti izin atau sakit langsung ke sistem untuk validasi administratif.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">ETL Data Warehouse</h5>
                            <i class="bi bi-database-fill-gear fs-3 text-secondary"></i>
                        </div>
                        <p class="text-muted">Implementasi ETL untuk memproses data operasional ke dalam warehouse analitik.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Dashboard Analitik Manager</h5>
                            <i class="bi bi-graph-up-arrow fs-3 text-success"></i>
                        </div>
                        <p class="text-muted">Laporan visual dan statistik untuk analisis manajemen yang kuat.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Export PDF Laporan</h5>
                            <i class="bi bi-file-earmark-pdf-fill fs-3 text-danger"></i>
                        </div>
                        <p class="text-muted">Ekspor laporan ke format PDF untuk presentasi dan dokumentasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-primary fw-semibold">Alur Sistem</span>
                <h2 class="fw-semibold">Dari Presensi ke Insight Manajerial</h2>
            </div>
            <div class="row gx-4 gy-4 justify-content-center">
                <div class="col-12 col-md-10">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                        <div class="timeline-step text-center shadow-sm">
                            <i class="bi bi-people-fill fs-3 text-primary mb-3"></i>
                            <h6 class="mb-1">Pegawai</h6>
                            <p class="text-muted small mb-0">Login & Presensi</p>
                        </div>
                        <div class="timeline-divider d-none d-md-block"></div>
                        <div class="timeline-step text-center shadow-sm">
                            <i class="bi bi-check2-square fs-3 text-success mb-3"></i>
                            <h6 class="mb-1">Presensi</h6>
                            <p class="text-muted small mb-0">Hadir / Izin / Sakit</p>
                        </div>
                        <div class="timeline-divider d-none d-md-block"></div>
                        <div class="timeline-step text-center shadow-sm">
                            <i class="bi bi-server fs-3 text-secondary mb-3"></i>
                            <h6 class="mb-1">Database Operasional</h6>
                            <p class="text-muted small mb-0">Simpan data absensi</p>
                        </div>
                        <div class="timeline-divider d-none d-md-block"></div>
                        <div class="timeline-step text-center shadow-sm">
                            <i class="bi bi-arrow-repeat fs-3 text-info mb-3"></i>
                            <h6 class="mb-1">ETL</h6>
                            <p class="text-muted small mb-0">Transformasi data</p>
                        </div>
                        <div class="timeline-divider d-none d-md-block"></div>
                        <div class="timeline-step text-center shadow-sm">
                            <i class="bi bi-bar-chart-line-fill fs-3 text-warning mb-3"></i>
                            <h6 class="mb-1">Data Warehouse</h6>
                            <p class="text-muted small mb-0">Data terstruktur analitik</p>
                        </div>
                        <div class="timeline-divider d-none d-md-block"></div>
                        <div class="timeline-step text-center shadow-sm">
                            <i class="bi bi-pie-chart-fill fs-3 text-primary mb-3"></i>
                            <h6 class="mb-1">Dashboard Manager</h6>
                            <p class="text-muted small mb-0">Insight untuk keputusan strategis</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-primary fw-semibold">Preview Dashboard</span>
                <h2 class="fw-semibold">Fitur Utama per Peran</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card overview-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h5 class="mb-2">ADMIN</h5>
                                <p class="text-muted small mb-0">Kelola manajemen absensi end-to-end.</p>
                            </div>
                            <i class="bi bi-shield-lock-fill fs-2 text-primary"></i>
                        </div>
                        <ul class="list-unstyled mb-0 text-muted small">
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Kelola Pegawai</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Kelola Divisi</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Kelola Jabatan</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Kelola Absensi</li>
                            <li><i class="bi bi-check2-circle text-success me-2"></i>Jalankan ETL</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card overview-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h5 class="mb-2">MANAGER</h5>
                                <p class="text-muted small mb-0">Analitik untuk keputusan strategis.</p>
                            </div>
                            <i class="bi bi-graph-up fs-2 text-success"></i>
                        </div>
                        <ul class="list-unstyled mb-0 text-muted small">
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Dashboard Analitik</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Grafik Kehadiran</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Statistik Keterlambatan</li>
                            <li><i class="bi bi-check2-circle text-success me-2"></i>Export PDF</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card overview-card rounded-4 shadow-sm border-0 p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h5 class="mb-2">PEGAWAI</h5>
                                <p class="text-muted small mb-0">Presensi digital dan riwayat lengkap.</p>
                            </div>
                            <i class="bi bi-person-lines-fill fs-2 text-warning"></i>
                        </div>
                        <ul class="list-unstyled mb-0 text-muted small">
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Presensi Hadir</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Presensi Izin</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Presensi Sakit</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Presensi Pulang</li>
                            <li><i class="bi bi-check2-circle text-success me-2"></i>Riwayat Presensi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-primary fw-semibold">Tentang Sistem</span>
                <h2 class="fw-semibold">Tentang Sistem</h2>
                <p class="text-muted mx-auto" style="max-width: 720px;">Sistem Data Warehouse Absensi Pegawai merupakan aplikasi berbasis web yang mengintegrasikan proses presensi pegawai dengan konsep Data Warehouse dan ETL (Extract, Transform, Load) sehingga data operasional dapat diolah menjadi informasi strategis yang mendukung proses pengambilan keputusan manajemen.</p>
            </div>
        </div>
    </section>

    <footer class="landing-footer py-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div>
                <h5 class="mb-1 text-white">Data Warehouse Absensi Pegawai</h5>
                <p class="mb-0 text-muted">Universitas Pancasakti Tegal • Tahun 2026</p>
            </div>
            <div class="d-flex align-items-center gap-4">
                <a href="{{ route('login') }}" class="text-white text-decoration-none"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
                <span class="text-white-50">© 2026</span>
            </div>
        </div>
    </footer>
@endsection
