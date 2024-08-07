<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


#servers
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ServerController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/servers', [ServerController::class, 'index'])->name('servers.index');
    Route::get('/dashboard/servers/create', [ServerController::class, 'create'])->name('servers.create');
    Route::post('/dashboard/servers', [ServerController::class, 'store'])->name('servers.store');
});



require __DIR__.'/auth.php';
