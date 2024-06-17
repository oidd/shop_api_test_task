<?php

use Illuminate\Support\Facades\Route;

Route::get('/hey', function () {
    return response()->json('oh hello there');
});
