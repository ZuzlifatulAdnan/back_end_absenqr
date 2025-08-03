<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AbsenQrController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Exports\RekapAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;


Route::redirect('/', '/beranda');

Route::middleware(['auth'])->group(function () {
    // beranda
    Route::resource('beranda', BerandaController::class);
    // absen qr
    Route::get('/absen/view/{id}', [AbsenQrController::class, 'view'])->name('qr.view');
    Route::resource('absen/qr', AbsenQrController::class);
    Route::get('/absen/qr/{id}/download', [AbsenQrController::class, 'downloadPDF'])->name('absenqr.download');
    ;
    // Absen
    // qr
    Route::get('/absen/qr/{id}/create-add', [AbsenQrController::class, 'createAdd'])->name('qr.createAdd');
    Route::post('/absen/qr/{id}/store-add', [AbsenQrController::class, 'storeAdd'])->name('qr.storeAdd');
    Route::get('/absen/qr/ubah/{id}', [AbsenQrController::class, 'ubah'])->name('qr.ubah');
    Route::patch('/absen/qr/update/{absen_qr}', [AbsenQrController::class, 'updateUbah'])->name('qr.updateUbah');

    Route::get('/absen/scan', [AbsenController::class, 'scanForm'])->name('absen.scan.form');
    Route::post('/absen/scan', [AbsenController::class, 'submitScan'])->name('absen.scan');
    //  add absen manual
    Route::get('/absen/add/{absen}', [AbsenController::class, 'add'])->name('absen.add');
    Route::post('/absen/add/{absen}', [AbsenController::class, 'store'])->name('absen.store');
    Route::resource('absen', AbsenController::class);
    Route::get('/absen/rekap/{absen}', [AbsenController::class, 'rekap'])->name('absen.rekap');
    // Guru
    Route::resource('guru', GuruController::class);
    // Jadwal
    Route::resource('jadwal', JadwalController::class);
    // kelas
    Route::resource('kelas', KelasController::class)->parameters([
        'kelas' => 'kelas'
    ]);
    // mapel
    Route::resource('mapel', MapelController::class);
    // siswa
    Route::resource('siswa', SiswaController::class);
    // user
    Route::resource('user', UserController::class);
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change-password-form');
    Route::post('profile/change-password/{user}', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::get('/rekap/export/{id}', function ($id) {
        return Excel::download(new RekapAbsensiExport($id), 'rekap-absensi.xlsx');
    })->name('absen.rekap.export');

});
