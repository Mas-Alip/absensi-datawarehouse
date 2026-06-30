<aside class="app-sidebar bg-dark text-white d-none d-xl-flex flex-column flex-shrink-0 p-3 shadow-sm">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-4 text-white text-decoration-none">
        <span class="sidebar-brand-icon bg-white text-dark rounded-3 d-inline-flex align-items-center justify-content-center me-2">
            <i class="bi bi-bar-chart-line-fill fs-4"></i>
        </span>
        <div>
            <h2 class="h6 mb-0 fw-semibold">{{ config('app.name', 'Absensi DW') }}</h2>
            <div class="text-white-50 small">Data Warehouse Absensi</div>
        </div>
    </a>

    <nav class="nav nav-pills flex-column mb-4">
        @auth
            <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('dashboard') ? 'active' : 'text-white-75' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>

            @if(auth()->user()->isAdmin())
                <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('divisi.*') ? 'active' : 'text-white-75' }}" href="{{ route('divisi.index') }}">
                    <i class="bi bi-diagram-3 me-2"></i> Divisi
                </a>
                <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('jabatan.*') ? 'active' : 'text-white-75' }}" href="{{ route('jabatan.index') }}">
                    <i class="bi bi-briefcase me-2"></i> Jabatan
                </a>
                <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('pegawai.*') ? 'active' : 'text-white-75' }}" href="{{ route('pegawai.index') }}">
                    <i class="bi bi-people me-2"></i> Pegawai
                </a>
                <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('absensi.*') ? 'active' : 'text-white-75' }}" href="{{ route('absensi.index') }}">
                    <i class="bi bi-clock me-2"></i> Absensi
                </a>
            @endif

            @if(Route::has('etl.run'))
                <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('etl.run') ? 'active' : 'text-white-75' }}" href="#" onclick="event.preventDefault(); document.getElementById('etl-run-form').submit();">
                    <i class="bi bi-arrow-repeat me-2"></i> ETL
                </a>
                <form id="etl-run-form" action="{{ route('etl.run') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endif

            @if(auth()->user()->isPegawai())
                <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('pegawai.presensi') ? 'active' : 'text-white-75' }}" href="{{ route('pegawai.presensi') }}">
                    <i class="bi bi-clock-history me-2"></i> Presensi Saya
                </a>
                <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('pegawai.administrasi-alpha*') ? 'active' : 'text-white-75' }}" href="{{ route('pegawai.administrasi-alpha') }}">
                    <i class="bi bi-file-earmark-richtext me-2"></i> Administrasi Alpha
                </a>
            @endif

            @if(Route::has('laporan.index'))
                <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('laporan.*') ? 'active' : 'text-white-75' }}" href="{{ route('laporan.index') }}">
                    <i class="bi bi-file-earmark-text me-2"></i> Laporan
                </a>
            @endif
        @endauth
    </nav>

    <div class="mt-auto pt-3 border-top border-white-10">
        @auth
            <div class="d-flex align-items-center gap-3">
                <div class="avatar rounded-circle bg-white text-dark d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-person-fill fs-5"></i>
                </div>
                <div>
                    <div class="fw-semibold">{{ Auth::user()->name }}</div>
                    <div class="text-white-50 small">{{ Auth::user()->role?->label() ?? ucfirst(Auth::user()->role?->value ?? '') }}</div>
                </div>
            </div>
            <div class="mt-3 d-grid gap-2">
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-light btn-sm w-100">Profil Saya</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm w-100">Logout</button>
                </form>
            </div>
        @endauth
    </div>
</aside>

<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-white" id="sidebarOffcanvasLabel">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="px-3 pb-3">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-4 text-white text-decoration-none">
                <span class="sidebar-brand-icon bg-white text-dark rounded-3 d-inline-flex align-items-center justify-content-center me-2">
                    <i class="bi bi-bar-chart-line-fill fs-4"></i>
                </span>
                <div>
                    <h2 class="h6 mb-0 fw-semibold">{{ config('app.name', 'Absensi DW') }}</h2>
                    <div class="text-white-50 small">Data Warehouse Absensi</div>
                </div>
            </a>

            <nav class="nav nav-pills flex-column">
                @auth
                    <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('dashboard') ? 'active' : 'text-white-75' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('divisi.*') ? 'active' : 'text-white-75' }}" href="{{ route('divisi.index') }}">
                            <i class="bi bi-diagram-3 me-2"></i> Divisi
                        </a>
                        <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('jabatan.*') ? 'active' : 'text-white-75' }}" href="{{ route('jabatan.index') }}">
                            <i class="bi bi-briefcase me-2"></i> Jabatan
                        </a>
                        <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('pegawai.*') ? 'active' : 'text-white-75' }}" href="{{ route('pegawai.index') }}">
                            <i class="bi bi-people me-2"></i> Pegawai
                        </a>
                        <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('absensi.*') ? 'active' : 'text-white-75' }}" href="{{ route('absensi.index') }}">
                            <i class="bi bi-clock me-2"></i> Absensi
                        </a>
                    @endif

                    @if(Route::has('etl.run'))
                        <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('etl.run') ? 'active' : 'text-white-75' }}" href="#" onclick="event.preventDefault(); document.getElementById('etl-run-form-mobile').submit();">
                            <i class="bi bi-arrow-repeat me-2"></i> ETL
                        </a>
                        <form id="etl-run-form-mobile" action="{{ route('etl.run') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endif

                    @if(Route::has('laporan.index'))
                        <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('laporan.*') ? 'active' : 'text-white-75' }}" href="{{ route('laporan.index') }}">
                            <i class="bi bi-file-earmark-text me-2"></i> Laporan
                        </a>
                    @endif

                    @if(auth()->user()->isPegawai())
                        <a class="nav-link rounded-3 mb-1 px-3 py-2 {{ request()->routeIs('pegawai.administrasi-alpha*') ? 'active' : 'text-white-75' }}" href="{{ route('pegawai.administrasi-alpha') }}">
                            <i class="bi bi-file-earmark-richtext me-2"></i> Administrasi Alpha
                        </a>
                    @endif

                    <div class="mt-4 pt-3 border-top border-white-10">
                        <a href="{{ route('profile.edit') }}" class="nav-link rounded-3 mb-1 px-3 py-2 text-white-75">
                            <i class="bi bi-person-circle me-2"></i> Profil Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link rounded-3 w-100 text-start px-3 py-2 btn btn-link text-white-75"> 
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </nav>
        </div>
    </div>
</div>
