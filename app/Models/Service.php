<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // <--- OJO: Usamos el Model de MongoDB
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'services';

    protected $fillable = [
        'name',         // Ej: Corte Fade
        'price',        // Ej: 15.00
        'duration_min', // Ej: 30 (minutos) - Vital para calcular la agenda
        'description',  // Ej: Incluye lavado
        'image', // <--- NUEVO CAMPO
        'activo',      // <--- NUEVO: Para activar/desactivar
    ];

    // AGREGA ESTO: Conversión automática de tipos
    protected $casts = [
        'activo' => 'boolean', // Cast automático
        'price' => 'decimal:2',
        'duration_min' => 'integer',
    ];
}
