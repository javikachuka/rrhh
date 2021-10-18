<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    //protected $table = 'empresas';

    protected $fillable = ['nombre', 'razonSocial', 'cuit', 'direccion', 'inicioActividad','condicion'];
}
