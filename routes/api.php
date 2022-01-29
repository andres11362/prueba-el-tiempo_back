<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Rutas no protegidas
 * Las siguientes rutaspodran ser accedidas por cualquier persona.
 * 1. Registro en el sistema.
 * 2. Autenticacion en el sistema
 * 3. Olvido de contraseña.
 * 4. Reset de contraseña.
 */
Route::post('register', 'API\AuthController@register')->name('register');
Route::post('login', 'API\AuthController@login')->name('login');
Route::post('forgot', 'API\ForgotController@forgot')->name('forgot');
Route::put('reset', 'API\ForgotController@reset')->name('reset');

/**
 * Rutas no protegidas noticias
 * 1. Lista de todas las noticias
 * 2. Lista de noticias por sección
 * 3. Lista de rutas por autor
 * 4. Noticia por id
 */
Route::get('noticias/lista', 'API\SectionPublicController@index')->name('public-notices');
Route::get('noticias/seccion/{id}', 'API\SectionPublicController@noticiasPorSeccion')->name('notices-section');
Route::get('noticias/autor/{autor}', 'API\SectionPublicController@noticiasPorUsuario')->name('notices-author');
Route::get('noticia/{id}', 'API\SectionPublicController@show')->name('notices-show');

/**
 * Rutas protegidas
 * Las siguientes rutas no podran ser accedidas 
 * a menos de que el usuario este registrado y autenticado en
 * el sistema
 */
Route::middleware(['auth:api'])->group(function () {
    /**
     * Rutas de la administracion de usuarios
     * 1. Mostrar todos los usuarios.
     * 2. Un usuario en especifico (Para el caso del logueo en el front)
     * 3. Primer acceso a la plataforma.
     * 4. Editar usuario.
     * 5. Salida de la plataforma
     */
    Route::get('users', 'API\UserController@index')->name('user-all');
    Route::get('user', 'API\AuthController@user')->name('user');
    Route::post('first-access-user', 'API\FirstAccessUserController@firstAccess')->name('first-access-user');
    Route::put('edit-user', 'API\UserController@update')->name('edit-user');
    Route::post('logout','API\AuthController@logout')->name('logout');

    /**
     * Rutas de las secciones
     * Se excluyen la creacion y la edicion al ser consumidas 
     * por medio de un API
     */
    Route::resource('secciones', 'API\SeccionesController')->except([
        'create', 'edit'
    ]);
    
    /**
     * Rutas de noticias
     * Se excluyen la creacion y la edicion al ser consumidas 
     * por medio de un API
     */
    Route::resource('noticias', 'API\NoticiasController')->except([
        'create', 'edit'
    ]);

});
