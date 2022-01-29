<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
            $name = $this->getName();
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
        if ($this->nombre && $this->imagen) {
            $this->deleteFile();
            $this->createFile();
        } else if($this->nombre) {
            Storage::move()
        }
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
    private function getName() {
        $name = $this->nombre.'.'.$this->imagen->extension();
        return $name;
    }
}
