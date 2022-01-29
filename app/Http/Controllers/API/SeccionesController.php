<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\secciones\SeccionesRequest;
use App\models\Seccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\StorageFiles;
use App\Http\Requests\secciones\SeccionesUpdateRequest;

class SeccionesController extends Controller
{
    /**
     * Muestra toda la lista de secciones
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $secciones = Seccion::paginate(5);

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
        if (!$request->has('errors') && $request->hasFile('imagen')) {
            try {
                $validated = $request->validated();
                $storage_files = new StorageFiles('secciones', $validated['nombre'], $request->file('imagen'));
                $path = $storage_files->createFile();
                $body = $this->getBodySecciones($validated['nombre'], $path);
                Seccion::create($body);
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
        try {
            $seccion = Seccion::findOrFail($id);
            return response()->json($seccion, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }  
    }

    /**
     * Actualiza una sección en especifico }
     * bajo el criterio del id
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SeccionesUpdateRequest $request, $id)
    {
        if(!$request->has('errors')) 
        {
            DB::beginTransaction();

            try {
                $seccion = Seccion::find($id);
                $validated = $request->validated();
                $file = $request->hasFile('imagen') ?  $request->file('imagen') : null;
                $name = $validated['nombre'] !== $seccion-> nombre ? $validated['nombre'] : null;
                $storage_files = new StorageFiles('secciones', $name,  $file);
                $path = $storage_files->createFile();
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
            $storage_files = new StorageFiles($seccion->imagen);
            $path = $storage_files->deleteFile();
            if($path) {
                $seccion->delete();
                DB::commit();
                return response()->json('¡Sección eliminada exitosamente!', 200);
            } else {
                return response()->json('¡No se ha encontrado el archivo!', 404);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()]);
        }
    }

    /**
     * Funcion para armarel cuerpo de la peticion de la seccion
     * al ser un archivo se le enviara la ruta y el nombre del mismo
     */
    private function getBodySecciones($name, $path)
    {
        $body = [
            'nombre' => $name,
            'imagen' => $path
        ];

        return $body;
    } 
}
