<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Muestra la bandeja de entrada.
     */
    public function index()
    {
        // Traemos los mensajes ordenados por fecha (más nuevos primero)
        // y paginados de 10 en 10
        $messages = Message::orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Alternar estado Leído / No Leído (Toggle).
     */
    public function toggleRead($id)
    {
        $message = Message::findOrFail($id);
        
        // Invertimos el valor actual (si es true pasa a false, y viceversa)
        $message->update([
            'read' => !$message->read
        ]);

        $status = $message->read ? 'marcado como leído' : 'marcado como no leído';
        return back()->with('success', "Mensaje $status.");
    }

    /**
     * Eliminar mensaje.
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return back()->with('success', 'Mensaje eliminado correctamente.');
    }
}
