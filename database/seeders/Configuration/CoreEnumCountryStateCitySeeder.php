<?php

namespace Database\Seeders\Configuration;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoreEnumCountryStateCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemCountry = [
            'parent_id' => 1,
            'name' => 'country',
            'code' => 'country',
            'description' => '',
            'is_father' => true,
            'status' => true
        ];
        $itemSate = [
            'parent_id' => 1,
            'name' => 'state',
            'code' => 'state',
            'description' => '',
            'is_father' => true,
            'status' => true
        ];
        $itemCity = [
            'parent_id' => 1,
            'name' => 'city',
            'code' => 'city',
            'description' => '',
            'is_father' => true,
            'status' => true
        ];

        $countryId = DB::table('enum_options')->insertGetId([
            'parent_id' => $itemCountry['parent_id'],
            'name' => $itemCountry['name'],
            'code' => $itemCountry['code'],
            'description' => $itemCountry['description'],
            'is_father' => $itemCountry['is_father'],
            'status' => $itemCountry['status']
        ]);
        $stateId = DB::table('enum_options')->insertGetId([
            'parent_id' => $itemSate['parent_id'],
            'name' => $itemSate['name'],
            'code' => $itemSate['code'],
            'description' => $itemSate['description'],
            'is_father' => $itemSate['is_father'],
            'status' => $itemSate['status']
        ]);
        $cityId = DB::table('enum_options')->insertGetId([
            'parent_id' => $itemCity['parent_id'],
            'name' => $itemCity['name'],
            'code' => $itemCity['code'],
            'description' => $itemCity['description'],
            'is_father' => $itemCity['is_father'],
            'status' => $itemCity['status']
        ]);


        $listCitiesByState = [
            'Andalucía' => ['Sevilla', 'Málaga', 'Granada', 'Córdoba', 'Almería', 'Jaén', 'Cádiz', 'Huelva'],
            'Aragón' => ['Zaragoza', 'Huesca', 'Teruel', 'Calatayud', 'Barbastro', 'Ejea de los Caballeros'],
            'Asturias' => ['Oviedo', 'Gijón', 'Avilés', 'Mieres', 'Langreo', 'Llanes'],
            'Islas Baleares' => ['Palma de Mallorca', 'Ibiza', 'Menorca', 'Mahón', 'Alcudia', 'Manacor'],
            'Canarias' => ['Santa Cruz de Tenerife', 'Las Palmas de Gran Canaria', 'La Laguna', 'Arona', 'Puerto del Rosario', 'San Cristóbal de La Laguna'],
            'Cantabria' => ['Santander', 'Torrelavega', 'Camargo', 'Castro Urdiales', 'Laredo', 'Reinosa'],
            'Castilla y León' => ['Valladolid', 'León', 'Salamanca', 'Burgos', 'Zamora', 'Palencia', 'Segovia', 'Ávila'],
            'Castilla-La Mancha' => ['Toledo', 'Guadalajara', 'Albacete', 'Cuenca', 'Ciudad Real', 'Talavera de la Reina'],
            'Cataluña' => ['Barcelona', 'Tarragona', 'Girona', 'Lleida', 'Terrassa', 'Sabadell', 'Reus', 'Mataró'],
            'Extremadura' => ['Badajoz', 'Cáceres', 'Plasencia', 'Mérida', 'Don Benito', 'Almendralejo'],
            'Galicia' => ['Santiago de Compostela', 'Vigo', 'A Coruña', 'Ourense', 'Pontevedra', 'Lugo', 'Ferrol', 'Vilagarcía de Arousa'],
            'Madrid' => ['Madrid'],
            'Murcia' => ['Murcia', 'Cartagena', 'Lorca', 'Molina de Segura', 'San Javier', 'Caravaca de la Cruz'],
            'Navarra' => ['Pamplona', 'Tudela', 'Estella', 'Barañáin', 'Corella', 'Zizur Mayor'],
            'La Rioja' => ['Logroño', 'Haro', 'Calahorra', 'Arnedo', 'Alfaro', 'Nájera'],
            'País Vasco' => ['Bilbao', 'San Sebastián', 'Vitoria', 'Barakaldo', 'Getxo', 'Eibar', 'Durango'],
            'Valencia' => ['Valencia', 'Alicante', 'Castellón de la Plana', 'Elche', 'Sagunto', 'Gandía', 'Torrent', 'Vila-real'],
            'Ceuta' => ['Ceuta'],
            'Melilla' => ['Melilla']
        ];

        $brotherIdC = DB::table('enum_options')->insertGetId([
            'parent_id' => $countryId,
            'name' => 'Spain'
        ]);

        foreach ($listCitiesByState as $key => $value) {
            $brotherId = DB::table('enum_options')->insertGetId([
                'parent_id' => $stateId,
                'brother_relation_id' => $brotherIdC,
                'name' => $key
            ]);
            foreach ($value as $item) {
                DB::table('enum_options')->insert([
                    'parent_id' => $cityId,
                    'brother_relation_id' => $brotherId,
                    'name' => $item
                ]);
            }
        }
    }
}
