<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ManageController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::prefix('manage')->group(function () {
    Route::middleware('authenticate:admin')->post('/report', [ManageController::class, 'report']);
    Route::get('/downloadReport/{filename}', [ManageController::class, 'downloadReport'])->name('manage.downloadReport');
});
