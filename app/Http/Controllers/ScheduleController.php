<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WorkSchedule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Muestra el formulario de generación.
     */
    public function index()
    {
        // Traemos solo a los barberos activos
        $barbers = User::where('role', 'barber')->where('activo', true)->get();
        return view('admin.schedules.index', compact('barbers'));
    }

    /**
     * Genera los horarios masivamente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barber_id' => 'required',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'days' => 'required|array', // Array de días seleccionados (0=Dom, 1=Lun, etc.)
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $startDate = Carbon::parse($request->date_start);
        $endDate = Carbon::parse($request->date_end);
        $selectedDays = $request->days; // Ej: ['1', '3', '5'] (Lun, Mie, Vie)
        
        $count = 0;

        // Bucle día por día
        while ($startDate->lte($endDate)) {
            // Verificamos si el día de la semana actual está en la selección
            // Carbon: dayOfWeek devuelve 0 (Domingo) a 6 (Sábado)
            if (in_array($startDate->dayOfWeek, $selectedDays)) {
                
                // Usamos updateOrCreate para no duplicar si ya existe horario ese día
                WorkSchedule::updateOrCreate(
                    [
                        'barber_id' => $request->barber_id,
                        'date' => $startDate->format('Y-m-d'),
                    ],
                    [
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                        'is_active' => true,
                        // Puedes agregar lógica de descanso aquí si lo recibes del form
                    ]
                );
                $count++;
            }
            
            $startDate->addDay();
        }

        return back()->with('success', "Se generaron horarios para $count días correctamente.");
    }
}
