<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- TE FALTABA ESTA LÍNEA
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;
    protected $table = 'registroasistencia';
    protected $primaryKey = 'idregistroAsistencia';
    // Si tu llave primaria no es un BIGINT autoincremental,
    // pero como en la migración pusimos $table->id(), esto está bien.
    protected $fillable = [
        'codigoQRleido',
        'fechahoraIngreso',
        'fechahoraSalida',
        'estadoR',
        'Subevento_idSubevento',
        'Persona_idPersona',
        'Usuario_idUsuario'
    ];
    /**
     * Casting de atributos.
     * Esto es muy útil para que Laravel trate estos campos como objetos Carbon (fechas)
     * y puedas formatearlos fácilmente en la vista.
     */
    protected $casts = [
        'fechahoraIngreso' => 'datetime',
        'fechahoraSalida' => 'datetime',
    ];
    // Relación con Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Persona_idPersona', 'idPersona');
    }
    // Te sugiero añadir también la relación con Subevento por si la necesitas
    public function subevento()
    {
        return $this->belongsTo(SubEvent::class, 'Subevento_idSubevento', 'idSubevento');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'Usuario_idUsuario', 'idUsuario');
    }
}
