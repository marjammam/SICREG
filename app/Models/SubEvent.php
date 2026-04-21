<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubEvent extends Model
{
    protected $table = 'subevento';
    protected $primaryKey = 'idSubevento';

    public $timestamps = false;

    protected $fillable = [
        'nombreSE',
        'tipoEvento',
        'fechaSE',
        'horaInicio',
        'horaFin',
        'estadoSE',
        'Evento_idEvento',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'Evento_idEvento', 'idEvento');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'Subevento_idSubevent');
    }
}
