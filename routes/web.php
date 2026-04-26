<?php

use App\Http\Controllers\AppDownloadController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk Landing Page
Route::get('/', function () {
    // Get available app versions for download
    $appDownloadController = new AppDownloadController;
    $availableVersions = $appDownloadController->getAvailableVersions();

    return Inertia::render('LandingPage', [
        'availableVersions' => $availableVersions,
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('landing');

// Rute untuk download aplikasi
Route::get('/download-app/{filename}', [AppDownloadController::class, 'downloadApp'])
    ->name('app.download')
    ->where('filename', '[^/]+');

// Rute Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('super_admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/users/{user}/qrcode/regenerate', [QRCodeController::class, 'regenerate'])->name('users.qrcode.regenerate');
        Route::get('/users/{user}/qrcode/download', [QRCodeController::class, 'download'])->name('users.qrcode.download');
    });

    Route::resource('products', ProductController::class);
    Route::post('products/verify-code', [ProductController::class, 'verifyProductCode'])
        ->name('products.verify-code');

    // Rute untuk halaman pelanggan dan transaksi (Anggota 6)
    Route::resource('customers', CustomerController::class);
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);
});

// Rute Autentikasi lainnya (Breeze sudah membuat ini)
require __DIR__.'/auth.php';
