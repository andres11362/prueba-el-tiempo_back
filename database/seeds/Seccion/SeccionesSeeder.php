<?php

use App\models\Seccion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionesSeeder extends Seeder
{
    /**
     * Sembrador de la tabla Secciones
     *
     * @return void
     */
    public function run()
    {
        DB::table('secciones')->delete();

        $secciones = [
            [
                'nombre' => 'Deportes',
                'imagen' => 'https://portavozdigital.com/wp-content/uploads/2021/04/GALA-DEL-DEPORTE-kjQF-U70481510887VpH-624x385@Ideal.jpg'
            ],
            [
                'nombre' => 'Política',
                'imagen' => 'https://www.caracteristicas.co/wp-content/uploads/2019/05/politica-1-e1589076411668.jpg'
            ],
            [
                'nombre' => 'Social',
                'imagen' => 'https://ladefinicion.com/wp-content/uploads/2019/04/grupo-social.jpg'
            ],
            [
                'nombre' => 'Internacional',
                'imagen' => 'https://maratum.com/wp-content/uploads/2019/04/Marketing-Internacional-1024x649.jpg'
            ],
            [
                'nombre' => 'Cultura',
                'imagen' => 'https://i0.wp.com/evemuseografia.com/wp-content/uploads/2020/08/EVE03082020-1.jpg'
            ],
            [
                'nombre' => 'Salud',
                'imagen' => 'https://concepto.de/wp-content/uploads/2013/08/salud-OMS-e1551914081412.jpg'
            ]
        ];

        Seccion::insert($secciones);
    }
}
