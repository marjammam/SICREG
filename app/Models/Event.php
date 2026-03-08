<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'evento';
    protected $primaryKey = 'idEvento';

    public $timestamps = false;

    public function subEvents()
    {
        return $this->hasMany(SubEvent::class, 'Evento_idEvento');
    }
}
