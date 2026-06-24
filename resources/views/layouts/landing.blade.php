<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Sistem Absensi DW'))</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                color-scheme: light;
                font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            body {
                margin: 0;
                background: #f5f7fb;
                color: #1f2937;
            }

            .landing-hero {
                min-height: 88vh;
                background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.22), transparent 30%),
                    linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                color: #f8fafc;
                position: relative;
                overflow: hidden;
            }

            .landing-hero::before {
                content: '';
                position: absolute;
                inset: 0;
                background: radial-gradient(circle at 20% 20%, rgba(96, 165, 250, 0.28), transparent 30%),
                    radial-gradient(circle at 85% 25%, rgba(59, 130, 246, 0.18), transparent 25%),
                    radial-gradient(circle at 50% 90%, rgba(14, 165, 233, 0.14), transparent 25%);
                pointer-events: none;
            }

            .landing-hero .hero-content {
                position: relative;
                z-index: 1;
            }

            .feature-card,
            .overview-card,
            .stat-card {
                transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
            }

            .feature-card:hover,
            .overview-card:hover,
            .stat-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 20px 45px rgba(15, 23, 42, 0.14);
                border-color: rgba(56, 189, 248, 0.3);
            }

            .fade-in {
                opacity: 0;
                transform: translateY(24px);
                animation: fadeInUp 0.9s ease forwards;
            }

            .fade-in.delay-1 {
                animation-delay: 0.2s;
            }
            .fade-in.delay-2 {
                animation-delay: 0.35s;
            }
            .fade-in.delay-3 {
                animation-delay: 0.5s;
            }
            .fade-in.delay-4 {
                animation-delay: 0.65s;
            }

            @keyframes fadeInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .timeline-step {
                min-height: 120px;
                border-radius: 24px;
                background: rgba(255, 255, 255, 0.9);
                border: 1px solid rgba(148, 163, 184, 0.18);
                padding: 1.5rem;
            }

            .timeline-divider {
                width: 3px;
                background: linear-gradient(180deg, #38bdf8, #0ea5e9);
                border-radius: 999px;
                margin: 0 auto;
                min-height: 4rem;
            }

            .landing-footer {
                background: #0f172a;
                color: #cbd5e1;
            }

            .landing-footer a {
                color: #fff;
                text-decoration: none;
            }

            .landing-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                border-radius: 999px;
                padding: 0.55rem 0.9rem;
                background: rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(255, 255, 255, 0.12);
                color: #e2e8f0;
                font-size: 0.85rem;
                font-weight: 600;
            }
        </style>
        @stack('styles')
    </head>
    <body>
        @yield('content')

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-" crossorigin="anonymous"></script>
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.fade-in').forEach(function (section) {
                    section.classList.add('visible');
                });
            });
        </script>
        @stack('scripts')
    </body>
</html>
