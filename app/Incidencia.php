<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $fillable = ['nombre', 'diasMaximo', 'diasMinimo','condicion'];
}
