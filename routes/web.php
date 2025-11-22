<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController; // <--- Importamos el controlador Admin
use App\Http\Controllers\ServiceController; // <--- Importamos ServiceController
use App\Http\Controllers\AppointmentController; // <-- nueva importación si no existe
use App\Http\Controllers\BarberController; // <--- No olvides importar esto arriba
use App\Http\Controllers\BarberManagementController; // <--- Agregamos el nuevo controlador para CRUD de barberos
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientManagementController; // <--- Importar arriba
use App\Http\Controllers\ConfigController; // <--- Importar arriba
use App\Http\Controllers\ScheduleController; // <--- Importar ScheduleController
use App\Http\Controllers\MessageController; // <--- Importar MessageController
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

    // CONFIGURACIÓN DEL SISTEMA
    Route::get('/admin/configuracion', [ConfigController::class, 'index'])->name('admin.config.index');
    Route::post('/admin/configuracion', [ConfigController::class, 'update'])->name('admin.config.update');

    // GESTIÓN DE HORARIOS
    Route::get('/admin/horarios', [ScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::post('/admin/horarios', [ScheduleController::class, 'store'])->name('admin.schedules.store');

    // GESTIÓN DE CLIENTES
    Route::get('/admin/clientes', [ClientManagementController::class, 'index'])->name('admin.clients.index');
    Route::post('/admin/clientes', [ClientManagementController::class, 'store'])->name('admin.clients.store');
    Route::put('/admin/clientes/{id}', [ClientManagementController::class, 'update'])->name('admin.clients.update');
    Route::delete('/admin/clientes/{id}', [ClientManagementController::class, 'destroy'])->name('admin.clients.destroy');
    
    // GESTIÓN DE MENSAJES (Bandeja de Entrada)
    Route::get('/admin/mensajes', [MessageController::class, 'index'])->name('admin.messages.index');
    // Ruta especial para cambiar el estado (usamos PATCH porque es una modificación parcial)
    Route::patch('/admin/mensajes/{id}/toggle', [MessageController::class, 'toggleRead'])->name('admin.messages.toggle');
    Route::delete('/admin/mensajes/{id}', [MessageController::class, 'destroy'])->name('admin.messages.destroy');
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

// RUTA PÚBLICA: Formulario de contacto en la web pública
Route::post('/contact', function (Illuminate\Http\Request $request) {
    // Validación básica
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'message' => 'required',
    ]);

    // Crear el mensaje en la base de datos
    \App\Models\Message::create([
        'name' => $request->name,
        'email' => $request->email,
        'subject' => 'Contacto Web', // Asunto por defecto
        'body' => $request->message,
        'read' => false,
    ]);

    return back()->with('status', '¡Mensaje enviado! Te contactaremos pronto.');
})->name('contact.send');
