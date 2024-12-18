<?php

namespace Database\Seeders\Configuration;

use Illuminate\Database\Seeder;
use App\Models\Economics\Opcion;
use App\Models\Configuration\EnumOption;

class CoreMastersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EnumOption::create(
            [ #Maestro ID: 1
                'parent_id' => 1, #1
                'name' => 'master padre',
                'description' => 'Maestro de Maestros'
            ]
        );
    }
}
