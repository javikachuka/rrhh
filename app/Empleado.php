<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = ['nombre', 'apellido', 'cuil', 'estadoCivil', 'fechaNacimiento',
     'direccion','fechaAlta',
    'fechaBaja', 'curriculum', 'condicion','responsabilidades'];

    public function competenciasEmpleado()
    {
        return $this->hasMany('App\CompetenciaEmpleado');
    }
    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'empleado_id');
    }
    public function solicitudInasistencia()
    {
        return $this->hasMany(SolicitudInasistencia::class, 'solicitud_inasistencia_id');
    }
    
    public function personaDependientes()
    {
        return $this->hasMany(PersonaDependiente::class, 'empleado_id');
    }

    public function responsabilidades()
    {
        return $this->hasMany(Responsabilidad::class, 'empleado_id');
    }
    //no esta trabajando
    public function enLincencia()
    {
        $solicitud=   $this->solicitudInasistencia()->where('desde', '<=', Carbon::now())->where('hasta', '>=', Carbon::now())->first();
        if ($solicitud->id >0) {
            return true;
        }
        return false;
    }
}
