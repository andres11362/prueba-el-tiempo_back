<?php

namespace App\Http\Controllers\API;

use App\Helpers\HeaderTables;
use App\Helpers\StorageFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Noticias\NoticiasRequest;
use App\Http\Requests\noticias\NoticiasUpdateRequest;
use App\models\Noticia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class NoticiasController extends Controller
{
    /**
     * Muestra todas las noticias
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noticias = Noticia::paginate(10);

        $headers = new HeaderTables('noticias');

        $list_header = $headers->getTableColumns();

        return response()->json(['headers' => $list_header, 'noticias' => $noticias], 200);
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
                $validated = $request->validated();
                $storage_files = new StorageFiles('noticias/'.$validated['id_seccion'], $validated['titulo'], $request->file('imagen'));
                $path = $storage_files->createFile();
                $body = $this->getBodySecciones($validated, $path);
                Noticia::create($body);
                DB::commit();
                return response()->json(['estado' => 'OK', 'message' => '¡Noticia guardada exitosamente!'], 200);
            } catch (\Exception $e) {
                DB::rollback();
                return response(['message' => $e->getMessage()], 500);
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
    public function update(NoticiasUpdateRequest $request, $id)
    {
        if (!$request->has('errors')) {
            DB::beginTransaction();
            try {
                $noticia = Noticia::findOrFail($id);
                $validated = $request->validated();
                $is_valid = $this->validateDataUpdateRequest($noticia, $validated, $request);
                if ($is_valid->band) {
                    $path = $is_valid->path;
                    $title = $is_valid->title;
                    $body = $this->getBodySecciones($is_valid, $path, $title);
                    $noticia->update($body);
                    DB::commit();
                    return response()->json(['noticia' => $noticia, 'message' => '¡Noticia actualizada exitosamente!'], 200);
                }
            } catch (\Exception $e) {
                DB::rollback();
                return response(['message' => $e->getMessage()], 500);
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
            $storage_files = new StorageFiles($noticia->imagen);
            $path = $storage_files->deleteFile();
            if($path) {
                $noticia->delete();
                DB::commit();
                return response()->json(['estado' => 'OK' , 'message' => 'Noticia eliminada exitosamente!'], 200);
            } else {
                return response()->json(['estado' => 'FAIL' , 'message' => '¡No se ha encontrado el archivo!'], 404);
            }
        }  catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Validacion de existencia de campos para la 
     * actualizacion de archivo
     * @return Object
     */
    private function validateDataUpdateRequest($noticia, $validated, $request) 
    {
        $obj = new stdClass();

        $obj->band = false;
        $obj->path = null;
        $obj->title = null;
        $obj->contents = null;
        $obj->id_section = null;

        if (($request->has('nombre')) || $request->hasFile('imagen')) {
            $file = $request->hasFile('imagen') ? $request->file('imagen') : null;
            $title = ($request->has('titulo') && ($validated['titulo'] !== $noticia->titulo)) 
                    ? $validated['titulo'] : null;
            $contents = ($request->has('contenido') && ($validated['contenido'] !== $noticia->titulo)) 
                    ? $validated['contenido'] : null;
            $id_section = ($request->has('id_seccion') && ($validated['id_seccion'] !== $noticia->id_seccion)) 
                    ? $validated['id_seccion'] : null;
            $storage_files = new StorageFiles($noticia->imagen, $title,  $file);
            $path = $storage_files->updateFile();
            if($path) {
                $obj->band = true;
                $obj->path = $path;
                $obj->title = $title ? $title : $noticia->titulo;
                $obj->contents = $contents ? $contents : $noticia->contenido;
                $obj->id_section = $id_section ? $id_section : $noticia->id_seccion;
            }
        } 

        return $obj;
    }

    /**
     * Funcion para armar el cuerpo de la peticion de la noticia
     * al ser un archivo se le enviara la ruta y el nombre del mismo
     */
    private function getBodySecciones($data, $path)
    {
        if (is_array($data)) {
            $body = [
                'titulo' => $data['titulo'],
                'contenido' => $data['contenido'],
                'id_usuario' => Auth::user()->id,
                'id_seccion' => $data['id_seccion'],
                'imagen' => $path
            ];
        } else {
            $body = [
                'titulo' => $data->title,
                'contenido' => $data->contents,
                'id_usuario' => Auth::user()->id,
                'id_seccion' => $data->id_section,
                'imagen' => $path
            ];
        }
        
        return $body;
    } 
}
