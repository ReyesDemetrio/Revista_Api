<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'persona';
    protected $primaryKey = 'Codigo';
    protected $fillable = [
        'Codigo',
        'Nombre',
        'Apellido',
        'DNI',
        'Telefono',
        'Correo',
        'Vigente',
    ];

}