<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    public function index()
    {
        // 1. Traer todas las configuraciones y convertirlas en un array simple [key => value]
        $settings = Setting::all()->pluck('value', 'key');

        // 2. Calcular estadísticas para el panel lateral (Igual que en tu HTML)
        $dbStats = [
            'usuarios' => User::count(),
            'servicios' => Service::count(),
            'citas' => Appointment::count(),
            'mongo_version' => '...connected...', // Placeholder o lógica real si quieres
        ];

        // Intentar verificar conexión real
        try {
            DB::connection('mongodb')->getMongoClient()->listDatabases();
            $dbStats['status'] = 'Conectada';
            $dbStats['status_color'] = 'success';
        } catch (\Exception $e) {
            $dbStats['status'] = 'Error';
            $dbStats['status_color'] = 'danger';
        }

        return view('admin.config.index', compact('settings', 'dbStats'));
    }

    public function update(Request $request)
    {
        // Guardamos todo lo que venga en el formulario, excepto el token CSRF
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            // Si el valor es nulo, guardamos string vacío
            $valToStore = is_null($value) ? '' : $value;

            Setting::updateOrCreate(
                ['key' => $key], // Busca por clave
                ['value' => $valToStore] // Actualiza el valor
            );
        }

        return back()->with('success', 'Configuración guardada correctamente.');
    }
}
