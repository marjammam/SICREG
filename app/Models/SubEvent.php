<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubEvent extends Model
{
    protected $table = 'subevento';
    protected $primaryKey = 'idSubevento';

    public $timestamps = false;

    public function event() {
        return $this->belongsTo(Event::class, 'Evento_idEvento');
    }
}
