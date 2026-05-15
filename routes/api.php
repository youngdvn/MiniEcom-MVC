<?php

use App\Http\Controllers\Api\BannerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'roles:1'])->group(function () {
    Route::apiResource('banners', BannerController::class);
});
