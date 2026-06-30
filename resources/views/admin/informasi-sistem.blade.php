@extends('layouts.app')

@section('title', 'Informasi Sistem')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-dark text-white rounded-top-4">
                    <h4 class="mb-0">Informasi Sistem</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Aplikasi ini dirancang sebagai HRIS internal kantor berbasis jaringan intranet, dengan fokus pada penggunaan lokal dan operasional staf.</p>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Nama Sistem</div>
                                <div class="fw-semibold">{{ $systemInfo['name'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Versi Aplikasi</div>
                                <div class="fw-semibold">1.0.0</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Framework Laravel</div>
                                <div class="fw-semibold">{{ $systemInfo['laravel_version'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Versi PHP</div>
                                <div class="fw-semibold">{{ $systemInfo['php_version'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Database</div>
                                <div class="fw-semibold">{{ strtoupper($systemInfo['database']) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Mode Sistem</div>
                                <div class="fw-semibold">{{ $systemInfo['mode'] }} / {{ $systemInfo['network_label'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Status ETL</div>
                                <div class="fw-semibold">{{ $systemInfo['etl_status'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Tanggal Build</div>
                                <div class="fw-semibold">{{ $systemInfo['build_date'] }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-secondary mt-4 mb-0" role="alert">
                        <strong>Konsep implementasi:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Server berada di kantor dan digunakan dalam jaringan internal.</li>
                            <li>Pegawai mengakses sistem melalui WiFi atau LAN kantor.</li>
                            <li>Sistem ini tidak ditujukan untuk akses publik.</li>
                            <li>Presensi dilakukan melalui jaringan intranet yang aman dan terbatas.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
