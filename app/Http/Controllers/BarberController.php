<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth; // Necesario para saber quién está logueado

class BarberController extends Controller
{
    public function index()
    {
        // 1. Obtenemos el ID del barbero que inició sesión
        $myId = Auth::id();

        // 2. Buscamos SOLO sus citas, ordenadas por fecha
        $appointments = Appointment::where('barber_id', $myId)
                                   ->where('appointment_date', '>=', now()->startOfDay()) // Solo futuras o de hoy
                                   ->with(['client', 'service']) // Traemos datos del cliente y servicio
                                   ->orderBy('appointment_date', 'asc')
                                   ->get();

        return view('barber.agenda', compact('appointments'));
    }

    // Función para cambiar el estado (Completar o Cancelar)
    public function updateStatus(Request $request, $id)
    {
        // 1. Buscamos la cita
        $appointment = Appointment::find($id);

        // 2. SEGURIDAD: Verificamos que la cita sea de este barbero
        if ($appointment->barber_id !== Auth::id()) {
            abort(403, 'No tienes permiso para modificar esta cita.');
        }

        // 3. Actualizamos el estado
        // Validamos que el estado sea uno válido
        $request->validate([
            'status' => 'required|in:completed,cancelled'
        ]);

        $appointment->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'El estado de la cita se actualizó correctamente.');
    }
}
