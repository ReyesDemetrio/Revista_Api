<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revista extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'revista';
    protected $primaryKey = 'Codigo';
    protected $fillable = [
        'Codigo',
        'CodigoPersona',
        'Titulo',
        'Paginas',
        'Vigente',
    ];
}
