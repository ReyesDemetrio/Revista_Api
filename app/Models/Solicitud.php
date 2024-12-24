<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'solicitud';
    protected $primaryKey = 'Codigo';
    protected $fillable = [
        'Codigo',
        'CodigoRevista',
        'CodigoPersona',
        'Observacion',
        'Estado',
        'Vigente',
    ];
}
