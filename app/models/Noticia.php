<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'titulo', 'contenido', 'imagen',  'endpoint', 'id_seccion', 'id_autor' ];

    /**
     * Se define el nombre de la tabla en DB 
     * que representa el modelo
     * @var string
     */
    protected $table = 'noticias';

    /**
     * Relacion entre una noticia y un autor
     * Una noticia puede tener un solo autor
     */
    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    /**
     * Relacion entre una noticia y una seccion
     * Una noticia puede tener una sola seccion
     */
    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }

}
