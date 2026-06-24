<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Models\Absensi;
use App\Models\Pegawai;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

class LandingPageController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        $stats = [
            'totalPegawai' => Pegawai::count(),
            'totalHadir' => Absensi::whereDate('tanggal', $today)
                ->where('status_kehadiran', AttendanceStatus::HADIR->value)
                ->count(),
            'totalIzin' => Absensi::whereDate('tanggal', $today)
                ->where('status_kehadiran', AttendanceStatus::IZIN->value)
                ->count(),
            'totalSakit' => Absensi::whereDate('tanggal', $today)
                ->where('status_kehadiran', AttendanceStatus::SAKIT->value)
                ->count(),
        ];

        return view('landing', compact('stats'));
    }
}
