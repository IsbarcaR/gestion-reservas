<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controladores
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ScheduleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTA PRINCIPAL INTELIGENTE ---
// Muestra la página de bienvenida en la ruta principal
Route::get('/', function () {
    return view('welcome');
});


// --- RUTAS PARA USUARIOS AUTENTICADOS ---
Route::middleware('auth')->group(function () {

    // RUTA DESPACHADORA
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('reservations.index');
    })->name('dashboard');


    // Rutas para la gestión del perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para que los clientes gestionen sus reservas
    Route::get('/mis-reservas', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservas/crear', [ReservationController::class, 'create'])->name('reservations.create'); // <-- LÍNEA CORREGIDA
    Route::post('/reservas', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservas/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    // Ruta de API interna para obtener la disponibilidad de horarios
    Route::get('/reservas/disponibilidad', [ReservationController::class, 'getAvailability'])->name('reservations.availability');
});


// --- RUTAS SOLO PARA ADMINISTRADORES ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard principal del administrador (Nombre completo: 'admin.dashboard')
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD para gestionar los servicios ofrecidos
    Route::resource('services', ServiceController::class);

    // Rutas para configurar los horarios de atención
    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('schedules', [ScheduleController::class, 'store'])->name('schedules.store');

    // Ruta para exportar los datos de las reservas
    Route::get('reservations/export', [AdminDashboardController::class, 'exportReservations'])->name('reservations.export');
});


// Incluye las rutas de autenticación (login, register, etc.) generadas por Laravel Breeze
require __DIR__.'/auth.php';