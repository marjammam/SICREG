<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credencial extends Model
{
    protected $table = 'credencial';
    protected $primaryKey = 'idCredencial';

    public $timestamps = false;

    protected $fillable = [
        'tipoCredencial',
        'codigoQR',
        'fechaEmision',
        'Persona_idPersona',
        'Evento_idEvento',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Persona_idPersona', 'idPersona');
    }

    public function evento()
    {
        return $this->belongsTo(Event::class, 'Evento_idEvento', 'idEvento');
    }
}
