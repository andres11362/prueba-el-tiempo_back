<?php

namespace App\models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Noticia extends Model
{
    /**
     * Los atributos definidos para ser manipulados en masa
     *
     * @var array
     */
    protected $fillable = [ 'titulo', 'contenido', 'imagen',  'endpoint', 'id_seccion', 'id_usuario' ];

    /**
     * Se define el nombre de la tabla en DB 
     * que representa el modelo
     * @var string
     */
    protected $table = 'noticias';

    /**
     * Relacion entre una noticia y un usuario
     * Una noticia puede tener un solo usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacion entre una noticia y una seccion
     * Una noticia puede tener una sola seccion
     */
    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }
    
    /**
     * Obtenemos las noticias por autor
     * @return mixed
    */
    public function scopeGetNoticiasUsuario($query, $id)
    {
        $noticias = $query->where('id_usuario', $id);
        $noticias->with('usuario');

        return $noticias;
    }

    /**
     * Obtenemos las noticias por seccion
     * @return mixed
    */
    public function scopeGetNoticiasSeccion($query, $id)
    {
        $noticias = $query->where('id_usuario', $id);
        $noticias->with('seccion');

        return $noticias;
    }

}
