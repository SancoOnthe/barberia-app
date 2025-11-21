<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Importamos el modelo User
use Illuminate\Support\Facades\Hash; // <--- IMPORTANTE: Agrega esto

class AdminController extends Controller
{
    public function index()
    {
        // 1. Estadísticas Generales (Tarjetas)
        $stats = [
            'barberos' => User::where('role', 'barber')->where('activo', true)->count(),
            'servicios' => \App\Models\Service::where('activo', true)->count(),
            'clientes' => User::where('role', 'client')->count(),
            'citas_hoy' => \App\Models\Appointment::where('appointment_date', '>=', now()->startOfDay())
                                ->where('appointment_date', '<=', now()->endOfDay())
                                ->count(),
            // Citas para la próxima semana (desde mañana hasta 7 días)
            'proxima_semana' => \App\Models\Appointment::where('appointment_date', '>', now()->endOfDay())
                                ->where('appointment_date', '<=', now()->addDays(7)->endOfDay())
                                ->count(),
        ];

        // 2. Próximas 3 Citas (Lista)
        $upcomingAppointments = \App\Models\Appointment::where('appointment_date', '>=', now())
            ->orderBy('appointment_date', 'asc')
            ->take(3)
            ->with(['client', 'barber', 'service']) // Carga ansiosa para optimizar
            ->get();

        // 3. Datos para Gráficos (Analítica)
        // A. Gráfico de Barras: Últimos 7 días
        $sevenDaysAgo = now()->subDays(6)->startOfDay();
        $citasLast7Days = \App\Models\Appointment::where('appointment_date', '>=', $sevenDaysAgo)
            ->get()
            ->groupBy(function($date) {
                // Agrupamos por fecha YYYY-MM-DD
                return \Carbon\Carbon::parse($date->appointment_date)->format('Y-m-d'); 
            });

        $chartLabels = [];
        $chartData = [];
        
        // Rellenar días vacíos con 0
        for ($i = 0; $i < 7; $i++) {
            $date = now()->subDays(6 - $i)->format('Y-m-d');
            $displayDate = now()->subDays(6 - $i)->format('d/m');
            $chartLabels[] = $displayDate;
            $chartData[] = isset($citasLast7Days[$date]) ? $citasLast7Days[$date]->count() : 0;
        }

        // B. Gráfico de Torta: Estados (Últimos 7 días)
        $citasStatus = \App\Models\Appointment::where('appointment_date', '>=', $sevenDaysAgo)
            ->get()
            ->groupBy('status'); // Asumiendo que tu campo en BD se llama 'status'
        
        // Función auxiliar para obtener conteo seguro de varios keys
        $countFor = function($keys) use ($citasStatus) {
            $total = 0;
            foreach ((array) $keys as $k) {
                if (isset($citasStatus[$k])) {
                    $total += $citasStatus[$k]->count();
                }
            }
            return $total;
        };

        // Mapeo de colores y etiquetas para ChartJS
        $statusCounts = [
            'programada' => $countFor('programada'),
            'confirmada' => $countFor(['confirmed', 'confirmada']), // ajustar según valores reales
            'completada' => $countFor(['completed', 'completada']),
            'cancelada'  => $countFor(['cancelled', 'cancelada', 'cancelada_cliente']),
        ];

        // Enviamos todo a la vista
        return view('admin.index', compact('stats', 'upcomingAppointments', 'chartLabels', 'chartData', 'statusCounts'));
    }

    // 1. Mostrar el formulario
    public function create()
    {
        return view('admin.create_user');
    }

    // 2. Guardar los datos en MongoDB
    public function store(Request $request)
    {
        // Validamos que los datos sean correctos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        // Creamos el usuario con el rol de Barbero fijo
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'barber', // <--- Aquí forzamos el rol
            'specialty' => $request->specialty, // Opcional: Especialidad del barbero
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.panel')->with('success', 'Barbero registrado exitosamente.');
    }

    // 3. Mostrar formulario de edición
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.edit_user', compact('user'));
    }

    // 4. Guardar cambios
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email', // Nota: Validar unique en Mongo a veces requiere configuración extra
            'role' => 'required',
        ]);

        // Preparamos los datos a actualizar
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'specialty' => $request->specialty, // Solo si es barbero
        ];

        // LÓGICA DE CONTRASEÑA: Solo la actualizamos si el admin escribió algo nuevo
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.panel')->with('success', 'Usuario actualizado correctamente.');
    }

    // 5. Eliminar usuario
    public function destroy($id)
    {
        // Seguridad: No dejar que el admin se borre a sí mismo
        if ($id == \Illuminate\Support\Facades\Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta mientras estás conectado.');
        }

        $user = User::find($id);
        
        // Opcional: Aquí podrías borrar sus citas o reasignarlas, 
        // pero por ahora solo borramos al usuario.
        $user->delete();

        return redirect()->route('admin.panel')->with('success', 'Usuario eliminado del sistema.');
    }
}
