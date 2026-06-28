@extends('layouts.app')

@section('title', 'Dashboard Manager')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">Dashboard Manager</h1>
                    <p class="text-muted">Analitik absensi pegawai berbasis data warehouse.</p>
                </div>
                <div class="text-end">
                    <a href="{{ route('manager.dashboard.export_pdf', request()->query()) }}" class="btn btn-outline-primary">
                        <i class="bi bi-printer me-2"></i>Export PDF
                    </a>
                </div>
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 px-4 py-3">
                    <h5 class="mb-0">Riwayat Presensi (Terbaru)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive overflow-x-auto">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pegawai</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Selfie</th>
                                    <th>Lokasi</th>
                                    <th>Maps</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPresensi as $p)
                                    <tr>
                                        <td>{{ $p->tanggal?->format('Y-m-d') }}</td>
                                        <td>{{ $p->pegawai?->nama }}</td>
                                        <td>{{ $p->status_kehadiran?->label() ?? ucfirst($p->status_kehadiran ?? '-') }}</td>
                                        <td>{{ $p->jam_masuk ?? '-' }}</td>
                                        <td>
                                            @if($p->foto_selfie)
                                                <a href="{{ asset('storage/' . $p->foto_selfie) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $p->foto_selfie) }}" alt="Selfie" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->latitude && $p->longitude)
                                                {{ number_format($p->latitude, 7) }}, {{ number_format($p->longitude, 7) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->latitude && $p->longitude)
                                                <a href="https://maps.google.com/?q={{ $p->latitude }},{{ $p->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Lokasi</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('manager.dashboard') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Periode</label>
            <select name="period" class="form-select">
                <option value="monthly" {{ $filters['period'] === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="yearly" {{ $filters['period'] === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                <option value="custom" {{ $filters['period'] === 'custom' ? 'selected' : '' }}>Kustom</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Mulai</label>
            <input type="date" name="start_date" value="{{ $filters['start_date']->format('Y-m-d') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Selesai</label>
            <input type="date" name="end_date" value="{{ $filters['end_date']->format('Y-m-d') }}" class="form-control">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Terapkan</button>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-success rounded-circle p-3 me-3">
                            <i class="bi bi-check2-circle fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-1 text-uppercase text-muted">Total Hadir</h6>
                            <h3 class="mb-0">{{ number_format($summary->total_hadir) }}</h3>
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Semua kehadiran terlihat dalam fact_absensi.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-danger rounded-circle p-3 me-3">
                            <i class="bi bi-clock-history fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-1 text-uppercase text-muted">Total Telat</h6>
                            <h3 class="mb-0">{{ number_format($summary->total_telat) }}</h3>
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Menggunakan metrik telat dari warehouse.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-danger rounded-circle p-3 me-3">
                            <i class="bi bi-person-fill-lock fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-1 text-uppercase text-muted">Pegawai Terlambat</h6>
                            <h3 class="mb-0">{{ number_format($lateSummary->pegawai_terlambat ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Jumlah pegawai terlambat pada periode.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-dark rounded-circle p-3 me-3">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-1 text-uppercase text-muted">Menit Terlambat</h6>
                            <h3 class="mb-0">{{ number_format($lateSummary->total_menit_terlambat ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Total menit keterlambatan pada periode.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-warning rounded-circle p-3 me-3">
                            <i class="bi bi-file-earmark-text fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-1 text-uppercase text-muted">Total Izin</h6>
                            <h3 class="mb-0">{{ number_format($summary->total_izin) }}</h3>
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Izin pegawai tercatat di fact_absensi.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-info rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-info rounded-circle p-3 me-3">
                            <i class="bi bi-heart-pulse fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-1 text-uppercase text-muted">Total Sakit</h6>
                            <h3 class="mb-0">{{ number_format($summary->total_sakit) }}</h3>
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Statistik sakit diambil dari warehouse.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-success rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-success rounded-circle p-3 me-3">
                            <i class="bi bi-camera-fill fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-1 text-uppercase text-muted">Total Smart Hadir</h6>
                            <h3 class="mb-0">{{ number_format($summary->total_smart_hadir) }}</h3>
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Hadir lengkap dengan selfie dan GPS.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-secondary rounded-circle p-3 me-3">
                            <i class="bi bi-x-circle fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-1 text-uppercase text-muted">Total Alfa</h6>
                            <h3 class="mb-0">{{ number_format($summary->total_alfa) }}</h3>
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Kehadiran alfa tersaji secara jelas.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-dark rounded-circle p-3 me-3">
                            <i class="bi bi-bar-chart-line fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-1 text-uppercase text-muted">Periode</h6>
                            <h3 class="mb-0">{{ $distinctDates }} hari</h3>
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Data waktu tersedia di dim_waktu.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 px-4 py-3">
                    <h5 class="mb-0">Grafik Kehadiran Bulanan</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="attendanceMonthlyChart" height="140"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 px-4 py-3">
                    <h5 class="mb-0">Keterlambatan Pegawai</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="lateByEmployeeChart" height="240"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 px-4 py-3">
                    <h5 class="mb-0">Statistik Kehadiran per Divisi</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="attendanceByDivisionChart" height="240"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 px-4 py-3">
                    <h5 class="mb-0">Top Pegawai Paling Disiplin</h5>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        @foreach($topDiscipline as $employee)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-1">{{ $employee->nama }}</h6>
                                    <small class="text-muted">Hadir: {{ $employee->total_hadir }} • Telat: {{ $employee->total_telat }}</small>
                                </div>
                                <span class="badge bg-success">{{ $employee->discipline_score }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-primary rounded-circle p-3 me-3">
                            <i class="bi bi-list-check fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-1 text-uppercase text-muted">Presensi Hari Ini</h6>
                            <h3 class="mb-0">{{ number_format($todayPresentCount ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Jumlah presensi yang tercatat hari ini.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-danger rounded-circle p-3 me-3">
                            <i class="bi bi-exclamation-circle fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-1 text-uppercase text-muted">Jumlah Terlambat</h6>
                            <h3 class="mb-0">{{ number_format($todayLateCount ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Pegawai terlambat hari ini.</p>
                </div>
            </div>
        </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 px-4 py-3">
                    <h5 class="mb-0">Monitoring Ringkasan Absensi</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row gx-3 gy-3">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="text-uppercase text-muted">Total Record Fact</h6>
                                <h3 class="mt-2">{{ number_format($factTotalRecords) }}</h3>
                                <p class="text-muted mb-0">Baris dalam fact_absensi.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="text-uppercase text-muted">Jumlah Pegawai</h6>
                                <h3 class="mt-2">{{ number_format($totalPegawai) }}</h3>
                                <p class="text-muted mb-0">Pegawai aktif dalam faktor absensi.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="text-uppercase text-muted">Volume Divisi</h6>
                                <h3 class="mt-2">{{ number_format($totalDivisi) }}</h3>
                                <p class="text-muted mb-0">Divisi terlibat dalam analitik.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const attendanceMonthlyLabels = {!! json_encode($monthlyAttendance->pluck('label')) !!};
        const attendanceMonthlyData = {!! json_encode($monthlyAttendance->pluck('total_hadir')) !!};

        const lateByEmployeeLabels = {!! json_encode($lateByEmployee->pluck('nama')) !!};
        const lateByEmployeeData = {!! json_encode($lateByEmployee->pluck('total_telat')) !!};

        const attendanceByDivisionLabels = {!! json_encode($attendanceByDivision->pluck('nama_divisi')) !!};
        const attendanceByDivisionData = {!! json_encode($attendanceByDivision->pluck('total_hadir')) !!};

        new Chart(document.getElementById('attendanceMonthlyChart'), {
            type: 'line',
            data: {
                labels: attendanceMonthlyLabels,
                datasets: [{
                    label: 'Hadir Bulanan',
                    data: attendanceMonthlyData,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.15)',
                    fill: true,
                    tension: 0.25,
                    pointRadius: 4,
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        new Chart(document.getElementById('lateByEmployeeChart'), {
            type: 'bar',
            data: {
                labels: lateByEmployeeLabels,
                datasets: [{
                    label: 'Total Telat',
                    data: lateByEmployeeData,
                    backgroundColor: '#dc3545',
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        new Chart(document.getElementById('attendanceByDivisionChart'), {
            type: 'bar',
            data: {
                labels: attendanceByDivisionLabels,
                datasets: [{
                    label: 'Total Hadir',
                    data: attendanceByDivisionData,
                    backgroundColor: '#0d6efd',
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endpush
