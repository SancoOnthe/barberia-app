<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'settings';

    protected $fillable = ['key', 'value'];
}
