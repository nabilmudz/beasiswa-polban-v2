<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HistoryMahasiswaPenerimaController;
use App\Http\Controllers\MaddingController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PenerimaBeasiswaController;
use App\Http\Controllers\PengajuanBeasiswaController;
use App\Http\Controllers\PengaturanController;
use Illuminate\Support\Facades\Route;


// ========================================================================================
// AUTHENTICATION ROUTES ==================================================================
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'login')->name('login.submit');
    Route::post('/register','register')->name('auth.register');

    // Forgot password process
    Route::get('/reset-password', 'showResetPasswordForm')->name('password.forgot');
    Route::post('/verify-code', 'verifyCode')->name('password.verifyCode');
    Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
    Route::post('/change-password', 'changePassword')->name('password.change');
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/mahasiswa/create/{id}','insertMahasiswaData')->name('mahasiswa.insert');
    Route::get('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email');



    // Register Information
    Route::get('/register-information/{id}', [AuthController::class, 'getRegisterInformation'])->name('auth.register-information');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('auth.showregister');
});

Route::middleware(['auth', 'check.role:mahasiswa'])->group(function () {
    Route::controller(PengajuanBeasiswaController::class)->group(function () {
        Route::get('/pengajuan-beasiswa/{id}',[PengajuanBeasiswaController::class, 'create'])->name('pengajuan.create');
        Route::get('/pengajuan-beasiswa/edit/{id}',[PengajuanBeasiswaController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/store/{id}', [PengajuanBeasiswaController::class, 'store'])->name('pengajuan.store');
        Route::patch('/pengajuan/edit/{id}',[PengajuanBeasiswaController::class, 'edit'])->name('pengajuan.edit');
    });
});

// Route untuk list beasiswa - diakses oleh mahasiswa dan ketua jurusan
Route::middleware(['auth'])->group(function () {
    Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa.index');
});

// Route untuk Ketua Jurusan mengajukan beasiswa untuk mahasiswa
Route::middleware(['auth', 'check.role:reviewer'])->group(function () {
    Route::get('/pengajuan-beasiswa-kajur/{id}', [PengajuanBeasiswaController::class, 'createForKajur'])->name('pengajuan.create-kajur');
    Route::post('/pengajuan-kajur/store/{id}', [PengajuanBeasiswaController::class, 'storeForKajur'])->name('pengajuan.store-kajur');
});

// Route untuk Staff Kemahasiswaan, WD3, dll (BUKAN Ketua Jurusan) - Full CRUD
Route::middleware(['auth', 'check.role:reviewer', 'not.kajur'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/import-data-beasiswa', [BeasiswaController::class, 'getImportDataBeasiswa'])->name('beasiswa.import-data-beasiswa');
    Route::get('/list-pengaju-beasiswa', [BeasiswaController::class,'getListPengajuBeasiswa'])->name('beasiswa.list-pengaju-beasiswa');
    Route::get('/beasiswa/create', [BeasiswaController::class, 'create'])->name('beasiswa.create');
    Route::post('/beasiswa/store', [BeasiswaController::class, 'store'])->name('beasiswa.store');
    Route::delete('/beasiswa/destroy/{id}', [BeasiswaController::class, 'destroy'])->name('beasiswa.destroy');
    Route::patch('/beasiswa/update/{id}', [BeasiswaController::class, 'update'])->name('beasiswa.update');
    Route::get('/beasiswa/edit/{id}', [BeasiswaController::class, 'edit'])->name('beasiswa.edit');
    Route::get('/list-beasiswa-staff', [BeasiswaController::class, 'getListBeasiswaForStaff'])->name('beasiswa.list-beasiswa-staff');
    Route::get('/import-data-penerima', [PenerimaBeasiswaController::class, 'create'])->name('beasiswa.import-data-beasiswa');
    Route::post('/import-data-penerima', [PenerimaBeasiswaController::class, 'store'])->name('penerimabeasiswa.import-data-beasiswa');
    Route::get('/beasiswa/search-syarat', [BeasiswaController::class, 'searchSyarat'])->name('Beasiswa.search_syarat');
    Route::get('/beasiswa/search-dokumen', [BeasiswaController::class, 'searchDokumen'])->name('Beasiswa.search_dokumen');
    Route::get('/beasiswa/search-benefit', [BeasiswaController::class, 'searchBenefit'])->name('Beasiswa.search_benefit');
    Route::get('/beasiswa/search-jenjang', [BeasiswaController::class, 'searchJenjang'])->name('Beasiswa.search_jenjang');
    Route::get('/beasiswa/get-templates', [BeasiswaController::class, 'getBeasiswaTemplate'])->name('Beasiswa.getTemplates');
    Route::get('/beasiswa/get-beasiswa/{id}', [BeasiswaController::class, 'getBeasiswa'])->name('Beasiswa.getBeasiswa');
    Route::post('/export-pengumuman-beasiswa/{id}',[PenerimaBeasiswaController::class, 'exportPenerimaBeasiswaInExcel'])->name('beasiswa.export-data-beasiswa');

    // History Penerima Beasiswa Routes (2024-2025)
    Route::get('/history-penerima', [HistoryMahasiswaPenerimaController::class, 'index'])->name('history-penerima.index');
    Route::get('/history-penerima/import', [HistoryMahasiswaPenerimaController::class, 'importForm'])->name('history-penerima.import-form');
    Route::post('/history-penerima/import', [HistoryMahasiswaPenerimaController::class, 'import'])->name('history-penerima.import');
    Route::get('/history-penerima/export', [HistoryMahasiswaPenerimaController::class, 'export'])->name('history-penerima.export');
    Route::delete('/history-penerima/{id}', [HistoryMahasiswaPenerimaController::class, 'destroy'])->name('history-penerima.destroy');

});

Route::controller(PengajuanBeasiswaController::class)->group(function () {
    Route::get('pengajuan/list-pengajuan',[PengajuanBeasiswaController::class, 'listPengajuanStaff'])->name('pengajuan.list-pengajuan');
    Route::get('pengajuan/export',[PengajuanBeasiswaController::class, 'exportPengajuan'])->name('pengajuan.export');
    Route::get('/tracking-pengajuan/{id}', [PengajuanBeasiswaController::class, 'showTracking'])->name('pengajuan.tracking');
    Route::patch('/pengajuan/progress/{id}', [PengajuanBeasiswaController::class, 'progressPengajuan'])->name('pengajuan.update-progress');
    Route::delete('/tracking-pengajuan/{id}', [PengajuanBeasiswaController::class, 'batalkanPengajuan'])->name('pengajuan.batalkan-pengajuan');
});


Route::post('/upload',[FileController::class,'uploadFile'])->name('upload.uploadFile');

// Route::middleware('auth')->group(function () {
// PENGAJUAN ROUTES =======================================================================
Route::middleware('auth')->group(function () {
    Route::get('/beasiswa/search-syarat', [BeasiswaController::class, 'searchSyarat'])->name('Beasiswa.search_syarat');
    Route::resource('tracking-pengajuan', PengajuanBeasiswaController::class);
    Route::get('/pengumuman-beasiswa/{id}', [PenerimaBeasiswaController::class, 'show'])->name('beasiswa.pengumuman-beasiswa');
    Route::get('/pengumuman-beasiswa', [PenerimaBeasiswaController::class, 'index'])->name('pengumuman-beasiswa.index');
    Route::get('/notifications', [NotificationController::class, 'getNotifData'])->name('notifications.index');
    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead']);


    Route::resource('pengaturan', PengaturanController::class);
    Route::patch('/pengaturan/updatefoto/{user}', [PengaturanController::class, 'updatefoto'])->name('pengaturan.updatefoto');
    Route::patch('/pengaturan/updateprofil/{user}', [PengaturanController::class, 'updateprofil'])->name('pengaturan.updateprofil');
    Route::get('/pengajuan/list-pengajuan',[PengajuanBeasiswaController::class, 'listPengajuanStaff'])->name('pengajuan.list-pengajuan');
    Route::post('/notify-reviewer', [MailController::class, 'notifyReviewer']);
    Route::get('/file/{path}', [FileController::class, 'getFile'])->name('getFile');
    Route::controller(MaddingController::class)->group(function () {
        Route::get('/madding', [MaddingController::class, 'index'])->name('madding.index');
    });
    Route::get('/beasiswa/{id}', [BeasiswaController::class, 'show'])
     ->where('beasiswa', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')// <-- Tambahkan baris ini
     ->name('beasiswa.show');
    Route::get('/detail-beasiswa-kipk/{id}', [BeasiswaController::class, 'getDetailBeasiswaKipk'])->name('beasiswa.detail-beasiswa-kipk');
    Route::get('/detail-beasiswa-eksternal/{id}', [BeasiswaController::class, 'getDetailBeasiswaEksternal'])->name('beasiswa.detail-beasiswa-eksternal');
});

Route::get('/file/public/{path}', [FileController::class, 'getFilePublic'])->name('getFilePublic');

// ========================================================================================
// PUBLIC ROUTES ==========================================================================
Route::get('/madding', [MaddingController::class, 'index'])->name('public.madding');
// Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa.index');

Route::redirect('/', '/madding');
