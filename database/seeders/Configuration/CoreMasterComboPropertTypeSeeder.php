<?php

namespace Database\Seeders\Configuration;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoreMasterComboPropertTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'parent_id' => 1,
                'name' => 'property type',
                'code' => 'property_type',
                'description' => '',
                'is_father' => true,
                'status' => true,
                'childrens' => [
                    [
                        'name' => 'department', //departamento
                        'orden' => 0,
                    ],
                    [
                        'name' => 'home', //casa
                        'orden' => 1,
                    ],
                    [
                        'name' => 'premises', //local
                        'orden' => 2,
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
                        'orden' => $child['orden'],
                        'status' => true
                    ]);
                }
            }
        }
    }
}
