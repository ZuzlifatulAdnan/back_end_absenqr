<?php

use App\Http\Controllers\Api\AbsenController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BerandaController;
use App\Http\Controllers\Api\IzinController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::get('/profile/edit', [ProfileController::class, 'edit']);
    Route::put('/profile/update', [ProfileController::class, 'update']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    // izin
    Route::get('/izin', [IzinController::class, 'index']);
    Route::post('/izin', [IzinController::class, 'store']);
    Route::get('/izin/{id}', [IzinController::class, 'show']);
    // beranda
    Route::get('/beranda', [BerandaController::class, 'index']);
    // absen
    Route::get('/absen', [AbsenController::class, 'index']);
    Route::get('/absen/{id}', [AbsenController::class, 'show']);
    Route::get('/absen/{id}/rekap', [AbsenController::class, 'rekap']);
    Route::post('/absen/scan', [AbsenController::class, 'submitScan']);
});

