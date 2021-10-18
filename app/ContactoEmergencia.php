<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactoEmergencia extends Model
{
    protected $table = 'contactos_emergencias';
    protected $fillable = [
        'nombre',
        'telefono1',
        'telefono2',
        'correo',
        'empleado_id','condicion'
    ];
}
