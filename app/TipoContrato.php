<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoContrato extends Model
{
    protected $table = 'tipo_contratos';

    protected $fillable = [
        'nombre',
        'diasMinimo',
        'diasMaximo',
        'condicion'
    ];

    public function contratos()
    {
        return $this->hasMany(Contrato::class,'tipoContrato_id');
    }
}
