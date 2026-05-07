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

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'Persona_idPersona');
    }

    public function credenciales()
    {
        return $this->hasMany(Credencial::class, 'Persona_idPersona');
    }

    public function credencialPersonas()
    {
        return $this->hasMany(CredencialPersona::class, 'Persona_idPersona', 'idPersona');
    }
}
