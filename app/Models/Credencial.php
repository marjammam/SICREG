<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Persona; 


class Credencial extends Model
{
    protected $table = 'credencial';
    protected $primaryKey = 'idCredencial';
    public $timestamps = false;

    protected $fillable = [
        'Persona_idPersona',
        'carnet',
        'tipo_institucion',
        'cargo',
        'fechaEmision'
    ];

    // relación con persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Persona_idPersona', 'idPersona');
    }
}


