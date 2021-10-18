<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudInasistencia extends Model
{
    protected $table = 'solicitudes_inasistencias';
    protected $fillable = [
        'desde',
        'hasta',
        'motivo',
        'empleado_id',
        'incidencia_id',
        'aprobado',
        'condicion'
    ];
}
