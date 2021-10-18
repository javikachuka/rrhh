<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetenciaEmpleado extends Model
{
    protected $table = 'competencias_empleados';
    protected $fillable = [
        'empleado_id',
        'competencia_id','condicion'
    ];
}
