<?php

namespace App;

use App\models\Autor;
use App\models\Noticia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_super_user'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     *  Mutador para que el valor del password siempre implemente 
     *  la función de hash bcrypt
     */
    public function setPasswordAttribute($value) 
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Relacion para establecer tokens de acceso para el usuario
    */
    public function AauthAcessToken()
    {
        return $this->hasMany('\App\OauthAccessToken');
    }


    /**
     * Se establece la relacion uno a uno con las noticias
     */
    public function noticias()
    {
        return $this->hasMany(Noticia::class, 'id_usuario');
    }

}
