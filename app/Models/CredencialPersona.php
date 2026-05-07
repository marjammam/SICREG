<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CredencialPersona extends Model
{
    protected $table = 'credencial_personas';
    protected $primaryKey = 'idCredencialPersona';
    
    public $timestamps = false;

    protected $fillable = [
        'codigoQR',
        'fechaEmision',
        'Persona_idPersona'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Persona_idPersona', 'idPersona');
    }
}
