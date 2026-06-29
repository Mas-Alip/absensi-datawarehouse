@extends('layouts.app')

@section('title', 'Presensi Saya')

@php
    $statusToday = $todayAttendance?->status_kehadiran?->value;
@endphp

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="h3 mb-1">Presensi Saya</h1>
                <p class="text-muted">Modul presensi sederhana untuk kehadiran dan pulang berbasis intranet kantor.</p>
            </div>
            <a href="{{ route('pegawai.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    @include('components.alert')

    <div class="row gy-3">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="card-title">Ringkasan Presensi Hari Ini</h5>
                    <div class="mb-3">
                        <span class="text-muted">Tanggal</span>
                        <div class="fs-5 fw-semibold">{{ $today->format('l, d F Y') }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">Status Presensi</span>
                        <div class="fs-5 fw-semibold">
                            @if(!$todayAttendance)
                                Belum presensi
                            @elseif($statusToday === 'hadir' && !$todayAttendance->jam_keluar)
                                Hadir - menunggu pulang
                            @elseif($statusToday === 'hadir' && $todayAttendance->jam_keluar)
                                Presensi hari ini selesai
                            @else
                                {{ $todayAttendance->status_kehadiran?->label() ?? ucfirst($todayAttendance->status_kehadiran ?? '-') }}
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">Jam Masuk</span>
                        <div class="fs-5 fw-semibold">{{ $todayAttendance?->jam_masuk ? date('H:i:s', strtotime($todayAttendance->jam_masuk)) : '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">Jam Keluar</span>
                        <div class="fs-5 fw-semibold">{{ $todayAttendance?->jam_keluar ? date('H:i:s', strtotime($todayAttendance->jam_keluar)) : '-' }}</div>
                    </div>
                    @if($todayAttendance && $statusToday === 'hadir')
                        <div class="mb-3">
                            <span class="text-muted">Status Kedisiplinan</span>
                            <div class="fs-5 fw-semibold">{{ $todayAttendance->status_keterlambatan?->label() ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted">Keterlambatan</span>
                            <div class="fs-5 fw-semibold">{{ $todayAttendance->menit_terlambat ? $todayAttendance->menit_terlambat . ' Menit' : '0 Menit' }}</div>
                        </div>
                    @endif

                    @if(!$todayAttendance)
                        <div class="mt-4">
                            <div class="d-grid gap-2">
                                <a href="#presensiAction" class="btn btn-outline-primary">Mulai Presensi Hadir</a>
                            </div>
                        </div>
                    @elseif($statusToday === 'hadir' && !$todayAttendance->jam_keluar)
                        <form action="{{ route('pegawai.presensi.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">Presensi Pulang</button>
                        </form>
                    @else
                        <div class="alert alert-success mb-0">Presensi hari ini telah selesai.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 mb-3" id="presensiAction">
                <div class="card-body">
                    <h5 class="card-title">Aksi Presensi</h5>
                    <p class="card-text text-muted">Lakukan presensi Hadir dan Pulang tanpa selfie atau GPS.</p>

                    @if(!$todayAttendance)
                        <form action="{{ route('pegawai.presensi.checkin') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jenis_presensi" value="{{ \App\Enums\AttendanceStatus::HADIR->value }}">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">Presensi Hadir</button>
                            </div>
                        </form>
                    @elseif($statusToday === 'hadir' && !$todayAttendance->jam_keluar)
                        <form action="{{ route('pegawai.presensi.checkout') }}" method="POST">
                            @csrf
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning btn-lg">Presensi Pulang</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-success mb-0">Anda telah menyelesaikan presensi hari ini.</div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="card-title">Riwayat 10 Presensi Terakhir</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status Kedisiplinan</th>
                                    <th>Menit Terlambat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $attendance)
                                    <tr>
                                        <td>{{ $attendance->tanggal->format('Y-m-d') }}</td>
                                        <td>{{ $attendance->status_kehadiran?->label() ?? ucfirst($attendance->status_kehadiran ?? '-') }}</td>
                                        <td>{{ $attendance->jam_masuk ? date('H:i:s', strtotime($attendance->jam_masuk)) : '-' }}</td>
                                        <td>{{ $attendance->jam_keluar ? date('H:i:s', strtotime($attendance->jam_keluar)) : '-' }}</td>
                                        <td>{{ $attendance->status_keterlambatan?->label() ?? '-' }}</td>
                                        <td>{{ $attendance->menit_terlambat ? $attendance->menit_terlambat . ' Menit' : '0 Menit' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada riwayat presensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
