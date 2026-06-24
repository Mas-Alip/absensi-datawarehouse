<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PegawaiMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isPegawai()) {
            abort(403, 'Akses halaman pegawai hanya untuk Pegawai.');
        }

        return $next($request);
    }
}
