<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicados extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'publicados';
    protected $primaryKey = 'Codigo';
    protected $fillable = [
        'Codigo',
        'CodigoSolicitud',
        'Estado',
        'Vigente',
    ];
}
