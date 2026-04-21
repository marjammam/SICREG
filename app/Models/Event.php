<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'evento';
    protected $primaryKey = 'idEvento';

    public $timestamps = false;

    protected $fillable = [
        'nombreE',
        'descripcionE',
        'fechaInicioE',
        'fechaFinE',
        'estadoE',
    ];

    public function subEvents()
    {
        return $this->hasMany(SubEvent::class, 'Evento_idEvento');
    }

    public function credenciales()
    {
        return $this->hasMany(Credencial::class, 'Evento_idEvento');
    }
}
