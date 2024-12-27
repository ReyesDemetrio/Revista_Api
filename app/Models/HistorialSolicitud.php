<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialSolicitud extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'historialsolicitud';
    protected $primaryKey = 'Codigo';
    protected $fillable = [
        'CodigoSolicitud',
        'CodigoEstado',
        'Fecha',
        'Observacion',
        'Vigente',
    ];
}
