<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'id_usuario' ];

    /**
     * Se define el nombre de la tabla en DB 
     * que representa el modelo
     * @var string
     */
    protected $table = 'autores';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacion entre autor y noticias
     * En este caso de uno a muchos
    */
    public function noticias()
    {
        return $this->hasMany(Noticia::class);
    }
}
