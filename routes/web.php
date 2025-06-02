<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/kalender', [CalendarController::class, 'index'])->name('calendar');

Route::resource('customers', CustomerController::class);


// Routes voor gebruikers beheer
Route::resource('users', UserController::class);

// Als je specifieke routes wilt:
// Route::get('/users', [UserController::class, 'index'])->name('users.index');
// Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
// Route::post('/users', [UserController::class, 'store'])->name('users.store');
// Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
// Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
// Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
// Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

// Routes voor instellingen
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

// Reservering routes
Route::get('/reserveren', [ReservationController::class, 'showForm'])->name('reservation.form');
Route::post('/reserveren/verwerken', [ReservationController::class, 'processReservation'])->name('reservation.process');

// Dashboard routes
Route::get('/dashboard', [ReservationController::class, 'dashboard'])->name('dashboard');
Route::put('/reservering/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservation.update.status');

// Reserveringsbeheer routes
Route::get('/reservering/{reservation}/bewerken', [ReservationController::class, 'edit'])->name('reservation.edit');
Route::put('/reservering/{reservation}', [ReservationController::class, 'update'])->name('reservation.update');

