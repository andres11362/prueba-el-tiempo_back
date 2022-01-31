<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
        $noticias = Noticia::getNoticias()->paginate(20);

        return response()->json($noticias, 200);
    }

    /**
     * Muestra una noticia en especifico
     * en el area publica
     * @return response
     */
    public function show ($id) 
    {
        $noticia = Noticia::getNoticia($id)->get();

        return response()->json($noticia, 200);
    }


    /**
     * Listar todas las noticias en el area
     * publica, dependiendo de la seccion
     * seleccionada
     * @return response
     */
    public function noticiasPorSeccion ($id) 
    {
        $noticias = Noticia::getNoticiasSeccion($id)->paginate(10);

        return response()->json($noticias, 200);
    }

    /**
     * Listar todas las noticias en el area
     * publica, dependiendo del usuario
     * seleccionado
     * @return response
     */
    public function noticiasPorUsuario ($id) 
    {
        $noticias = Noticia::getNoticiasUsuario($id)->paginate(2);

        return response()->json($noticias, 200);
    }

}
