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
        $buktiPath = null;
        $jamMasuk = null;
        $statusKeterlambatan = LateStatus::ON_TIME;
        $menitKeterlambatan = 0;

        if ($jenisPresensi === AttendanceStatus::IZIN || $jenisPresensi === AttendanceStatus::SAKIT) {
            if ($request->hasFile('bukti_file')) {
                $buktiPath = $request->file('bukti_file')->store('bukti-presensi', 'public');
            }
        }

        if ($jenisPresensi === AttendanceStatus::HADIR) {
            if (! $this->isOfficeIp($request->ip())) {
                return redirect()->back()->with('error', 'Presensi HADIR hanya dapat dilakukan di kantor.');
            }

            $jamMasuk = now('Asia/Jakarta');
            $cutoff = $jamMasuk->copy()->setTime(8, 0, 0);
            if ($jamMasuk->greaterThan($cutoff)) {
                $menitKeterlambatan = $jamMasuk->diffInMinutes($cutoff);
                $statusKeterlambatan = LateStatus::LATE;
            } else {
                $menitKeterlambatan = 0;
                $statusKeterlambatan = LateStatus::ON_TIME;
            }
        }

        Absensi::create([
            'pegawai_id' => $pegawai->id,
            'tanggal' => $today,
            'jam_masuk' => $jamMasuk?->toTimeString(),
            'status_kehadiran' => $jenisPresensi,
            'status_keterlambatan' => $statusKeterlambatan,
            'menit_terlambat' => $menitKeterlambatan,
            'keterangan' => $validated['keterangan'] ?? null,
            'bukti_file' => $buktiPath,
        ]);

        return redirect()->route('pegawai.presensi')->with('success', 'Presensi ' . $jenisPresensi->label() . ' berhasil dicatat.');
    }

    protected function isOfficeIp(?string $ip): bool
    {
        if (! $ip) {
            return false;
        }

        foreach (config('office.office_ip_ranges', []) as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    protected function ipInRange(string $ip, string $range): bool
    {
        if (! str_contains($range, '/')) {
            return false;
        }

        [$subnet, $bits] = explode('/', $range);

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $bits = (int) $bits;

        if ($ipLong === false || $subnetLong === false || $bits < 0 || $bits > 32) {
            return false;
        }

        $mask = $bits === 0 ? 0 : ~((1 << (32 - $bits)) - 1);

        return ($ipLong & $mask) === ($subnetLong & $mask);
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
