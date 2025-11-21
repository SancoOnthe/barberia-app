<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController; // <--- Importamos el controlador Admin
use App\Http\Controllers\ServiceController; // <--- Importamos ServiceController
use App\Http\Controllers\AppointmentController; // <-- nueva importación si no existe
use App\Http\Controllers\BarberController; // <--- No olvides importar esto arriba
use App\Http\Controllers\BarberManagementController; // <--- Agregamos el nuevo controlador para CRUD de barberos
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use App\Models\Service; // <--- Import importante para traer los servicios
use Illuminate\Support\Facades\Auth; // <-- agrega esta importación si no existe

Route::get('/', function () {
    // Traemos todos los servicios
    $services = Service::all();

    // Se los enviamos a la vista 'welcome'
    return view('welcome', compact('services'));
});

// Ruta inteligente: Si entran a /dashboard, los manda a donde pertenecen
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.panel');
    } elseif ($user->role === 'barber') {
        return redirect()->route('barber.agenda');
    } else {
        // Si es cliente o no tiene rol, va a su panel de cliente
        return redirect()->route('client.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// GRUPO DE RUTAS PARA EL ADMINISTRADOR
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Ver lista (Dashboard)
    Route::get('/admin/panel', [AdminController::class, 'index'])->name('admin.panel');
    
    // GESTIÓN DE BARBEROS (CRUD COMPLETO)
    Route::get('/admin/barberos', [BarberManagementController::class, 'index'])->name('admin.barbers.index');
    Route::post('/admin/barberos', [BarberManagementController::class, 'store'])->name('admin.barbers.store');
    Route::put('/admin/barberos/{id}', [BarberManagementController::class, 'update'])->name('admin.barbers.update');
    Route::delete('/admin/barberos/{id}', [BarberManagementController::class, 'destroy'])->name('admin.barbers.destroy');
    
    // GESTIÓN DE SERVICIOS
    Route::get('/admin/services', [ServiceController::class, 'index'])->name('admin.services.index');
    Route::get('/admin/services/create', [ServiceController::class, 'create'])->name('admin.services.create');
    Route::post('/admin/services', [ServiceController::class, 'store'])->name('admin.services.store');

    // NUEVAS RUTAS (Editar y Eliminar)
    Route::get('/admin/services/{id}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit');
    Route::put('/admin/services/{id}', [ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/admin/services/{id}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');

    // GESTIÓN DE CITAS
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('admin.appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('admin.appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('admin.appointments.store');
});

// GRUPO DE RUTAS PARA BARBEROS
Route::middleware(['auth', 'role:barber'])->group(function () {
    Route::get('/barber/agenda', [BarberController::class, 'index'])->name('barber.agenda');

    // NUEVA RUTA PARA CAMBIAR ESTADO:
    Route::patch('/barber/appointments/{id}', [BarberController::class, 'updateStatus'])->name('barber.appointments.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// GRUPO DE RUTAS PARA CLIENTES
Route::middleware(['auth'])->group(function () {
    Route::get('/client/dashboard', [ClientController::class, 'index'])->name('client.dashboard'); // <--- ESTA ES LA QUE FALTA
    Route::get('/client/book', [ClientController::class, 'create'])->name('client.appointments.create');
    Route::post('/client/book', [ClientController::class, 'store'])->name('client.appointments.store');
});

require __DIR__.'/auth.php';
