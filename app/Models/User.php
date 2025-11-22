<?php

namespace App\Models;

// Usamos la autenticación de MongoDB
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    // AQUÍ ESTABA EL ERROR: Ya quitamos "HasApiTokens"
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        // NUEVOS CAMPOS AGREGADOS:
        'cedula',
        'specialty',
        'experience',
        'bio',
        'activo',
        'notes' // <--- NUEVO CAMPO PARA CLIENTES
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean', // Esto asegura que se guarde como true/false real
    ];
}