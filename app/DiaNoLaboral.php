<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiaNoLaboral extends Model
{
    protected $table = 'dias_no_laborales';
    protected $fillable = ['dia', 'descripcion', 'fijo', 'empresa_id','condicion'];
}
