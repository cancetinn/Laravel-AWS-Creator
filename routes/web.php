<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

// Başlangıçta yalnızca bir kez tanımlayın
Route::middleware(['auth', /* 'verified' */])->group(function () {
    Route::get('/dashboard', [ServerController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    #servers
    Route::get('/dashboard/servers', [ServerController::class, 'index'])->name('servers.index');
    Route::get('/dashboard/servers/create', [ServerController::class, 'create'])->name('servers.create');
    Route::post('/dashboard/servers', [ServerController::class, 'store'])->name('servers.store');

    // Sunucu metrikleri için API rotası
    Route::get('/api/server/{instanceId}/metrics', [ServerController::class, 'getMetrics']);
    
    // Yeni metrik görüntüleme sayfası rotası
    Route::get('/dashboard/servers/{instanceId}/view', [ServerController::class, 'viewMetrics'])->name('servers.view');

    //Servis
    Route::get('/api/server/statuses', [ServerController::class, 'getServerStatuses'])->name('servers.statuses');

    //AWS Service start-stop
    Route::post('/api/server/{instanceId}/start', [ServerController::class, 'startInstance'])->name('server.start');
    Route::post('/api/server/{instanceId}/stop', [ServerController::class, 'stopInstance'])->name('server.stop');
    Route::post('/api/server/{instanceId}/restart', [ServerController::class, 'restartInstance'])->name('server.restart');

    Route::get('/api/server/{instanceId}/status', [ServerController::class, 'getInstanceStatus']);

});

require __DIR__.'/auth.php';
