<?php

namespace App\Http\Middleware;

use App\Enums\AttendanceStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfficeNetworkMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('office.enabled', true)) {
            return $next($request);
        }

        $jenisPresensi = $request->input('jenis_presensi');

        if ($jenisPresensi !== AttendanceStatus::HADIR->value) {
            return $next($request);
        }

        $userIp = $request->ip();

        if (! $this->isOfficeIp($userIp)) {
            return redirect()->back()
                ->with('error', 'Presensi HADIR hanya dapat dilakukan di kantor.');
        }

        return $next($request);
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
            return $ip === range;
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
