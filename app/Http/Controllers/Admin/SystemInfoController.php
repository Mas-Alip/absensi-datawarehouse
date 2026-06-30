<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemInfoController extends Controller
{
    public function index(Request $request): View
    {
        $systemInfo = [
            'name' => config('app.name', 'Absensi DW'),
            'app_mode' => env('APP_MODE', 'INTRANET'),
            'network' => env('APP_NETWORK', 'LOCAL'),
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'database' => config('database.default', 'sqlite'),
            'etl_status' => 'Siap',
            'build_date' => now()->translatedFormat('d F Y'),
            'mode' => env('APP_MODE', 'INTRANET'),
            'network_label' => env('APP_NETWORK', 'LOCAL') === 'LOCAL' ? 'Jaringan Lokal' : 'Jaringan Terhubung',
        ];

        return view('admin.informasi-sistem', [
            'systemInfo' => $systemInfo,
            'user' => $request->user(),
        ]);
    }
}
