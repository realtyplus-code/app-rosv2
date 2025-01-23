<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(Rols\RolesAndPermissionsSeeder::class);
        $this->call(Users\UsersSeeder::class);

        //Combos
        $this->call(Configuration\CoreMastersTableSeeder::class);
        $this->call(Configuration\CoreMasterComboIncidentSeeder::class);
        $this->call(Configuration\CoreMasterComboIncidentTypeSeeder::class);
        $this->call(Configuration\CoreMasterComboPayerSeeder::class);
        $this->call(Configuration\CoreMasterComboPrioritySeeder::class);
        $this->call(Configuration\CoreMasterComboPropertyTypeSeeder::class);
        $this->call(Configuration\CoreMasterComboCoverageTypeSeeder::class);
        $this->call(Configuration\CoreMasterComboProviderTypeSeeder::class);
        $this->call(Configuration\CoreEnumCountryStateCitySeeder::class);
        $this->call(Configuration\CoreMasterComboInsuranceTypeSeeder::class);
        $this->call(Configuration\CoreMasterComboLenguajeSeeder::class);
        $this->call(Configuration\CoreMasterComboCurrencySeeder::class);
    }
}
