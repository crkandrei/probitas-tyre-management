<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\TyreController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Authentication and Dashboard
Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Tyre Related Routes
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/tyres', [TyreController::class, 'index'])->name('tyres.index');
    Route::post('/tyres/check-in', [TyreController::class, 'checkIn'])->name('tyres.check-in');
    Route::get('/tyre-list', function () {
        return Inertia::render('TyreList');
    })->name('tyre-list');
});

Route::get('/tyre/generate-checkout-document/{tyre}', [TyreController::class, 'generateCheckoutDocument'])
    ->middleware(['auth', 'verified'])
    ->name('tyre.generate-checkout-document');

Route::get('/tyre/generate-checkin-document/{tyre}', [TyreController::class, 'generateCheckinDocument'])
    ->middleware(['auth', 'verified'])
    ->name('tyre.generate-checkin-document');

Route::get('/clients/{client}/history', [ClientController::class, 'history'])
    ->middleware(['auth', 'verified'])
    ->name('clients.history');

Route::get('clients/list', [ClientController::class, 'list'])
    ->middleware(['auth', 'verified'])
    ->name('clients.list');

Route::post('/client/add', [ClientController::class, 'add'])
    ->middleware(['auth', 'verified'])
    ->name('clients.add');


Route::get('/dashboard-stats', [DashboardController::class, 'getStats'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.stats');

Route::get('/client-list', function () {
    return Inertia::render('ClientList');
})->name('client-list');

Route::get('/clients', [ClientController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('client.index');

Route::put('/clients/{client}', [ClientController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('client.update');

Route::delete('/clients/{client}', [ClientController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('client.destroy');

// Tyre Storage
Route::post('/tyre-storage-link', [StorageController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('tyre-storage-link');

Route::put('/tyre-storage-link/{tyre}', [StorageController::class, 'updateStorage'])
    ->middleware(['auth', 'verified'])
    ->name('tyre-storage-link.update');

// web.php or api.php
Route::post('/tyres/checkout/{tyre}', [TyreController::class, 'checkout'])
    ->middleware(['auth', 'verified'])
    ->name('tyres.checkout');

Route::put('/tyres/{tyre}', [TyreController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('tyres.update');

// Check-in Route
Route::get('/checkin', function (Request $request) {
    return Inertia::render('Checkin', [
        'client_id' => $request->input('client_id', ''),
        'car_number' => $request->input('car_number', ''),
    ]);
})->middleware(['auth', 'verified'])->name('checkin');


// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/excel/export', [TyreController::class, 'export'])
    ->middleware(['auth', 'verified'])
    ->name('excel.export');

require __DIR__.'/auth.php';
