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
            "A Coruña" => ["A Coruña", "Ferrol", "Santiago de Compostela"],
            "Álava" => ["Vitoria-Gasteiz", "Llodio", "Amurrio"],
            "Albacete" => ["Albacete", "Hellín", "Villarrobledo"],
            "Alicante" => ["Alicante", "Elche", "Benidorm"],
            "Almería" => ["Almería", "Roquetas de Mar", "El Ejido"],
            "Asturias" => ["Oviedo", "Gijón", "Avilés"],
            "Ávila" => ["Ávila", "Arévalo", "El Tiemblo"],
            "Badajoz" => ["Badajoz", "Mérida", "Don Benito"],
            "Barcelona" => ["Barcelona", "Hospitalet de Llobregat", "Badalona"],
            "Burgos" => ["Burgos", "Miranda de Ebro", "Aranda de Duero"],
            "Cáceres" => ["Cáceres", "Plasencia", "Trujillo"],
            "Cádiz" => ["Cádiz", "Jerez de la Frontera", "Algeciras"],
            "Cantabria" => ["Santander", "Torrelavega", "Castro-Urdiales"],
            "Castellón" => ["Castellón de la Plana", "Vila-real", "Benicàssim"],
            "Ciudad Real" => ["Ciudad Real", "Puertollano", "Tomelloso"],
            "Córdoba" => ["Córdoba", "Lucena", "Montilla"],
            "Cuenca" => ["Cuenca", "Tarancón", "Motilla del Palancar"],
            "Girona" => ["Girona", "Figueres", "Blanes"],
            "Granada" => ["Granada", "Motril", "Baza"],
            "Guadalajara" => ["Guadalajara", "Azuqueca de Henares", "Cabanillas del Campo"],
            "Guipúzcoa" => ["San Sebastián", "Irún", "Eibar"],
            "Huelva" => ["Huelva", "Lepe", "Isla Cristina"],
            "Huesca" => ["Huesca", "Jaca", "Monzón"],
            "Islas Baleares" => ["Palma de Mallorca", "Ibiza", "Manacor"],
            "Jaén" => ["Jaén", "Linares", "Úbeda"],
            "La Rioja" => ["Logroño", "Calahorra", "Haro"],
            "Las Palmas" => ["Las Palmas de Gran Canaria", "Telde", "Arrecife"],
            "León" => ["León", "Ponferrada", "San Andrés del Rabanedo"],
            "Lleida" => ["Lleida", "Balaguer", "Tàrrega"],
            "Lugo" => ["Lugo", "Monforte de Lemos", "Vilalba"],
            "Madrid" => ["Madrid", "Alcalá de Henares", "Móstoles"],
            "Málaga" => ["Málaga", "Marbella", "Vélez-Málaga"],
            "Murcia" => ["Murcia", "Cartagena", "Lorca"],
            "Navarra" => ["Pamplona", "Tudela", "Estella"],
            "Ourense" => ["Ourense", "O Barco de Valdeorras", "Verín"],
            "Palencia" => ["Palencia", "Aguilar de Campoo", "Guardo"],
            "Pontevedra" => ["Vigo", "Pontevedra", "Vilagarcía de Arousa"],
            "Salamanca" => ["Salamanca", "Béjar", "Ciudad Rodrigo"],
            "Santa Cruz de Tenerife" => ["Santa Cruz de Tenerife", "San Cristóbal de La Laguna", "Arona"],
            "Segovia" => ["Segovia", "Cuéllar", "El Espinar"],
            "Sevilla" => ["Sevilla", "Dos Hermanas", "Alcalá de Guadaíra"],
            "Soria" => ["Soria", "Almazán", "El Burgo de Osma"],
            "Tarragona" => ["Tarragona", "Reus", "Tortosa"],
            "Teruel" => ["Teruel", "Alcañiz", "Andorra"],
            "Toledo" => ["Toledo", "Talavera de la Reina", "Illescas"],
            "Valencia" => ["Valencia", "Gandía", "Torrent"],
            "Valladolid" => ["Valladolid", "Medina del Campo", "Laguna de Duero"],
            "Vizcaya" => ["Bilbao", "Barakaldo", "Getxo"],
            "Zamora" => ["Zamora", "Benavente", "Toro"],
            "Zaragoza" => ["Zaragoza", "Calatayud", "Utebo"],
        ];

        $brotherIdC = DB::table('enum_options')->insertGetId([
            'parent_id' => $countryId,
            'name' => 'Spain',
            'valor1' => 'España'
        ]);

        foreach ($listCitiesByState as $key => $value) {
            $brotherId = DB::table('enum_options')->insertGetId([
                'parent_id' => $stateId,
                'brother_relation_id' => $brotherIdC,
                'name' => $key,
                'valor1' => $key,
            ]);
            foreach ($value as $item) {
                DB::table('enum_options')->insert([
                    'parent_id' => $cityId,
                    'brother_relation_id' => $brotherId,
                    'name' => $item,
                    'valor1' => $item,
                ]);
            }
        }
    }
}
