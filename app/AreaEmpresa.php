<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AreaEmpresa extends Model
{
    protected $table = 'areas_empresas';
    protected $fillable = [
        'area_id','condicion'
    ];

   
}
