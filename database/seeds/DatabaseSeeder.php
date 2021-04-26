<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $this->call(TpUserAdmTableSeed::class);
        $this->call(UserAdmTableSeeder::class);
        $this->call(StatusDemandsFoodSeed::class);
        $this->call(UnitWeightsTableSeeder::class);
        $this->call(DaysOfWeekTableSeeder::class);
    }
}
