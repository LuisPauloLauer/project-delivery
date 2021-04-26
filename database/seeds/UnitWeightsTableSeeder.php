<?php

use Illuminate\Database\Seeder;
use App\mdUnitWeights;

class UnitWeightsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        mdUnitWeights::create(   [
            'unit_weight' => 'g',
            'name' => 'grama'
        ]);

        mdUnitWeights::create(   [
            'unit_weight' => 'Kg',
            'name' => 'quilos'
        ]);
    }
}
