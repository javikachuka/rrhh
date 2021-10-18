<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsabilidad extends Model
{
    protected $table = 'responsabilidades';
    protected $fillable = ['nombre', 'descripcion', 'empleado_id', 'condicion'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class,'empleado_id');
    }
}
