<?php

use App\Http\Controllers\Admin\AdminController;
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
Route::prefix('/profile')->middleware('authenticate:customer')
    ->controller(ProfileController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::put('/', 'update');
        Route::post('/fulfillBalance', 'topUpBalance');
        Route::get('/orders', 'orders');
});


Route::prefix('/customers')->controller(CustomerController::class)->group(function () {
    Route::middleware('authenticate:admin')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::delete('/{customer}', 'destroy');
    });

    // with argument 'any' it would iterate through guards until finds the right one.
    // so it assures that the request is authenticated by any guard
    Route::middleware('authenticate:any')->group(function () {
        // customer should be able to see and update its own profile, so
        // additional authorization checks for that are inside form requests
        Route::get('/{customer}', 'show');
        Route::put('/{customer}', 'update');
    });
});

Route::prefix('/admins')->middleware('authenticate:admin')
    ->controller(AdminController::class)
    ->group(function () {
       Route::get('/', 'index');
       Route::get('/{admin}', 'show');
       Route::put('/{admin}', 'update');
       Route::post('/', 'store');
       Route::delete('/{admin}', 'destroy');
    });

Route::prefix('/products')->controller(ProductController::class)->group(function () {
    Route::middleware('authenticate:admin')->group(function () {
        Route::post('/', 'store');
        Route::put('/{product}', 'update');
        Route::delete('/{product}', 'destroy');
    });

    Route::get('/', 'index');
    Route::get('/{product}', 'show');
});

Route::prefix('/categories')->controller(CategoryController::class)->group(function () {
    Route::middleware('authenticate:admin')->group(function () {
        Route::post('/', 'store');
        Route::put('/{category}', 'update');
        Route::delete('/{category}', 'destroy');
    });

    Route::get('/', 'index');
    Route::get('/{id}', 'show');
});

Route::prefix('/orders')->controller(OrderController::class)->group(function () {
    Route::middleware('authenticate:admin')->group(function () {
        Route::get('/', 'index');
        Route::put('/{order}', 'update');
        Route::delete('/{order}', 'destroy'); // actually shouldn't be able to destroy. only confirm or cancel
    });

    Route::middleware('authenticate:any')->group(function () {
        Route::get('/{order}', 'show');
        Route::post('/', 'store');
    });
});
