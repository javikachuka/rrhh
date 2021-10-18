<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonaDependiente extends Model
{
    protected $table='persona_dependientes';

    protected $fillable = ['nombre', 'apellido', 'dni','empleado_id','relacion','condicion'];

    public function tutor()
    {
        return $this->belongsTo(Empleado::class,'empleado_id');
    }
    


}
