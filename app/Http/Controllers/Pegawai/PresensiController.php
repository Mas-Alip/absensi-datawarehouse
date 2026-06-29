<?php

namespace App\Http\Controllers\Pegawai;

use App\Enums\AttendanceStatus;
use App\Enums\LateStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePresensiRequest;
use App\Models\Absensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $pegawai = $user->pegawai;

        if (! $pegawai) {
            return redirect()->route('pegawai.dashboard')->with('error', 'Data pegawai tidak ditemukan.');
        }

        $today = now('Asia/Jakarta')->toDateString();
        $todayAttendance = Absensi::where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', $today)
            ->first();

        $history = Absensi::where('pegawai_id', $pegawai->id)
            ->orderByDesc('tanggal')
            ->limit(10)
            ->get();

        return view('pegawai.presensi', [
            'pegawai' => $pegawai,
            'todayAttendance' => $todayAttendance,
            'statusToday' => $todayAttendance ? $todayAttendance->status_kehadiran : null,
            'history' => $history,
            'today' => Carbon::parse($today),
        ]);
    }

    public function checkin(StorePresensiRequest $request): RedirectResponse
    {
        $user = $request->user();
        $pegawai = $user->pegawai;

        if (! $pegawai) {
            return redirect()->route('pegawai.presensi')->with('error', 'Data pegawai tidak ditemukan.');
        }

        $today = now('Asia/Jakarta')->toDateString();
        $existing = Absensi::where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existing) {
            return redirect()->route('pegawai.presensi')->with('error', 'Anda sudah melakukan presensi hari ini.');
        }

        $validated = $request->validated();
        $jenisPresensi = AttendanceStatus::from($validated['jenis_presensi']);
        $jamMasuk = now('Asia/Jakarta');
        $statusKeterlambatan = LateStatus::ON_TIME;
        $menitKeterlambatan = 0;

        $cutoff = $jamMasuk->copy()->setTime(8, 0, 0);
        if ($jamMasuk->greaterThan($cutoff)) {
            $menitKeterlambatan = $jamMasuk->diffInMinutes($cutoff);
            $statusKeterlambatan = LateStatus::LATE;
        }

        Absensi::create([
            'pegawai_id' => $pegawai->id,
            'tanggal' => $today,
            'jam_masuk' => $jamMasuk->toTimeString(),
            'status_kehadiran' => AttendanceStatus::HADIR,
            'status_keterlambatan' => $statusKeterlambatan,
            'menit_terlambat' => $menitKeterlambatan,
            'keterangan' => null,
        ]);

        return redirect()->route('pegawai.presensi')->with('success', 'Presensi Hadir berhasil dicatat.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $user = $request->user();
        $pegawai = $user->pegawai;

        if (! $pegawai) {
            return redirect()->route('pegawai.presensi')->with('error', 'Data pegawai tidak ditemukan.');
        }

        $today = now('Asia/Jakarta')->toDateString();
        $attendance = Absensi::where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (! $attendance) {
            return redirect()->route('pegawai.presensi')->with('error', 'Anda belum melakukan presensi masuk hari ini.');
        }

        if ($attendance->status_kehadiran !== AttendanceStatus::HADIR) {
            return redirect()->route('pegawai.presensi')->with('error', 'Hanya status Hadir yang dapat melakukan checkout.');
        }

        if ($attendance->jam_keluar) {
            return redirect()->route('pegawai.presensi')->with('error', 'Anda sudah melakukan presensi pulang hari ini.');
        }

        $attendance->update([
            'jam_keluar' => now('Asia/Jakarta')->toTimeString(),
        ]);

        return redirect()->route('pegawai.presensi')->with('success', 'Presensi pulang berhasil dicatat.');
    }
}
