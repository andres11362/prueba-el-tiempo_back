<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Schema;

/**
 * Helper para saber las cabeceras de una tabla 
 * de base de datos
 */
class HeaderTables {

    protected $table = '';

    //Lista de campos prohibidos para exponer en las peticiones
    protected $black_list = [
        'id',
        'remember_token', 
        'created_at', 
        'updated_at', 
        'password',
        'id_usuario',
        'id_seccion',
    ];

    /**
     * Constructor de la clase
     */
    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * Funcion que obtiene las cabeceras de una tabla seleccionada
     * Se remueven por motivos de seguridad algunas cabeceras
     * @return Array
     */
    public function getTableColumns()
    {
        $temp_headers = Schema::getColumnListing($this->table);
;
        $filter = array_diff($temp_headers, $this->black_list);

        array_push($filter, 'acciones');

        return $filter;
    }
    
}