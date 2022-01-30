<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use stdClass;

/**
 * Clase para crear archivos
 */
class StorageFiles
{

    protected $folder;
    protected $nombre;
    protected $imagen;
    CONST PATH = 'public/';

    public function __construct($folder, $nombre = null, $imagen = null)
    {
        $this->folder = $folder;
        $this->nombre = $nombre;
        $this->imagen = $imagen;
    }

    /**
     * Función para crear archivos
     * @return string
     */
    public function createFile() 
    {
       if ($this->imagen) {
            $name = $this->createName();
            $file_path = self::PATH.$this->folder;
            $path = Storage::putFileAs($file_path, $this->imagen, $name);
            return $path;
       }
    }

    /**
     * Función para actualizar archivos
     */
    public function updateFile() 
    {
        $new_name = null;

        if ($this->imagen) {
            $is_deleted = $this->deleteFile();
            if ($is_deleted) {
                $obj_file = $this->getUpdateFileDates();
                $new_name = Storage::putFileAs($obj_file->path, $this->imagen, $obj_file->name);
            }
        }
        
        if($this->nombre) {
            $new_name = $this->replaceName();
            Storage::move($this->folder, $new_name);
        }

        return $new_name;
    }

    /**
     * Funcion para eliminar archivos
     * @return bool
     */
    public function deleteFile() 
    {
        $path = true;

        if (Storage::exists($this->folder)) 
        {
            Storage::delete($this->folder);
        } else {
            $path = false;
        }

        return $path;
    }

    /**
     * Función para crear el nombre del archivo
     */
    private function createName() {
        $name = $this->nombre.'.'.$this->imagen->extension();
        return $name;
    }

    /**
     * Función para crear el nombre del archivo
     */
    private function getUpdateFileDates() {
        $obj = new stdClass();
        $str_explode = explode('/', $this->folder);
        $obj->name = array_pop($str_explode);
        $obj->path = implode('/', $str_explode);
        return $obj;
    }


    /**
     * Función para reemplazar el nombre en la ruta de un archivo
     * 
     * @return String
    */
    private function replaceName() {
        $str_explode = explode('/', $this->folder);
        $file = end($str_explode);
        $file_explode = explode('.', $file);
        $file_explode[0] = $this->nombre;
        $new_name = $file_explode[0].'.'.$file_explode[1];
        $str_explode[array_key_last($str_explode)] = $new_name;
        $new_route = implode('/', $str_explode);

        return $new_route;
    }
}
