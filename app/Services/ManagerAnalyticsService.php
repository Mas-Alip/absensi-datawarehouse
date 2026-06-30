<?php

namespace App\Services;

use App\Enums\LateStatus;
use App\Models\Absensi;
use App\Models\FactAbsensi;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManagerAnalyticsService
{
    public function parseFilters(Request $request): array
    {
        $period = $request->get('period', 'monthly');
        $today = Carbon::today();
        $startDate = null;
        $endDate = null;

        if ($period === 'yearly') {
            $startDate = $today->copy()->startOfYear();
            $endDate = $today;
        } elseif ($period === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->get('start_date'));
            $endDate = Carbon::parse($request->get('end_date'));

            if ($endDate->lessThan($startDate)) {
                [$startDate, $endDate] = [$endDate, $startDate];
            }
        } else {
            $startDate = $today->copy()->startOfMonth();
            $endDate = $today;
            $period = 'monthly';
        }

        return [
            'period' => $period,
            'start_date' => $startDate->startOfDay(),
            'end_date' => $endDate->endOfDay(),
        ];
    }

    public function dashboardData(array $filters): array
    {
        $dateRange = [$filters['start_date']->format('Y-m-d'), $filters['end_date']->format('Y-m-d')];
        $baseQuery = $this->factQuery($dateRange);

        $summary = (clone $baseQuery)
            ->selectRaw(
                'COALESCE(SUM(total_hadir), 0) as total_hadir,' .
                'COALESCE(SUM(total_smart_hadir), 0) as total_smart_hadir,' .
                'COALESCE(SUM(total_telat), 0) as total_telat,' .
                'COALESCE(SUM(total_izin), 0) as total_izin,' .
                'COALESCE(SUM(total_sakit), 0) as total_sakit,' .
                'COALESCE(SUM(total_alfa), 0) as total_alfa'
            )
            ->first();

        $totalPegawai = (clone $baseQuery)->distinct()->count('pegawai_key');
        $totalDivisi = (clone $baseQuery)->whereNotNull('divisi_key')->distinct()->count('divisi_key');
        $totalJabatan = (clone $baseQuery)->whereNotNull('jabatan_key')->distinct()->count('jabatan_key');
        $totalDataAbsensi = (clone $baseQuery)->count();

        $monthlyAttendance = (clone $baseQuery)
            ->selectRaw('dim_waktu.tahun, dim_waktu.bulan, CONCAT(dim_waktu.nama_bulan, " ", dim_waktu.tahun) as label, SUM(total_hadir) as total_hadir')
            ->groupBy('dim_waktu.tahun', 'dim_waktu.bulan', 'dim_waktu.nama_bulan')
            ->orderBy('dim_waktu.tahun')
            ->orderBy('dim_waktu.bulan')
            ->get();

        $lateByEmployee = (clone $baseQuery)
            ->join('dim_pegawai', 'fact_absensi.pegawai_key', '=', 'dim_pegawai.pegawai_key')
            ->selectRaw('dim_pegawai.nama, SUM(total_telat) as total_telat')
            ->groupBy('dim_pegawai.nama')
            ->orderByDesc('total_telat')
            ->limit(7)
            ->get();

        $attendanceByDivision = (clone $baseQuery)
            ->join('dim_divisi', 'fact_absensi.divisi_key', '=', 'dim_divisi.divisi_key')
            ->selectRaw('dim_divisi.nama_divisi, SUM(total_hadir) as total_hadir')
            ->groupBy('dim_divisi.nama_divisi')
            ->orderByDesc('total_hadir')
            ->get();

        $topDiscipline = (clone $baseQuery)
            ->join('dim_pegawai', 'fact_absensi.pegawai_key', '=', 'dim_pegawai.pegawai_key')
            ->selectRaw('dim_pegawai.nama, SUM(total_hadir) as total_hadir, SUM(total_telat) as total_telat, GREATEST(SUM(total_hadir) - SUM(total_telat), 0) as discipline_score')
            ->groupBy('dim_pegawai.nama')
            ->orderByDesc('discipline_score')
            ->limit(7)
            ->get();

        $lateSummary = Absensi::whereBetween('tanggal', $dateRange)
            ->where('status_keterlambatan', LateStatus::LATE->value)
            ->selectRaw('COUNT(DISTINCT pegawai_id) as pegawai_terlambat, COALESCE(SUM(menit_terlambat), 0) as total_menit_terlambat')
            ->first();

        // Additional manager-focused metrics
        $today = Carbon::today();
        $todayPresentCount = Absensi::whereDate('tanggal', $today)->count();
        $todayLateCount = Absensi::whereDate('tanggal', $today)->where('status_keterlambatan', LateStatus::LATE->value)->count();

        $totalMasterPegawai = Pegawai::count();
        $notPresentToday = Pegawai::whereDoesntHave('absensi', function ($query) use ($today) {
                $query->whereDate('tanggal', $today);
            })
            ->with(['divisi', 'jabatan'])
            ->orderBy('nama')
            ->get();

        $presentToday = Absensi::with(['pegawai.divisi', 'pegawai.jabatan'])
            ->whereDate('tanggal', $today)
            ->orderBy('jam_masuk')
            ->get();

        $attendancePercentageToday = $totalMasterPegawai > 0
            ? round(($todayPresentCount / $totalMasterPegawai) * 100, 2)
            : 0;

        return [
            'summary' => $summary,
            'todayPresentCount' => $todayPresentCount,
            'todayLateCount' => $todayLateCount,
            'totalPegawai' => $totalPegawai,
            'totalMasterPegawai' => $totalMasterPegawai,
            'notPresentToday' => $notPresentToday,
            'presentToday' => $presentToday,
            'attendancePercentageToday' => $attendancePercentageToday,
            'totalDivisi' => $totalDivisi,
            'totalJabatan' => $totalJabatan,
            'totalDataAbsensi' => $totalDataAbsensi,
            'monthlyAttendance' => $monthlyAttendance,
            'lateByEmployee' => $lateByEmployee,
            'attendanceByDivision' => $attendanceByDivision,
            'topDiscipline' => $topDiscipline,
            'factTotalRecords' => $totalDataAbsensi,
            'distinctDates' => (clone $baseQuery)->distinct()->count('dim_waktu.tanggal'),
            'lateSummary' => $lateSummary,
        ];
    }

    private function factQuery(array $dateRange)
    {
        return FactAbsensi::join('dim_waktu', 'fact_absensi.waktu_key', '=', 'dim_waktu.waktu_key')
            ->whereBetween('dim_waktu.tanggal', $dateRange);
    }
}
