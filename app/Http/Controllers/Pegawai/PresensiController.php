<?php

namespace App\Http\Controllers\Pegawai;

use App\Enums\AttendanceStatus;
use App\Enums\LateStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePresensiRequest;
use App\Models\Absensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $fotoSelfiePath = null;
        $jamMasuk = null;
        $statusKeterlambatan = LateStatus::ON_TIME;
        $menitKeterlambatan = 0;
        $latitude = $validated['latitude'] ?? null;
        $longitude = $validated['longitude'] ?? null;
        $alamat = null;

        if ($jenisPresensi === AttendanceStatus::IZIN || $jenisPresensi === AttendanceStatus::SAKIT) {
            if ($request->hasFile('bukti_file')) {
                $buktiPath = $request->file('bukti_file')->store('bukti-presensi', 'public');
            }
        }

        if ($jenisPresensi === AttendanceStatus::HADIR) {
            $jamMasuk = now('Asia/Jakarta');
            $cutoff = $jamMasuk->copy()->setTime(8, 0, 0);
            if ($jamMasuk->greaterThan($cutoff)) {
                $menitKeterlambatan = $jamMasuk->diffInMinutes($cutoff);
                $statusKeterlambatan = LateStatus::LATE;
            } else {
                $menitKeterlambatan = 0;
                $statusKeterlambatan = LateStatus::ON_TIME;
            }

            $fotoSelfiePath = $this->saveSelfieFromBase64($validated['foto_selfie'] ?? null);
            if ($latitude !== null && $longitude !== null) {
                $alamat = $this->buildLocationLink($latitude, $longitude);
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
            'foto_selfie' => $fotoSelfiePath,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'alamat' => $alamat,
        ]);

        return redirect()->route('pegawai.presensi')->with('success', 'Presensi ' . $jenisPresensi->label() . ' berhasil dicatat.');
    }

    private function saveSelfieFromBase64(?string $dataUrl): ?string
    {
        if (! $dataUrl || ! str_contains($dataUrl, 'base64,')) {
            return null;
        }

        [$meta, $encoded] = explode('base64,', $dataUrl, 2);
        $decoded = base64_decode($encoded);
        if ($decoded === false) {
            return null;
        }

        $filename = 'selfie_' . now('Asia/Jakarta')->format('Ymd_His') . '_' . uniqid() . '.jpg';
        $path = 'selfie-presensi/' . $filename;
        Storage::disk('public')->put($path, $decoded);

        return $path;
    }

    private function buildLocationLink(string $latitude, string $longitude): string
    {
        return sprintf('https://www.google.com/maps/search/?api=1&query=%s,%s', $latitude, $longitude);
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
