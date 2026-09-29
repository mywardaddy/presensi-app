<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
//Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AbsensiAdminController;
use App\Http\Controllers\Admin\InstansiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\UserImportController;

//User
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\AbsensiUserController;
use App\Http\Controllers\User\ReportUserController;
use App\Http\Controllers\User\SettingUserController;
use App\Http\Controllers\User\JadwalUserController;

Route::get('/storage-link', function () { 
    $targetFolder = base_path().'/storage/app/public'; 
    $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage'; 
    symlink($targetFolder, $linkFolder); 
});

Route::get('reset', function (){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'authenticate')->name('login');
});

    Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('jadwal', [JadwalUserController::class, 'index'])->name('jadwal.index');
});


Route::get('/', [LoginController::class, 'index'])->middleware('guest');

// Authentication
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

//Admin Dashboard
Route::prefix('admin')
    ->middleware(['auth','admin'])
    ->group(function() {
        Route::get('/dashboard',[DashboardController::class, 'index'])->name('admin-dashboard');

        // Data Absensi
        Route::resource('presensi', AbsensiAdminController::class);
        Route::post('/reset-data',[AbsensiAdminController::class, 'reset_data'])->name('reset-data');
        
        // Face Validation Route
        Route::post('/validate-face', [AbsensiUserController::class, 'validateFace'])
            ->name('user.validate.face');

        // Data Instansi
        Route::resource('instansi', InstansiController::class);

        // Data User
        Route::resource('user', UserController::class);

        // Data Laporan
        Route::post('/users/import', [UserImportController::class, 'import'])->name('users.import');
        Route::resource('report', ReportAdminController::class);
        Route::get('/export-data-absensi/{date1}/{date2}/{user_id}', [ReportAdminController::class, 'export_data'])->name('export-data-absensi');
        Route::get('/laporan-total', [ReportAdminController::class, 'laporan_total'])->name('laporan-total');
        Route::get('/export-data-total/{date1}/{date2}', [ReportAdminController::class, 'export_total'])->name('export-total-absensi');

        // Pengaturan
        Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
        Route::put('setting/{id}', [SettingController::class, 'update'])->name('setting.update');
        Route::post('setting/upload-profile', [SettingController::class, 'upload_profile'])->name('profile-upload');
        Route::get('setting/delete-profile/{id}', [SettingController::class, 'destroy_profile'])->name('profile-delete');
        Route::get('setting/password', [SettingController::class, 'change_password'])->name('change-password');
        Route::post('change-password', [SettingController::class, 'update_password'])->name('update.password');

        // Setup Aplikasi
        Route::get('setting/application', [SettingController::class, 'change_application'])->name('change-application');
        Route::put('setting/application/{id}', [SettingController::class, 'update_application'])->name('update.application');
        Route::post('setting/upload-logo', [SettingController::class, 'upload_logo'])->name('logo-upload');
        Route::get('setting/delete-logo/{id}', [SettingController::class, 'destroy_logo'])->name('logo-delete');
        Route::post('setting/upload-favicon', [SettingController::class, 'upload_favicon'])->name('favicon-upload');
        Route::get('setting/delete-favicon/{id}', [SettingController::class, 'destroy_favicon'])->name('favicon-delete');
        Route::post('setting/upload-wallpaper', [SettingController::class, 'upload_wallpaper'])->name('wallpaper-upload');
        Route::get('setting/delete-wallpaper/{id}', [SettingController::class, 'destroy_wallpaper'])->name('wallpaper-delete');

        // Jadwal
        Route::resource('jadwal', JadwalController::class);
        Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    });

//User Dashboard
Route::prefix('user')
    ->middleware(['auth','user'])
    ->group(function(){
        Route::get('/dashboard-user',[DashboardUserController::class, 'index'])->name('user-dashboard');

        //Data Absensi
        Route::resource('absensi', AbsensiUserController::class);
        Route::get('/absensi-pulang',[AbsensiUserController::class, 'get_back'])->name('absensi-pulang');
        
        //Data Laporan
        Route::resource('report-user', ReportUserController::class);
        Route::get('/export-absensi-user/{date1}/{date2}/{user_id}',[ReportUserController::class, 'export_data'])->name('export-absensi-user');

        //Pengaturan
        Route::get('setting-user',[SettingUserController::class, 'index'])->name('setting-user.index');
        Route::put('setting-user/{id}',[SettingUserController::class, 'update'])->name('setting-user.update');
        Route::post('setting-user/upload-profile', [SettingUserController::class, 'upload_profile'])->name('profile-upload-user');
        Route::get('setting-user/delete-profile/{id}',[SettingUserController::class, 'destroy_profile'])->name('profile-delete-user');
        Route::get('setting-user/password',[SettingUserController::class, 'change_password'])->name('change-password-user');
        Route::post('change-password-user', [SettingUserController::class, 'update_password'])->name('update.password-user');

        // Jadwal
        Route::get('jadwal-user', [JadwalUserController::class, 'index'])->name('jadwal-user.index');

    });