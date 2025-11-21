<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon; // Librería para manejar fechas

class AppointmentController extends Controller
{
    // Ver todas las citas (Como Admin)
    public function index()
    {
        // Traemos las citas con los datos de los usuarios relacionados
        $appointments = Appointment::with(['barber', 'client', 'service'])->get();
        return view('admin.appointments.index', compact('appointments'));
    }

    // Formulario para crear cita
    public function create()
    {
        // Necesitamos listas para los Selects del formulario
        $barbers = User::where('role', 'barber')->get();
        $clients = User::where('role', 'client')->orWhereNull('role')->get(); // Clientes
        $services = Service::all();

        return view('admin.appointments.create', compact('barbers', 'clients', 'services'));
    }

    // Guardar la cita
    public function store(Request $request)
    {
        $request->validate([
            'barber_id' => 'required',
            'client_id' => 'required', // Por ahora el admin elige al cliente
            'service_id' => 'required',
            'appointment_date' => 'required|date',
        ]);

        // 1. Buscamos el servicio para saber cuánto dura
        $service = Service::find($request->service_id);
        
        // 2. Calculamos la hora de fin
        $startTime = Carbon::parse($request->appointment_date);
        $endTime = $startTime->copy()->addMinutes($service->duration_min);

        // 3. (OPCIONAL) Aquí iría la validación de si el barbero ya está ocupado
        // Lo omitimos por ahora para no complicarlo.

        Appointment::create([
            'barber_id' => $request->barber_id,
            'client_id' => $request->client_id,
            'service_id' => $request->service_id,
            'appointment_date' => $startTime,
            'end_time' => $endTime,
            'status' => 'confirmed', // Como la crea el admin, nace confirmada
            'notes' => $request->notes
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Cita agendada.');
    }
}
