<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Analitik Absensi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; }
        .page-break { page-break-after: always; }
        .header { margin-bottom: 24px; }
        .header h1 { font-size: 24px; margin-bottom: 4px; }
        .header p { margin: 0; color: #666; }
        .summary-grid { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .summary-grid td, .summary-grid th { padding: 10px; border: 1px solid #ddd; }
        .summary-grid th { background: #f5f5f5; text-align: left; }
        .section-title { font-size: 18px; margin: 20px 0 10px; }
        .metric-card { border: 1px solid #ddd; padding: 12px; border-radius: 8px; margin-bottom: 12px; }
        .metric-card strong { display: block; margin-bottom: 6px; }
        .table-report { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-report th, .table-report td { border: 1px solid #ddd; padding: 8px; }
        .table-report th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Analytics Absensi</h1>
        <p>Periode: {{ $filters['start_date']->format('d M Y') }} - {{ $filters['end_date']->format('d M Y') }}</p>
        <p>Dibuat: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table class="summary-grid">
        <tr>
            <th>Total Hadir</th>
            <th>Total Smart Hadir</th>
            <th>Total Telat</th>
            <th>Total Izin</th>
            <th>Total Sakit</th>
            <th>Total Alfa</th>
        </tr>
        <tr>
            <td>{{ number_format($summary->total_hadir) }}</td>
            <td>{{ number_format($summary->total_smart_hadir) }}</td>
            <td>{{ number_format($summary->total_telat) }}</td>
            <td>{{ number_format($summary->total_izin) }}</td>
            <td>{{ number_format($summary->total_sakit) }}</td>
            <td>{{ number_format($summary->total_alfa) }}</td>
        </tr>
    </table>

    <div class="metric-card">
        <strong>Total Pegawai</strong>
        {{ number_format($totalPegawai) }} pegawai
    </div>
    <div class="metric-card">
        <strong>Total Divisi</strong>
        {{ number_format($totalDivisi) }} divisi
    </div>
    <div class="metric-card">
        <strong>Total Jabatan</strong>
        {{ number_format($totalJabatan) }} jabatan
    </div>
    <div class="metric-card">
        <strong>Total Record Fact</strong>
        {{ number_format($totalDataAbsensi) }} baris
    </div>

    <h2 class="section-title">Kehadiran Bulanan</h2>
    <table class="table-report">
        <thead>
            <tr>
                <th>Periode</th>
                <th>Total Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyAttendance as $item)
                <tr>
                    <td>{{ $item->label }}</td>
                    <td>{{ number_format($item->total_hadir) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section-title">Keterlambatan Pegawai</h2>
    <table class="table-report">
        <thead>
            <tr>
                <th>Pegawai</th>
                <th>Total Telat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lateByEmployee as $item)
                <tr>
                    <td>{{ $item->nama }}</td>
                    <td>{{ number_format($item->total_telat) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section-title">Top Pegawai Disiplin</h2>
    <table class="table-report">
        <thead>
            <tr>
                <th>Pegawai</th>
                <th>Hadir</th>
                <th>Telat</th>
                <th>Skor Disiplin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topDiscipline as $item)
                <tr>
                    <td>{{ $item->nama }}</td>
                    <td>{{ number_format($item->total_hadir) }}</td>
                    <td>{{ number_format($item->total_telat) }}</td>
                    <td>{{ number_format($item->discipline_score) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
