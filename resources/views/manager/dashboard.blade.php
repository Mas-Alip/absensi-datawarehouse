@extends('layouts.app')

@section('title', 'Dashboard Manager')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">Dashboard Manager</h1>
                    <p class="text-muted">Monitoring operasional kehadiran pegawai berbasis HRIS intranet.</p>
                </div>
                <div class="text-end">
                    <a href="{{ route('manager.dashboard.export_pdf', request()->query()) }}" class="btn btn-outline-primary">
                        <i class="bi bi-printer me-2"></i>Export PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Total Pegawai</h6>
                    <h2 class="mb-2">{{ number_format($totalMasterPegawai) }}</h2>
                    <p class="text-muted mb-0">Jumlah pegawai terdaftar dalam HRIS.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Sudah Hadir Hari Ini</h6>
                    <h2 class="mb-2">{{ number_format($todayPresentCount) }}</h2>
                    <p class="text-muted mb-0">Pegawai yang telah melakukan presensi masuk hari ini.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Belum Presensi (Alpha Sementara)</h6>
                    <h2 class="mb-2">{{ number_format($notPresentToday->count()) }}</h2>
                    <p class="text-muted mb-0">Pegawai yang belum tercatat hadir hari ini.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Persentase Kehadiran Hari Ini</h6>
                    <h2 class="mb-2">{{ number_format($attendancePercentageToday, 2) }}%</h2>
                    <p class="text-muted mb-0">Rasio kehadiran hari ini terhadap total pegawai.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 px-4 py-3">
                    <h5 class="mb-0">Daftar Pegawai yang Sudah Presensi</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Nama Pegawai</th>
                                    <th>Divisi</th>
                                    <th>Jabatan</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Status Kehadiran</th>
                                    <th>Status Keterlambatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($presentToday as $item)
                                    <tr>
                                        <td>{{ $item->pegawai?->nama ?? '-' }}</td>
                                        <td>{{ $item->pegawai?->divisi?->nama_divisi ?? '-' }}</td>
                                        <td>{{ $item->pegawai?->jabatan?->nama_jabatan ?? '-' }}</td>
                                        <td>{{ $item->jam_masuk ?? '-' }}</td>
                                        <td>{{ $item->jam_keluar ?? '-' }}</td>
                                        <td>{{ $item->status_kehadiran?->label() ?? ucfirst($item->status_kehadiran ?? '-') }}</td>
                                        <td>{{ $item->status_keterlambatan?->label() ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada data presensi hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 px-4 py-3">
                    <h5 class="mb-0">Daftar Pegawai yang Belum Presensi Hari Ini</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Nama Pegawai</th>
                                    <th>Divisi</th>
                                    <th>Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notPresentToday as $pegawai)
                                    <tr>
                                        <td>{{ $pegawai->nama }}</td>
                                        <td>{{ $pegawai->divisi?->nama_divisi ?? '-' }}</td>
                                        <td>{{ $pegawai->jabatan?->nama_jabatan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Semua pegawai sudah melakukan presensi hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                        @forelse($topDiscipline as $employee)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-1">{{ $employee->nama }}</h6>
                                    <small class="text-muted">Hadir: {{ $employee->total_hadir }} • Telat: {{ $employee->total_telat }}</small>
                                </div>
                                <span class="badge bg-success">{{ $employee->discipline_score }}</span>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted">Data pegawai disiplin belum tersedia.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
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
        <div class="col-sm-6 col-xl-3">
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
                                <h3 class="mt-2">{{ number_format($factTotalRecords ?? 0) }}</h3>
                                <p class="text-muted mb-0">Baris dalam fact_absensi.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="text-uppercase text-muted">Jumlah Pegawai</h6>
                                <h3 class="mt-2">{{ number_format($totalPegawai ?? 0) }}</h3>
                                <p class="text-muted mb-0">Pegawai aktif dalam faktor absensi.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="text-uppercase text-muted">Volume Divisi</h6>
                                <h3 class="mt-2">{{ number_format($totalDivisi ?? 0) }}</h3>
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
