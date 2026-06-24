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
                <p class="text-muted">Pilih jenis presensi yang sesuai untuk hari ini.</p>
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
                            @elseif($statusToday === 'izin')
                                Izin
                            @elseif($statusToday === 'sakit')
                                Sakit
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
                    <div class="mb-3">
                        <span class="text-muted">Keterangan</span>
                        <div class="fs-5 fw-semibold">{{ $todayAttendance?->keterangan ?? '-' }}</div>
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
                    <div class="mb-3">
                        <span class="text-muted">Bukti</span>
                        <div class="fs-5 fw-semibold">
                            @if($todayAttendance?->bukti_file)
                                <a href="{{ asset('storage/' . $todayAttendance->bukti_file) }}" target="_blank" class="link-primary">Lihat Bukti</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    @if(!$todayAttendance)
                        <div class="mt-4">
                            <div class="d-grid gap-2">
                                <a href="#presensi-options" class="btn btn-outline-primary">Pilih Jenis Presensi</a>
                            </div>
                        </div>
                    @elseif($statusToday === 'hadir' && !$todayAttendance->jam_keluar)
                        <form action="{{ route('pegawai.presensi.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">Presensi Pulang</button>
                        </form>
                    @else
                        <div class="alert alert-success mb-0">Presensi hari ini selesai.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            @if(!$todayAttendance)
                <div id="presensi-options" class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="card border-success rounded-4 h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <span class="badge bg-success">Hadir</span>
                                </div>
                                <h5 class="card-title">Presensi Hadir</h5>
                                <p class="card-text text-muted">Catat kehadiran dengan jam masuk sekarang.</p>
                                <form action="{{ route('pegawai.presensi.checkin') }}" method="POST" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="jenis_presensi" value="hadir">
                                    <button type="submit" class="btn btn-success w-100">Pilih Hadir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-info rounded-4 h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <span class="badge bg-info text-dark">Izin</span>
                                </div>
                                <h5 class="card-title">Presensi Izin</h5>
                                <p class="card-text text-muted">Ajukan izin dengan keterangan dan bukti pendukung.</p>
                                <button class="btn btn-outline-info w-100 mt-auto" type="button" data-bs-toggle="collapse" data-bs-target="#izinForm" aria-expanded="false" aria-controls="izinForm">Pilih Izin</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning rounded-4 h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <span class="badge bg-warning text-dark">Sakit</span>
                                </div>
                                <h5 class="card-title">Presensi Sakit</h5>
                                <p class="card-text text-muted">Laporkan sakit dengan keterangan dan bukti medis.</p>
                                <button class="btn btn-outline-warning w-100 mt-auto" type="button" data-bs-toggle="collapse" data-bs-target="#sakitForm" aria-expanded="false" aria-controls="sakitForm">Pilih Sakit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="collapse @if(old('jenis_presensi') === 'izin') show @endif" id="izinForm">
                    <div class="card shadow-sm border-0 rounded-4 mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Form Presensi Izin</h5>
                            <form action="{{ route('pegawai.presensi.checkin') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="jenis_presensi" value="izin">
                                <div class="mb-3">
                                    <label for="keterangan_izin" class="form-label">Keterangan</label>
                                    <textarea id="keterangan_izin" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="bukti_file_izin" class="form-label">Upload Bukti</label>
                                    <input type="file" id="bukti_file_izin" name="bukti_file" class="form-control @error('bukti_file') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                                    @error('bukti_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-info text-white">Kirim Izin</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="collapse @if(old('jenis_presensi') === 'sakit') show @endif" id="sakitForm">
                    <div class="card shadow-sm border-0 rounded-4 mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Form Presensi Sakit</h5>
                            <form action="{{ route('pegawai.presensi.checkin') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="jenis_presensi" value="sakit">
                                <div class="mb-3">
                                    <label for="keterangan_sakit" class="form-label">Keterangan Sakit</label>
                                    <textarea id="keterangan_sakit" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="bukti_file_sakit" class="form-label">Upload Bukti</label>
                                    <input type="file" id="bukti_file_sakit" name="bukti_file" class="form-control @error('bukti_file') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                                    @error('bukti_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-warning">Kirim Sakit</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h5 class="card-title">Detail Presensi Hari Ini</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted">Status</span>
                                        <div class="fs-5 fw-semibold">{{ $todayAttendance->status_kehadiran?->label() ?? ucfirst($todayAttendance->status_kehadiran) }}</div>
                                    </div>
                                    @if($todayAttendance->bukti_file)
                                        <a href="{{ asset('storage/' . $todayAttendance->bukti_file) }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Bukti</a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">Jam Masuk</span>
                                <div class="fs-5 fw-semibold">{{ $todayAttendance->jam_masuk ? date('H:i:s', strtotime($todayAttendance->jam_masuk)) : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">Jam Keluar</span>
                                <div class="fs-5 fw-semibold">{{ $todayAttendance->jam_keluar ? date('H:i:s', strtotime($todayAttendance->jam_keluar)) : '-' }}</div>
                            </div>
                            <div class="col-12">
                                <span class="text-muted">Keterangan</span>
                                <div class="fs-5 fw-semibold">{{ $todayAttendance->keterangan ?? '-' }}</div>
                            </div>
                            <div class="col-12">
                                @if($statusToday === 'hadir' && !$todayAttendance->jam_keluar)
                                    <form action="{{ route('pegawai.presensi.checkout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning w-100">Presensi Pulang</button>
                                    </form>
                                @elseif($statusToday === 'hadir' && $todayAttendance->jam_keluar)
                                    <div class="alert alert-success mb-0">Presensi hari ini selesai.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-4 mt-3">
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
                                    <th>Keterangan</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $attendance)
                                    <tr>
                                        <td>{{ $attendance->tanggal->format('Y-m-d') }}</td>
                                        <td>{{ $attendance->status_kehadiran?->label() ?? ucfirst($attendance->status_kehadiran ?? '-') }}</td>
                                        <td>{{ $attendance->jam_masuk ? date('H:i:s', strtotime($attendance->jam_masuk)) : '-' }}</td>
                                        <td>{{ $attendance->jam_keluar ? date('H:i:s', strtotime($attendance->jam_keluar)) : '-' }}</td>
                                        <td>{{ $attendance->keterangan ?? '-' }}</td>
                                        <td>
                                            @if($attendance->bukti_file)
                                                <a href="{{ asset('storage/' . $attendance->bukti_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Bukti</a>
                                            @else
                                                -
                                            @endif
                                        </td>
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
