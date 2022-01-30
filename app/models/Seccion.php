<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [ 'nombre', 'imagen' ];

    /**
     * Se define el nombre de la tabla en DB 
     * que representa el modelo
     * @var string
     */
    protected $table = 'secciones';

    /**
     * Relacion entre autor y noticias
     * En este caso de uno a muchos
    */
    public function noticias()
    {
        return $this->hasMany(Noticia::class);
    }

}


