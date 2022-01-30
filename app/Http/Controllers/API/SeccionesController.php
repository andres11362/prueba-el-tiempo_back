<?php

namespace App\Http\Controllers\API;

use App\Helpers\HeaderTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\secciones\SeccionesRequest;
use App\models\Seccion;
use Illuminate\Support\Facades\DB;
use App\Helpers\StorageFiles;
use App\Http\Requests\secciones\SeccionesUpdateRequest;
use stdClass;

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

        $headers = new HeaderTables('secciones');

        $list_header = $headers->getTableColumns();

        return response()->json(['headers' => $list_header, 'secciones' => $secciones], 200);
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
                return response()->json(['estado' => 'OK', 'message' => '¡Sección guardada exitosamente!'], 200);
            } catch (\Exception $e) {
               DB::rollback();
               return response(['message' => $e->getMessage(), 500]);
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
     * Actualiza una sección en especifico
     * bajo el criterio del id
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SeccionesUpdateRequest $request, $id)
    {
        if (!$request->has('errors')) {
            try {
                $seccion = Seccion::findOrFail($id);
                $validated = $request->validated();
                $is_valid = $this->validateDataUpdateRequest($seccion, $validated, $request);
                if ($is_valid->band) {
                    $path = $is_valid->path;
                    $name = $is_valid->name;
                    $body = $this->getBodySecciones($name, $path);
                    $seccion->update($body);
                    DB::commit();
                    return response()->json(['seccion' => $seccion, 'message' => '¡Sección actualizada exitosamente!'], 200); 
                } else {
                    return response()->json(['message' => '¡Nada que actualizar!'], 404);
                }
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 500);
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
                return response()->json(['estado' => 'OK' , 'message' => '¡Sección eliminada exitosamente!'], 200);
            } else {
                return response()->json(['estado' => 'FAIL' , 'message' => '¡No se ha encontrado el archivo!'], 404);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * obtiene todos las secciones con el scope
     * de pluck (campos id y nombre)
     *
     * @return \Illuminate\Http\Response
     */
    public function pluckedData()
    {
        $secciones = Seccion::all();

        $plucked = $secciones->pluck('nombre', 'id');

        return $plucked->all();
    }

    /**
     * Validacion de existencia de campos para la 
     * actualizacion de archivo
     * @return Object
     */
    private function validateDataUpdateRequest($seccion, $validated, $request) 
    {
        $obj = new stdClass();

        $obj->band = false;
        $obj->path = null;
        $obj->name = null;

        if (($request->has('nombre')) || $request->hasFile('imagen')) {
            $file = $request->hasFile('imagen') ? $request->file('imagen') : null;
            $name = ($request->has('nombre') && ($validated['nombre'] !== $seccion->nombre)) 
                    ? $validated['nombre'] : null;
            $storage_files = new StorageFiles($seccion->imagen, $name,  $file);
            $path = $storage_files->updateFile();
            if($path) {
                $obj->band = true;
                $obj->path = $path;
                $obj->name = $name ? $name : $seccion->nombre;
            }
        } 

        return $obj;
    }

    /**
     * Funcion para armar el cuerpo de la peticion de la seccion
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
