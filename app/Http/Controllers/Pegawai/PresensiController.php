<?php

namespace App\Http\Controllers\Pegawai;

use App\Enums\AttendanceStatus;
use App\Enums\LateStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePresensiRequest;
use App\Models\Absensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

            Log::info('Presensi hadir: mulai proses upload selfie', [
                'pegawai_id' => $pegawai->id,
                'today' => $today,
                'foto_selfie_present' => isset($validated['foto_selfie']),
                'foto_selfie_length' => isset($validated['foto_selfie']) ? strlen($validated['foto_selfie']) : 0,
            ]);

            $fotoSelfiePath = $this->saveSelfieFromBase64($validated['foto_selfie'] ?? null);
            if (! $fotoSelfiePath) {
                Log::error('Presensi hadir gagal: file selfie tidak tersimpan', [
                    'pegawai_id' => $pegawai->id,
                    'today' => $today,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);
                return redirect()->route('pegawai.presensi')->with('error', 'Gagal menyimpan foto selfie. Silakan ulangi presensi.');
            }

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
        Log::info('saveSelfieFromBase64 called', [
            'data_url_present' => ! empty($dataUrl),
            'data_url_type' => is_string($dataUrl) ? gettype($dataUrl) : null,
        ]);

        if (! $dataUrl || ! str_contains($dataUrl, 'base64,')) {
            Log::warning('saveSelfieFromBase64 invalid payload', [
                'data_url' => $dataUrl,
            ]);
            return null;
        }

        [$meta, $encoded] = explode('base64,', $dataUrl, 2);
        $encodedLength = strlen($encoded);
        Log::info('saveSelfieFromBase64 parsed base64', [
            'meta' => $meta,
            'encoded_length' => $encodedLength,
        ]);

        $decoded = base64_decode($encoded, true);
        if ($decoded === false) {
            Log::error('saveSelfieFromBase64 base64_decode failed', [
                'meta' => $meta,
                'encoded_length' => $encodedLength,
            ]);
            return null;
        }

        $decodedSize = strlen($decoded);
        Log::info('saveSelfieFromBase64 decoded file', [
            'decoded_size' => $decodedSize,
        ]);

        $filename = 'selfie_' . now('Asia/Jakarta')->format('Ymd_His') . '_' . uniqid() . '.jpg';
        $path = 'selfie-presensi/' . $filename;
        $disk = Storage::disk('public');
        $rootPath = $disk->path('');

        $created = $disk->makeDirectory('selfie-presensi', 0755, true);
        Log::info('saveSelfieFromBase64 ensured directory exists', [
            'directory' => 'selfie-presensi',
            'created' => $created,
            'disk_root' => $rootPath,
        ]);

        $stored = false;
        try {
            $stored = $disk->put($path, $decoded);
        } catch (\Throwable $e) {
            Log::error('saveSelfieFromBase64 put exception', [
                'path' => $path,
                'exception' => $e->getMessage(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine(),
            ]);
        }

        if (! $stored) {
            $absolutePath = rtrim($rootPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $path;
            $folder = dirname($absolutePath);
            if (! is_dir($folder)) {
                mkdir($folder, 0755, true);
            }
            $stored = file_put_contents($absolutePath, $decoded) !== false;
            if ($stored) {
                $disk->setVisibility($path, 'public');
            }
            Log::warning('saveSelfieFromBase64 fallback native write', [
                'path' => $path,
                'absolute_path' => $absolutePath,
                'stored' => $stored,
            ]);
        }

        $exists = $disk->exists($path);
        $absolutePath = $disk->path($path);
        Log::info('saveSelfieFromBase64 storage exists check', [
            'path' => $path,
            'exists' => $exists,
            'absolute_path' => $absolutePath,
        ]);

        if (! $stored || ! $exists) {
            Log::error('saveSelfieFromBase64 upload verification failed', [
                'path' => $path,
                'stored' => $stored,
                'exists' => $exists,
                'absolute_path' => $absolutePath,
            ]);
            return null;
        }

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
