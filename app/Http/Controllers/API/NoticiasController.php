<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Noticias\NoticiasRequest;
use App\models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoticiasController extends Controller
{
    /**
     * Muestra todas las noticias
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noticias = Noticia::all();

        return response()->json($noticias, 200);
    }

    /**
     * Crea un nuevo recurso para guardar una noticia
     * en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoticiasRequest $request)
    {
        if (!$request->has('errors')) {
            DB::beginTransaction();
            try {
                Noticia::create($request->validated());
                DB::commit();
                return response()->json('¡Noticia guardada exitosamente!', 200);
            } catch (\Exception $e) {
                DB::rollback();
                return response(['message' => $e->getMessage()]);
            }
        } else {
            return response()->json($request->validated(), 422);
        }
    }

    /**
     * Muestra una noticia en especifico
     * segun el id seleccionado
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $noticia = Noticia::findOrFail($id);

        return response()->json($noticia, 200);
    }

  
    /**
     * Actualiza una noticia en especifico bajo el criterio
     * del id
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NoticiasRequest $request, $id)
    {
        if ($request->has('errors')) {
            DB::beginTransaction();
            try {
                $noticia = Noticia::find($id);
                $noticia->update($request->validated());
                DB::commit();
                return response()->json(['noticia' => $noticia, 'message' => '¡Noticia actualizada exitosamente!'], 200);
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
            $noticia = Noticia::findOrFail($id);
            $noticia->delete();
            DB::commit();
            return response()->json('¡Noticia eliminada exitosamente!', 200);
        }  catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()]);
        }
    }
}
