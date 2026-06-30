<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DivisiController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\EtlController;
use App\Http\Controllers\Admin\SystemInfoController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Pegawai\PresensiController as PegawaiPresensiController;
use App\Http\Controllers\Pegawai\AdministrasiAlphaController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManagerMiddleware;
use App\Http\Middleware\PegawaiMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isManager()) {
        return redirect()->route('manager.dashboard');
    }

    return redirect()->route('pegawai.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware(AdminMiddleware::class)
        ->name('admin.dashboard');

    Route::get('/admin/informasi-sistem', [SystemInfoController::class, 'index'])
        ->middleware(AdminMiddleware::class)
        ->name('admin.informasi-sistem');

    // Admin-only user creation (show register with role selection)
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])
        ->middleware(AdminMiddleware::class)
        ->name('admin.users.create');

    Route::post('/admin/users', [AdminUserController::class, 'store'])
        ->middleware(AdminMiddleware::class)
        ->name('admin.users.store');

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::resource('divisi', DivisiController::class)->except(['show']);
        Route::resource('jabatan', JabatanController::class)->except(['show']);
        Route::resource('pegawai', PegawaiController::class)->except(['show']);
        Route::get('pegawai/{pegawai}/create-account', [PegawaiController::class, 'createAccount'])->name('pegawai.createAccount');
        Route::post('pegawai/{pegawai}/store-account', [PegawaiController::class, 'storeAccount'])->name('pegawai.storeAccount');
        Route::resource('absensi', AbsensiController::class)->except(['show']);
        Route::post('etl/run', EtlController::class)->name('etl.run');
    });

    Route::get('/manager/dashboard', [ManagerDashboardController::class, 'index'])
        ->middleware(ManagerMiddleware::class)
        ->name('manager.dashboard');

    Route::get('/manager/dashboard/export-pdf', [ManagerDashboardController::class, 'exportPdf'])
        ->middleware(ManagerMiddleware::class)
        ->name('manager.dashboard.export_pdf');

    Route::get('/pegawai/dashboard', [PegawaiDashboardController::class, 'index'])
        ->middleware(PegawaiMiddleware::class)
        ->name('pegawai.dashboard');

    Route::get('/pegawai/presensi', [PegawaiPresensiController::class, 'index'])
        ->middleware(PegawaiMiddleware::class)
        ->name('pegawai.presensi');

    Route::get('/pegawai/administrasi-alpha', [AdministrasiAlphaController::class, 'index'])
        ->middleware(PegawaiMiddleware::class)
        ->name('pegawai.administrasi-alpha');

    Route::get('/pegawai/administrasi-alpha/pdf', [AdministrasiAlphaController::class, 'downloadPdf'])
        ->middleware(PegawaiMiddleware::class)
        ->name('pegawai.administrasi-alpha.pdf');

    Route::post('/pegawai/presensi/checkin', [PegawaiPresensiController::class, 'checkin'])
        ->middleware([PegawaiMiddleware::class])
        ->name('pegawai.presensi.checkin');

    Route::post('/pegawai/presensi/checkout', [PegawaiPresensiController::class, 'checkout'])
        ->middleware(PegawaiMiddleware::class)
        ->name('pegawai.presensi.checkout');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
