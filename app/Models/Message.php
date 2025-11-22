<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'messages';

    protected $fillable = [
        'name',     // Nombre del remitente
        'email',    // Correo
        'subject',  // Asunto
        'body',     // El mensaje en sí
        'read',     // Estado: true (leído) / false (no leído)
    ];

    protected $casts = [
        'read' => 'boolean',
        'created_at' => 'datetime',
    ];
}
