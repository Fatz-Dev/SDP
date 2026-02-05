<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// AUTHENTICATED ROUTES (Admin & Pegawai)
// ==========================================
Route::middleware(['auth.admin'])->group(function () {

    // Central Redirection
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Shared Profile
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // ------------------------------------------
    // ADMIN SECTION
    // ------------------------------------------
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            $username = session('username');
            $user = \App\Models\User::where('username', $username)->first();

            // Get today's attendance
            $todaysAttendance = \App\Models\Absensi::with('user')
                ->whereDate('tanggal', \Carbon\Carbon::today())
                ->orderBy('jam_masuk', 'desc')
                ->get();

            return view('pages.admin.dashboard', [
                'username' => $username,
                'user' => $user,
                'latestUsers' => \App\Models\User::latest()->take(5)->get(),
                'todaysAttendance' => $todaysAttendance,
            ]);
        })->name('dashboard.admin');


        Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        Route::get('/messages', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
        Route::delete('/messages/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('reports.generate');

        // Admin Features: Attendance & Leave Management
        Route::prefix('fitur')->group(function () {
            Route::get('/absensi', [\App\Http\Controllers\Admin\AbsensiController::class, 'index'])->name('admin.absensi.index');
            Route::get('/absensi/rekap', [\App\Http\Controllers\Admin\AbsensiController::class, 'rekap'])->name('admin.absensi.rekap');
            Route::get('/absensi/{absensi}', [\App\Http\Controllers\Admin\AbsensiController::class, 'show'])->name('admin.absensi.show');
            Route::get('/cuti', [\App\Http\Controllers\Admin\PengajuanCutiController::class, 'index'])->name('admin.cuti.index');
            Route::get('/cuti/{cuti}', [\App\Http\Controllers\Admin\PengajuanCutiController::class, 'show'])->name('admin.cuti.show');
            Route::patch('/cuti/{cuti}/status', [\App\Http\Controllers\Admin\PengajuanCutiController::class, 'updateStatus'])->name('admin.cuti.status');
        });
    });

    // ------------------------------------------
    // PEGAWAI SECTION
    // ------------------------------------------
    Route::prefix('pegawai')->group(function () {
        Route::get('/dashboard', function () {
            $username = session('username');
            $user = \App\Models\User::where('username', $username)->first();
            return view('pages.pegawai.dashboard', [
                'username' => $username,
                'user' => $user,
            ]);
        })->name('dashboard.pegawai');

        // Employee Management (Shared/Admin focus)
        Route::get('/export', [\App\Http\Controllers\Pegawai\PegawaiController::class, 'export'])->name('pegawai.export');
        Route::get('/search', [\App\Http\Controllers\Pegawai\PegawaiController::class, 'search'])->name('pegawai.search');
        Route::resource('data', \App\Http\Controllers\Pegawai\PegawaiController::class)->parameters([
            'data' => 'pegawai'
        ])->names([
            'index' => 'pegawai.index',
            'create' => 'pegawai.create',
            'store' => 'pegawai.store',
            'show' => 'pegawai.show',
            'edit' => 'pegawai.edit',
            'update' => 'pegawai.update',
            'destroy' => 'pegawai.destroy',
        ]);

        // Pegawai Features: My Attendance & My Leave
        Route::prefix('fitur')->group(function () {
            Route::get('/absensi', [\App\Http\Controllers\Pegawai\AbsensiController::class, 'index'])->name('pegawai.absensi.index');
            Route::post('/absensi/masuk', [\App\Http\Controllers\Pegawai\AbsensiController::class, 'masuk'])->name('pegawai.absensi.masuk');
            Route::post('/absensi/pulang', [\App\Http\Controllers\Pegawai\AbsensiController::class, 'pulang'])->name('pegawai.absensi.pulang');
            Route::post('/absensi/check-location', [\App\Http\Controllers\Pegawai\AbsensiController::class, 'checkLocation'])->name('pegawai.absensi.checkLocation');

            Route::resource('cuti', \App\Http\Controllers\Pegawai\PengajuanCutiController::class)->names([
                'index' => 'pegawai.cuti.index',
                'create' => 'pegawai.cuti.create',
                'store' => 'pegawai.cuti.store',
                'show' => 'pegawai.cuti.show',
                'edit' => 'pegawai.cuti.edit',
                'update' => 'pegawai.cuti.update',
                'destroy' => 'pegawai.cuti.destroy',
            ]);
        });
    });
});
