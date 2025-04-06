<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserJerseyController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User Jersey routes
    Route::apiResource('user-jerseys', UserJerseyController::class);
    Route::get('/ornaments/{ornament}/versions', [UserJerseyController::class, 'getOrnamentVersions']);
}); 