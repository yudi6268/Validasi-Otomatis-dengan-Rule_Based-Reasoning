<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\BrowsershotPdfController;

// LOGIN (boleh diakses meskipun sudah login)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// REGISTER tetap pakai guest
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        // Admin langsung ke dashboard admin
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    })->name('root');

    // Dashboard controller untuk redirect otomatis
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'home'])->name('home');

    // Dashboard direktur - hanya untuk Direktur
    Route::middleware(['check.jabatan:Direktur'])->group(function () {
        Route::get('/dashboard/direktur', [App\Http\Controllers\DirekturDashboardController::class, 'index'])->name('dashboard.direktur');
        Route::get('/dashboard/direktur/perjanjian-kinerja', [App\Http\Controllers\DirekturDashboardController::class, 'perjanjianKinerja'])->name('direktur.perjanjian');
        Route::get('/dashboard/direktur/perjanjian-list', [App\Http\Controllers\DirekturDashboardController::class, 'perjanjianList'])->name('direktur.perjanjian.list');
        Route::get('/dashboard/direktur/laporan-list', [App\Http\Controllers\DirekturDashboardController::class, 'laporanList'])->name('direktur.laporan.list');
        Route::get('/dashboard/direktur/perjanjian/{id}', [App\Http\Controllers\DirekturDashboardController::class, 'showPerjanjian'])->name('direktur.perjanjian.show');
        Route::get('/dashboard/direktur/perjanjian/{id}/print', [App\Http\Controllers\DirekturDashboardController::class, 'printPerjanjian'])->name('direktur.perjanjian.print');
        Route::get('/dashboard/direktur/perjanjian/{id}/download', [App\Http\Controllers\DirekturDashboardController::class, 'downloadPerjanjian'])->name('direktur.perjanjian.download');
        Route::get('/dashboard/direktur/laporan-kinerja', [App\Http\Controllers\DirekturDashboardController::class, 'laporanKinerja'])->name('direktur.laporan');
        Route::post('/dashboard/direktur/perjanjian/{id}/approve', [App\Http\Controllers\DirekturDashboardController::class, 'approvePerjanjian'])->name('direktur.perjanjian.approve');
        Route::post('/dashboard/direktur/perjanjian/{id}/reject', [App\Http\Controllers\DirekturDashboardController::class, 'rejectPerjanjian'])->name('direktur.perjanjian.reject');
        Route::post('/dashboard/direktur/laporan/{id}/approve', [App\Http\Controllers\DirekturDashboardController::class, 'approveLaporan'])->name('direktur.laporan.approve');
        Route::post('/dashboard/direktur/laporan/{id}/reject', [App\Http\Controllers\DirekturDashboardController::class, 'rejectLaporan'])->name('direktur.laporan.reject');
    });

    // Dashboard Wadir (Umum, Pelayanan, dan Perencanaan/Keuangan digabung)
    Route::middleware(['check.jabatan:Wakil Direktur Umum dan Keuangan,Wakil Direktur Pelayanan,Wakil Direktur Perencanaan dan Keuangan'])->group(function () {
        Route::get('/dashboard/wadir', [DashboardController::class, 'wadir'])->name('dashboard.wadir');
    });

    // Dashboard Kabag.Kabid (Kabag dan Kabid digabung)
    Route::get('/dashboard/kabag.kabid', [DashboardController::class, 'kabagKabid'])->name('dashboard.kabag.kabid');

    // Dashboard Katimker/Staf (dahulu Kasi)
    Route::get('/dashboard/katimker.staf', [DashboardController::class, 'katimkerStaf'])->name('dashboard.katimker.staf');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // Perjanjian & Laporan
    Route::get('/perjanjian', [App\Http\Controllers\PerjanjianController::class, 'index'])->name('perjanjian.index');
    Route::get('/laporan-kinerja', [App\Http\Controllers\LaporanKinerjaController::class, 'index'])->name('laporan.kinerja');
    Route::get('/laporan-kinerja/wadir', [App\Http\Controllers\LaporanKinerjaController::class, 'wadirIndex'])->name('laporan.wadir.index');
    Route::get('/laporan-kinerja/validasi-summary', [App\Http\Controllers\LaporanKinerjaController::class, 'validasiSummaryPage'])->name('laporan.validasi.summary');
    Route::get('/laporan/{id}/pdf/preview', [App\Http\Controllers\LaporanKinerjaController::class, 'pdfPreview'])->name('laporan.pdf.preview');
    Route::delete('/laporan/{id}', [App\Http\Controllers\LaporanKinerjaController::class, 'destroy'])->name('laporan.destroy');
    Route::post('/api/realisasi/perjanjian', [App\Http\Controllers\LaporanKinerjaController::class, 'saveRealisasi'])->name('api.realisasi.perjanjian');
    Route::post('/api/realisasi/{laporanId}', [App\Http\Controllers\LaporanKinerjaController::class, 'saveRealisasi'])->name('api.realisasi.laporan');
    
    // Smart Validation API
    Route::get('/api/laporan/by-perjanjian/{perjanjianId}', [App\Http\Controllers\LaporanKinerjaController::class, 'getLaporanByPerjanjian'])->name('api.laporan.by-perjanjian');
    Route::post('/api/laporan/{id}/smart-validate', [App\Http\Controllers\LaporanKinerjaController::class, 'smartValidate'])->name('api.laporan.smart-validate');
    Route::post('/api/laporan/quick-validate', [App\Http\Controllers\LaporanKinerjaController::class, 'quickValidate'])->name('api.laporan.quick-validate');
    
    // Validation Result Persistence API
    Route::post('/api/validasi-laporan', [App\Http\Controllers\LaporanKinerjaController::class, 'saveValidasiResult'])->name('api.validasi.save');
    Route::get('/api/validasi-laporan/{laporanId}/{tw}', [App\Http\Controllers\LaporanKinerjaController::class, 'getValidasiResult'])->name('api.validasi.get');
    Route::get('/perjanjian/create', [App\Http\Controllers\PerjanjianController::class, 'create'])->name('perjanjian.create');
    Route::post('/perjanjian', [App\Http\Controllers\PerjanjianController::class, 'store'])->name('perjanjian.store');
    Route::post('/perjanjian/save', [App\Http\Controllers\PerjanjianController::class, 'savePerjanjian'])->name('perjanjian.save');
    Route::get('/perjanjian/{id}/edit', [App\Http\Controllers\PerjanjianController::class, 'edit'])->name('perjanjian.edit');
    Route::put('/perjanjian/{id}', [App\Http\Controllers\PerjanjianController::class, 'update'])->name('perjanjian.update');
    Route::delete('/perjanjian/{id}', [App\Http\Controllers\PerjanjianController::class, 'destroy'])->name('perjanjian.destroy');
    Route::get('/perjanjian/{id}/print', [App\Http\Controllers\PerjanjianController::class, 'print'])->name('perjanjian.print');
    Route::get('/perjanjian/{id}/pdf', [App\Http\Controllers\PerjanjianController::class, 'exportPdf'])->name('perjanjian.pdf');

    // Route untuk form penolakan perjanjian
    Route::get('/perjanjian/{id}/tolak', [App\Http\Controllers\PerjanjianController::class, 'tolakForm'])->name('perjanjian.tolak.form');

    // Route untuk submit penolakan perjanjian (POST)
    Route::post('/perjanjian/{id}/tolak', [App\Http\Controllers\PerjanjianController::class, 'tolakSubmit'])->name('perjanjian.tolak.submit');

    // API untuk mendapatkan data user berdasarkan jabatan
    Route::get('/api/user-by-jabatan/{jabatan}', [App\Http\Controllers\PerjanjianController::class, 'getUserByJabatan'])->name('api.user.jabatan');

    // API untuk mendapatkan kegiatan berdasarkan program ID
    Route::get('/api/kegiatan-by-program/{programId}', [App\Http\Controllers\PerjanjianController::class, 'getKegiatanByProgram'])->name('api.kegiatan.program');

    // API untuk mendapatkan sub kegiatan berdasarkan kegiatan ID
    Route::get('/api/subkegiatan-by-kegiatan/{kegiatanId}', [App\Http\Controllers\PerjanjianController::class, 'getSubKegiatanByKegiatan'])->name('api.subkegiatan.kegiatan');

    // === PANDUAN ===
    Route::get('/panduan', function () {
        return view('panduan');
    })->name('panduan');
    // === PROFIL ===
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profil.update');
    Route::post('/profil/upload-foto', [ProfileController::class, 'uploadFoto'])->name('profil.upload_foto');
    Route::post('/profil/upload-ttd', [ProfileController::class, 'uploadTTD'])->name('profil.upload_ttd');
    // === KONTAK ===
    Route::get('/kontak', [App\Http\Controllers\KontakController::class, 'show'])->name('kontak');
    Route::post('/kontak/kirim', [App\Http\Controllers\KontakController::class, 'kirim'])->name('kontak.kirim');

    // === TENTANG ===
    Route::get('/tentang', function () {
        return view('tentang');
    })->name('tentang');

    // === SETTING ===
    Route::get('/settings', [SettingsController::class, 'show'])->name('settings');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])
        ->name('settings.password.update');
    Route::post('/settings/email', [SettingsController::class, 'updateEmail'])
        ->name('settings.email.update');

    // === BROWSERSHOT PDF ROUTES ===
    Route::prefix('perjanjian/{id}/pdf')->name('perjanjian.browsershot.')->group(function () {
        Route::get('/download', [App\Http\Controllers\BrowsershotPdfController::class, 'download'])->name('download');
        Route::get('/preview', [App\Http\Controllers\BrowsershotPdfController::class, 'preview'])->name('preview');
        Route::post('/save', [App\Http\Controllers\BrowsershotPdfController::class, 'save'])->name('save');
    });

    // Browsershot diagnostics (local only)
    Route::get('/pdf/diagnostics', [App\Http\Controllers\BrowsershotPdfController::class, 'diagnostics'])->name('pdf.diagnostics');
    Route::get('/pdf/test', [App\Http\Controllers\BrowsershotPdfController::class, 'testPdf'])->name('pdf.test');
});

// === FORGOT PASSWORD - Public Routes ===
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot.form');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendCode'])->name('forgot.post');

Route::get('/verify-code', [ForgotPasswordController::class, 'showVerifyForm'])->name('verify.form');
Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('verify.code');

// === RESET PASSWORD ===
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset.post');

// ===================================
// ADMIN PANEL ROUTES (single group)
// ===================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    /* ================= USERS ================= */
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/pending', [UserController::class, 'pending'])->name('users.pending');
    Route::post('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    /* ================= JABATAN ================= */
    Route::get('/jabatan', [JabatanController::class, 'index'])->name('jabatan.index');
    Route::get('/jabatan/create', [JabatanController::class, 'create'])->name('jabatan.create');
    Route::post('/jabatan', [JabatanController::class, 'store'])->name('jabatan.store');
    Route::get('/jabatan/{jabatan}/edit', [JabatanController::class, 'edit'])->name('jabatan.edit');
    Route::put('/jabatan/{jabatan}', [JabatanController::class, 'update'])->name('jabatan.update');
    Route::delete('/jabatan/{jabatan}', [JabatanController::class, 'destroy'])->name('jabatan.destroy');
    Route::get('/jabatan/diagnostics/test', [JabatanController::class, 'diagnostics'])->name('jabatan.diagnostics');
    Route::get('/jabatan/debug/supabase-insert', [JabatanController::class, 'debugSupabaseInsert'])->name('jabatan.debug.supabase');

    /* ================= PROGRAM, KEGIATAN, SUB KEGIATAN ================= */
    // Program Management
    Route::get('/program', [ProgramController::class, 'index'])->name('program.index');
    Route::get('/program/search', [ProgramController::class, 'search'])->name('program.search');
    Route::get('/program/create', [ProgramController::class, 'createProgram'])->name('program.create');
    Route::post('/program', [ProgramController::class, 'storeProgram'])->name('program.store');
    Route::get('/program/{id}/edit', [ProgramController::class, 'editProgram'])->name('program.edit');
    Route::put('/program/{id}', [ProgramController::class, 'updateProgram'])->name('program.update');
    Route::delete('/program/{id}', [ProgramController::class, 'destroyProgram'])->name('program.destroy');

    // Kegiatan Management
    Route::get('/program/{programId}/kegiatan/create', [ProgramController::class, 'createKegiatan'])->name('kegiatan.create');
    Route::post('/program/{programId}/kegiatan', [ProgramController::class, 'storeKegiatan'])->name('kegiatan.store');
    Route::get('/kegiatan/{id}/edit', [ProgramController::class, 'editKegiatan'])->name('kegiatan.edit');
    Route::put('/kegiatan/{id}', [ProgramController::class, 'updateKegiatan'])->name('kegiatan.update');
    Route::delete('/kegiatan/{id}', [ProgramController::class, 'destroyKegiatan'])->name('kegiatan.destroy');

    // Sub Kegiatan Management
    Route::get('/kegiatan/{kegiatanId}/sub-kegiatan/create', [ProgramController::class, 'createSubKegiatan'])->name('sub-kegiatan.create');
    Route::post('/kegiatan/{kegiatanId}/sub-kegiatan', [ProgramController::class, 'storeSubKegiatan'])->name('sub-kegiatan.store');
    Route::get('/sub-kegiatan/{id}/edit', [ProgramController::class, 'editSubKegiatan'])->name('sub-kegiatan.edit');
    Route::put('/sub-kegiatan/{id}', [ProgramController::class, 'updateSubKegiatan'])->name('sub-kegiatan.update');
    Route::delete('/sub-kegiatan/{id}', [ProgramController::class, 'destroySubKegiatan'])->name('sub-kegiatan.destroy');

    // Quick toggle active/nonactive
    Route::post('/program/{id}/toggle-active', [ProgramController::class, 'toggleProgramActive'])->name('program.toggle-active');
    Route::post('/kegiatan/{id}/toggle-active', [ProgramController::class, 'toggleKegiatanActive'])->name('kegiatan.toggle-active');
    Route::post('/sub-kegiatan/{id}/toggle-active', [ProgramController::class, 'toggleSubKegiatanActive'])->name('sub-kegiatan.toggle-active');

    // API endpoint for dynamic dropdown
    Route::get('/api/program/{programId}/kegiatan', [ProgramController::class, 'getKegiatanByProgram'])->name('api.kegiatan.program');

    // Routes untuk PDF (Browsershot)
    Route::get('/perjanjian-kinerja/{id}/pdf/download', [BrowsershotPdfController::class, 'download'])->name('perjanjian.pdf.download');
    Route::get('/perjanjian-kinerja/{id}/pdf/preview', [BrowsershotPdfController::class, 'preview'])->name('perjanjian.pdf.preview');

    /* ================= PERJANJIAN (ADMIN) ================= */
    Route::get('/perjanjian', [App\Http\Controllers\Admin\PerjanjianController::class, 'index'])->name('perjanjian.index');
    Route::post('/perjanjian/{id}/revisi', [App\Http\Controllers\Admin\PerjanjianController::class, 'revisiStatus'])->name('perjanjian.revisi');
    Route::delete('/perjanjian/{id}', [App\Http\Controllers\Admin\PerjanjianController::class, 'destroy'])->name('perjanjian.destroy');

    /* ================= LAPORAN (ADMIN) ================= */
    Route::get('/laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/{id}/revisi', [App\Http\Controllers\Admin\LaporanController::class, 'revisiStatus'])->name('laporan.revisi');
    Route::delete('/laporan/{id}', [App\Http\Controllers\Admin\LaporanController::class, 'destroy'])->name('laporan.destroy');

    /* ================= NOTIFICATIONS ================= */
    Route::get('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{notification}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');

    /* ================= SETTINGS ================= */
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    
    /* ================= TRIWULAN SETTING ================= */
    Route::get('/triwulan-setting', [App\Http\Controllers\Admin\TriwulanSettingController::class, 'show'])->name('triwulan.setting');
    Route::post('/triwulan-setting', [App\Http\Controllers\Admin\TriwulanSettingController::class, 'update'])->name('triwulan.setting.update');

});
