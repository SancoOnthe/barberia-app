<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'work_schedules';

    protected $fillable = [
        'barber_id',
        'date',         // Fecha específica (Y-m-d)
        'start_time',   // Hora entrada (H:i)
        'end_time',     // Hora salida (H:i)
        'break_start',  // (Opcional) Inicio almuerzo
        'break_end',    // (Opcional) Fin almuerzo
        'is_active',    // Por si quieres cancelar un día específico
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }
}
