<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->group(base_path('routes/v1/admin.php'));

Route::prefix('/auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/logout', 'logout');
});

// serves logged-user related functions. so implementing separate CustomerController as a resource
Route::prefix('/profile')->middleware('authenticate')->controller(ProfileController::class)->group(function () {
    Route::get('/', 'index');
    Route::put('/', 'update');
    Route::post('/fulfillBalance', 'topUpBalance');
    Route::get('/orders', 'orders');
});

// only for admin usage
Route::prefix('/customers')->middleware('authenticate:admin')
    ->controller(CustomerController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::put('/{customer}', 'update');
        Route::post('/', 'store');
        Route::delete('/{id}', 'destroy');
    });

Route::prefix('/products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{product}', 'show');

    // needs authentication via admin guard to enter these endpoints
    Route::middleware('authenticate:admin')->group(function () {
        Route::post('/', 'store');
        Route::put('/{product}', 'update');
        Route::delete('/{product}', 'destroy');
    });
});

Route::prefix('/categories')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');

    Route::middleware('authenticate:admin')->group(function () {
        Route::post('/', 'store');
        Route::put('/{category}', 'update');
        Route::delete('/{category}', 'destroy');
    });
});

Route::prefix('/orders')->controller(OrderController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{order}', 'show');
    Route::post('/', 'store');
    Route::put('/{order}', 'update');
    Route::delete('/{order}', 'destroy'); // actually shouldn't be able to destroy. only confirm or cancel
});
