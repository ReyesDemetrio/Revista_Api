<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'tipodocumento';
    protected $primaryKey = 'Codigo';
    protected $fillable = [
        'Nombre',
        'Siglas',
        'Vigente',
    ];
}
