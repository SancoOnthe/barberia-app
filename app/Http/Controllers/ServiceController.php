<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    // Ver lista de servicios
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    // Formulario de crear
    public function create()
    {
        return view('admin.services.create');
    }

    // Guardar en la BD
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_min' => 'required|integer|min:5',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        
        // Manejo del Checkbox (Si no viene marcado, es false)
        $data['activo'] = $request->has('activo'); 

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($data);

        return back()->with('success', 'Servicio creado exitosamente.');
    }

    // 1. Mostrar formulario de edición
    public function edit($id)
    {
        $service = Service::find($id);
        return view('admin.services.edit', compact('service'));
    }

    // 2. Actualizar en base de datos
    public function update(Request $request, $id)
    {
        $service = Service::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_min' => 'required|integer|min:5',
        ]);

        $data = $request->all();
        $data['activo'] = $request->has('activo'); // Checkbox handling

        if ($request->hasFile('image')) {
            // Borrar vieja si existe
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return back()->with('success', 'Servicio actualizado.');
    }

    // 3. Eliminar servicio
    public function destroy($id)
    {
        $service = Service::find($id);

        // Borrar la imagen asociada si existe
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Servicio eliminado.');
    }
}
