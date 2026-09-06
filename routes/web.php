<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\OperatorPackageController;
use App\Http\Controllers\ProfileController;
use App\Models\Package;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home', ['packages' => Package::where('is_available', true)->take(2)->get()]))->name('home');
Route::get('/packages', fn () => view('packages.index', [
    'packages' => Package::where('is_available', true)
        ->with(['bookings' => fn ($query) => $query->whereIn('status', ['pending', 'approved'])])
        ->get(),
]))->name('packages.index');
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'createAccount'])->name('register.store');
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateInformation'])->name('profile.information.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/operator', [OperatorController::class, 'index'])->name('operator.index');
    Route::patch('/operator/bookings/{booking}', [OperatorController::class, 'update'])->name('operator.bookings.update');
    Route::get('/operator/packages', [OperatorPackageController::class, 'index'])->name('operator.packages.index');
    Route::post('/operator/packages', [OperatorPackageController::class, 'store'])->name('operator.packages.store');
    Route::put('/operator/packages/{package}', [OperatorPackageController::class, 'update'])->name('operator.packages.update');
    Route::delete('/operator/packages/{package}', [OperatorPackageController::class, 'destroy'])->name('operator.packages.destroy');
});
