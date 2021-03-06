<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre', 'descripcion','condicion'];
    
    public function usuarios()
    {
        return $this->hasMany(User::class,'rol_id');
    }
}
