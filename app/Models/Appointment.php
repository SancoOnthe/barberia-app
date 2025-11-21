<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'appointments';

    protected $fillable = [
        'barber_id',
        'client_id',
        'service_id',
        'appointment_date', // Fecha y hora de inicio
        'end_time',         // Hora de fin (calculada automáticamente)
        'status',           // 'pending', 'confirmed', 'cancelled'
        'notes'
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'end_time' => 'datetime',
    ];

    // RELACIONES (Para poder decir $cita->barber->name)
    
    public function barber() {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function client() {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function service() {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
