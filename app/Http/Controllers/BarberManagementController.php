<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BarberManagementController extends Controller
{
    /**
     * Muestra la lista de barberos.
     */
    public function index()
    {
        // Traemos solo a los usuarios con rol 'barber'
        $barbers = User::where('role', 'barber')->get();
        return view('admin.barbers.index', compact('barbers'));
    }

    /**
     * Guarda un nuevo barbero.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string',
            'password' => 'required|string|min:8',
            'specialty' => 'nullable|string',
            'experience' => 'nullable|numeric',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'barber',
            'activo' => $request->has('activo'),
            'specialty' => $request->specialty,
            'experience' => $request->experience,
            'bio' => $request->bio,
        ]);

        return back()->with('success', 'Barbero registrado exitosamente.');
    }

    /**
     * Actualiza un barbero existente.
     */
    public function update(Request $request, $id)
    {
        $barber = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'activo' => $request->has('activo'),
            'specialty' => $request->specialty,
            'experience' => $request->experience,
            'bio' => $request->bio,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $barber->update($data);

        return back()->with('success', 'Información del barbero actualizada.');
    }

    /**
     * Elimina un barbero.
     */
    public function destroy($id)
    {
        $barber = User::findOrFail($id);
        $barber->delete();

        return back()->with('success', 'Barbero eliminado del sistema.');
    }
}
