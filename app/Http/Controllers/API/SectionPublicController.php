<?php

namespace App\Http\Controllers\API;

use App\models\Noticia;
use Illuminate\Http\Request;

class SectionPublicController extends Controller
{
    /**
     * Listar todas las noticias en el area
     * publica
     * @return response
     */
    public function index () 
    {
        $noticias = Noticia::paginate(15);

        return response()->json($noticias, 200);
    }

    /**
     * Muestra una noticia en especifico
     * en el area publica
     * @return response
     */
    public function show ($id) 
    {
        $noticia = Noticia::findOrDie($id);

        return response()->json($noticia, 200);
    }


    /**
     * Listar todas las noticias en el area
     * publica, dependiendo de la seccion
     * seleccionada
     * @return response
     */
    public function noticiasPorSeccion (Request $request) 
    {
        $noticias = Noticia::getNoticiasSeccion($request->id)->get();

        return response()->json($noticias, 200);
    }

    /**
     * Listar todas las noticias en el area
     * publica, dependiendo del usuario
     * seleccionado
     * @return response
     */
    public function noticiasPorUsuario (Request $request) 
    {
        $noticias = Noticia::getNoticiasUsuario($request->id)->get();

        return response()->json($noticias, 200);
    }

}
