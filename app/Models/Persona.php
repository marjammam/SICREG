<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
    protected $primaryKey = 'idPersona';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidos',
        'ci',
        'tipoInstitucion',
        'distrito',
        'foto',
        'estado'
    ];
}
