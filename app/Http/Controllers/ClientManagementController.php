<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ClientManagementController extends Controller
{
    public function index()
    {
        // Traemos solo a los clientes
        $clients = User::where('role', 'client')->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'activo' => $request->has('activo'),
            // Datos específicos de cliente (MongoDB flexible)
            'notes' => $request->notes, 
        ]);

        return back()->with('success', 'Cliente registrado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $client = User::findOrFail($id);

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
            'notes' => $request->notes,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $client->update($data);

        return back()->with('success', 'Información del cliente actualizada.');
    }

    public function destroy($id)
    {
        $client = User::findOrFail($id);
        $client->delete();
        return back()->with('success', 'Cliente eliminado.');
    }
}
