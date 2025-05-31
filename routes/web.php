<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'login']);

// Authentication routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');


// URL redirection
Route::get('/s/{shortCode}', [UrlController::class, 'redirect'])->name('urls.redirect');
Route::get('/exit/{shortCode}', [UrlController::class, 'showExitPage'])->name('urls.exit');

// Protected routes 
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // URL Creation
    Route::get('/create', [UrlController::class, 'create'])->name('urls.create');
    Route::post('/create', [UrlController::class, 'store'])->name('urls.store');
    Route::get('/success', [UrlController::class, 'success'])->name('urls.success');

    // URL Management Routes
    Route::get('/urls', [UrlController::class, 'index'])->name('urls.index');
    Route::get('/urls/{url}/edit', [UrlController::class, 'edit'])->name('urls.edit');
    Route::post('/urls/{url}', [UrlController::class, 'update'])->name('urls.update');
    Route::delete('/urls/{url}', [UrlController::class, 'destroy'])->name('urls.destroy');
    Route::patch('/urls/{url}/toggle', [UrlController::class, 'toggleStatus'])->name('urls.toggle');
    Route::get('/urls/{url}/view', [UrlController::class, 'view'])->name('urls.view');


     // User profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.update-password');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
