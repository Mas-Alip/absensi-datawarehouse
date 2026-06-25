<?php

namespace App\Http\Middleware;

use App\Enums\AttendanceStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfficeNetworkMiddleware
{
    /**
     * Memproses penyaringan request presensi berdasarkan IP kantor.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika pembatasan IP dinonaktifkan di config, loloskan langsung
        if (! config('office.enabled', true)) {
            return $next($request);
        }

        $jenisPresensi = $request->input('jenis_presensi');

        // Pembatasan IP hanya berlaku untuk presensi dengan status "HADIR"
        if ($jenisPresensi !== AttendanceStatus::HADIR->value) {
            return $next($request);
        }

        // --- TRICK JITU: Membaca IP Asli Tanpa Bergantung pada bootstrap/app.php ---
        $userIp = $request->header('X-Forwarded-For');
        if ($userIp) {
            // X-Forwarded-For biasanya berisi daftar IP (IP_User, Proxy1, Proxy2). 
            // Kita ambil IP paling pertama karena itu adalah IP Publik asli WiFi kantor kamu.
            $ips = array_map('trim', explode(',', $userIp));
            $userIp = $ips[0];
        } else {
            $userIp = $request->ip();
        }
        // --------------------------------------------------------------------------

        // Cek apakah IP asli user cocok dengan salah satu IP kantor yang diizinkan
        if (! $this->isOfficeIp($userIp)) {
            // Menampilkan pesan error sekaligus menampilkan IP asli yang berhasil dibongkar
            return redirect()->back()
                ->with('error', 'Presensi HADIR hanya dapat dilakukan di kantor. (IP Anda terdeteksi: ' . ($userIp ?? 'Tidak Diketahui') . ')');
        }

        return $next($request);
    }

    /**
     * Memeriksa apakah IP berada di dalam daftar IP kantor.
     */
    protected function isOfficeIp(?string $ip): bool
    {
        if (! $ip) {
            return false;
        }

        $ranges = config('office.office_ip_ranges', []);

        // Antisipasi jika config mengembalikan string mentah dari .env, kita pecah menjadi array
        if (is_string($ranges)) {
            $ranges = array_map('trim', explode(',', $ranges));
        }

        foreach ($ranges as $range) {
            if ($this->ipInRange($ip, trim($range))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Memeriksa kecocokan IP tunggal maupun IP dengan subnet mask (CIDR).
     */
    protected function ipInRange(string $ip, string $range): bool
    {
        // Jika IP yang didaftarkan adalah IP tunggal (tanpa tanda slash "/"), langsung dicocokkan secara eksak
        if (! str_contains($range, '/')) {
            return $ip === $range;
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
}