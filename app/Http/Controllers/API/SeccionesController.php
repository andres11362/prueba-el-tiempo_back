<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\secciones\SeccionesRequest;
use App\models\Seccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeccionesController extends Controller
{
    /**
     * Muestra toda la lista de secciones
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $secciones = Seccion::all();

        return response()->json(['secciones' => $secciones], 200);
    }


    /**
     * Crea un nuevo recurso para guardar una seccion
     * en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeccionesRequest $request)
    {
        DB::beginTransaction();

        if (!$request->has('errors')) {
            try {
                Seccion::create($request->validated());
                DB::commit();
                return response()->json('¡Sección guardada exitosamente!', 200);
            } catch (\Exception $e) {
               DB::rollback();
               return response(['message' => $e->getMessage()]);
            }
        } else {
            return response()->json($request->validated(), 422);
        }
    }

    /**
     * Muestra una seccion en especifico
     * segun el id seleccionado
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sensor = Seccion::findOrFail($id);

        return response()->json($sensor, 200);
    }

    /**
     * Actualiza una sección en especifico }
     * bajo el criterio del id
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SeccionesRequest $request, $id)
    {
        if(!$request->has('errors')) 
        {
            DB::beginTransaction();

            try {
                $seccion = Seccion::find($id);
                $seccion->update($request->validated());
                DB::commit();
                return response()->json(['seccion' => $seccion, 'message' => '¡Sección actualizada exitosamente!'], 200);
            } catch (\Exception $e) {
                DB::rollback();
                return response(['message' => $e->getMessage()]);
            }
        } else {
            return response()->json($request->validated(), 422);
        }
    }

    /**
    * Remueve una noticia seleccionada por el id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $seccion = Seccion::findOrFail($id);
            $seccion->delete();
            DB::commit();
            return response()->json('¡Sección eliminada exitosamente!', 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()]);
        }
    }
}
