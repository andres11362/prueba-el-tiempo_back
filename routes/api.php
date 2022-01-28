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
Route::post('reset', 'API\ForgotController@reset')->name('reset');

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
     * Rutas de los tipo de medicion
     * Se excluyen la creacion y la edicion al ser consumidas 
     * por medio de un API
     */
    Route::resource('type-medition', 'API\TypeMeditionController')->except([
        'create', 'edit'
    ]);
    
    /**
     * Rutas de los sensores
     * Se excluyen la creacion y la edicion al ser consumidas 
     * por medio de un API
     */
    Route::resource('sensor', 'API\SensorController')->except([
        'create', 'edit'
    ]);

    /**
     * Rutas especificas para las mediciones
     * 1. Ultima medicion de un sensor en especifico
     * 2. Data completa de un sensor en especifico (Caso PDF)
     * 3. Todas las mediciones.
     * 4. Buscar una medicion por tiempo.
     * 5. Creacion de una medicion (Solo usar para el sensor)
     */
    Route::get('sensor-medition-last/{id}', 'API\SensorController@lastMedition')->name('medition-last');
    Route::post('sensor-full-data', 'API\SensorController@sensorFullData')->name('sensor-full-data');
    Route::get('medition', 'API\MeditionController@all')->name('medition-all');
    Route::get('medition-sensor/{time}', 'API\MeditionController@show')->name('medition-sensor');
    Route::post('medition-create', 'API\MeditionController@store')->name('medition-store');
});
