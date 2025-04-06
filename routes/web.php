<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Routes admin
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Ornaments routes
    Route::resource('ornaments', \App\Http\Controllers\Admin\OrnamentController::class)->names([
        'index' => 'ornaments.index',
        'create' => 'ornaments.create',
        'store' => 'ornaments.store',
        'show' => 'ornaments.show',
        'edit' => 'ornaments.edit',
        'update' => 'ornaments.update',
        'destroy' => 'ornaments.destroy'
    ]);
    Route::post('ornaments/{ornament}/versions', [\App\Http\Controllers\Admin\OrnamentController::class, 'storeVersion'])->name('ornaments.versions.store');
    Route::delete('ornaments/{ornament}/versions/{version}', [\App\Http\Controllers\Admin\OrnamentController::class, 'destroyVersion'])->name('ornaments.versions.destroy');

    // Jerseys routes
    Route::resource('jerseys', \App\Http\Controllers\Admin\JerseyController::class)->names([
        'index' => 'jerseys.index',
        'create' => 'jerseys.create',
        'store' => 'jerseys.store',
        'show' => 'jerseys.show',
        'edit' => 'jerseys.edit',
        'update' => 'jerseys.update',
        'destroy' => 'jerseys.destroy'
    ]);
    Route::post('jerseys/{jersey}/ornaments', [\App\Http\Controllers\Admin\JerseyController::class, 'storeOrnament'])->name('jerseys.ornaments.store');
    Route::delete('jerseys/{jersey}/ornaments/{ornament}', [\App\Http\Controllers\Admin\JerseyController::class, 'destroyOrnament'])->name('jerseys.ornaments.destroy');

    // Users routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'show' => 'users.show',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy'
    ]);

    // User Jerseys routes
    Route::resource('user-jerseys', \App\Http\Controllers\Admin\UserJerseyController::class)->names([
        'index' => 'user-jerseys.index',
        'create' => 'user-jerseys.create',
        'store' => 'user-jerseys.store',
        'show' => 'user-jerseys.show',
        'edit' => 'user-jerseys.edit',
        'update' => 'user-jerseys.update',
        'destroy' => 'user-jerseys.destroy'
    ]);

    // Settings routes
    Route::resource('settings', \App\Http\Controllers\Admin\SettingController::class)->only(['index', 'update']);
});

// Editor routes
Route::middleware(['auth'])->group(function () {
    Route::get('/editor', [\App\Http\Controllers\EditorController::class, 'index'])->name('editor.index');
    Route::get('/editor/jersey/{jersey}', [\App\Http\Controllers\EditorController::class, 'edit'])->name('editor.edit');
    Route::post('/editor/store', [\App\Http\Controllers\EditorController::class, 'store'])->name('editor.store');
    Route::post('/editor/send-prompt', [\App\Http\Controllers\EditorController::class, 'sendPrompt'])->name('editor.sendPrompt');
});

require __DIR__.'/auth.php';
