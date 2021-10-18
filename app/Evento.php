<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = ['titulo', 'descripcion', 'fecha', 'fechaFin','departamento_id', 'puesto_id','condicion'];
}
