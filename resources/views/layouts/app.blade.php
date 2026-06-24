<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="bg-light">
        <div class="app-shell d-flex min-vh-100">
            @include('layouts.navigation')

            <div class="app-content flex-fill d-flex flex-column">
                <header class="topbar bg-white border-bottom py-3 px-3 shadow-sm">
                    <div class="container-fluid d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-outline-secondary btn-sm d-xl-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                                <i class="bi bi-list"></i>
                            </button>
                            <div>
                                <h1 class="h6 mb-0 text-secondary">Dashboard Data Warehouse Absensi Pegawai</h1>
                                <small class="text-muted">Modern analytics interface untuk monitoring absensi.</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            @auth
                                <div class="d-none d-md-flex align-items-center gap-2 text-secondary">
                                    <i class="bi bi-person-circle fs-5"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                </div>
                            @endauth
                        </div>
                    </div>
                </header>

                <main class="py-4 flex-fill">
                    <div class="container-fluid px-4">
                        @yield('content')
                    </div>
                </main>

                <footer class="app-footer bg-white border-top py-3 text-muted small">
                    <div class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                        <span>Data Warehouse Absensi Pegawai - Laravel 13</span>
                        <span class="text-muted">© {{ date('Y') }} {{ config('app.name', 'Absensi DW') }}</span>
                    </div>
                </footer>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>
