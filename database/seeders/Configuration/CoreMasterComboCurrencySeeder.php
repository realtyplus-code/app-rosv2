<?php

namespace Database\Seeders\Configuration;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoreMasterComboCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'parent_id' => 1,
                'name' => 'currency',
                'code' => 'currency',
                'description' => '',
                'is_father' => true,
                'status' => true,
                'childrens' => [
                    [
                        'name' => 'EUR',
                        'valor' => 'Euro',
                        'orden' => 0,
                    ],
                    [
                        'name' => 'USD',
                        'valor' => 'Dolar',
                        'orden' => 1,
                    ],
                    [
                        'name' => 'GBP',
                        'valor' => 'Libra',
                        'orden' => 2,
                    ],
                    [
                        'name' => 'JPY',
                        'valor' => 'Yen',
                        'orden' => 3,
                    ],
                    [
                        'name' => 'COP',
                        'valor' => 'Peso Colombiano',
                        'orden' => 4,
                    ]
                ],
            ],
        ];

        foreach ($items as $key => $item) {
            $temp_children = null;
            if (isset($item['childrens'])) {
                $temp_children = $item['childrens'];
                unset($item['childrens']);
            }

            # Creamos el padre y vinculamos los hijos
            $father_id = DB::table('enum_options')->insertGetId($item);

            if (!empty($temp_children)) {
                foreach ($temp_children as $child) {
                    DB::table('enum_options')->insert([
                        'parent_id' => $father_id,
                        'name' => $child['name'],
                        'valor1' => $child['valor'],
                        'orden' => $child['orden'],
                        'status' => true
                    ]);
                }
            }
        }
    }
}
