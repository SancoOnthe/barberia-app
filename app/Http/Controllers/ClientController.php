<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // 1. Dashboard del Cliente (Sus citas)
    public function index()
    {
        $myId = Auth::id();

        $appointments = Appointment::where('client_id', $myId)
                                   ->with(['barber', 'service'])
                                   ->orderBy('appointment_date', 'desc') // Las más nuevas primero
                                   ->get();

        return view('client.dashboard', compact('appointments'));
    }

    // 2. Formulario de Reserva
    public function create()
    {
        $barbers = User::where('role', 'barber')->get();
        $services = Service::all();

        return view('client.create', compact('barbers', 'services'));
    }

    // 3. Guardar la Reserva
    public function store(Request $request)
    {
        $request->validate([
            'barber_id' => 'required',
            'service_id' => 'required',
            'appointment_date' => 'required|date|after:now', // Solo fechas futuras
        ]);

        $service = Service::find($request->service_id);
        
        $startTime = Carbon::parse($request->appointment_date);
        $endTime = $startTime->copy()->addMinutes((int) $service->duration_min);

        // Validar disponibilidad (Básico)
        // Verificamos si el barbero ya tiene una cita que choque con este horario
        $conflict = Appointment::where('barber_id', $request->barber_id)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('appointment_date', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime]);
            })->first();

        if ($conflict) {
            return back()->withErrors(['appointment_date' => 'El barbero está ocupado a esa hora. Por favor elige otra.']);
        }

        Appointment::create([
            'barber_id' => $request->barber_id,
            'client_id' => Auth::id(), // <--- IMPORTANTE: El cliente es el usuario logueado
            'service_id' => $request->service_id,
            'appointment_date' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending', // Las citas de clientes nacen "Pendientes"
            'notes' => $request->notes
        ]);

        return redirect()->route('client.dashboard')->with('success', '¡Tu cita ha sido solicitada!');
    }
}
