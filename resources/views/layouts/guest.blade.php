<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gradient p-0">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
            <div class="auth-brand text-center text-white mb-4">
                <div class="auth-logo d-inline-flex align-items-center justify-content-center rounded-4 mb-3">
                    <i class="bi bi-shield-lock-fill fs-1"></i>
                </div>
                <h1 class="h3 mb-1">{{ config('app.name', 'Absensi DW') }}</h1>
                <p class="text-white-75 mb-0">Dashboard Data Warehouse Absensi Pegawai</p>
            </div>

            <div class="card auth-card shadow-lg w-100" style="max-width: 460px;">
                <div class="card-body p-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>
</html>
