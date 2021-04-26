<?php

use Illuminate\Database\Seeder;
use App\mdDaysOfWeek;

class DaysOfWeekTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        mdDaysOfWeek::create(   [
            'sequence'      => 1,
            'status'        => 'S',
            'day'           => 'Segunda',
            'language'      => 'PT-BR'
        ]);

        mdDaysOfWeek::create(   [
            'sequence'      => 2,
            'status'        => 'S',
            'day'           => 'Terça',
            'language'      => 'PT-BR'
        ]);

        mdDaysOfWeek::create(   [
            'sequence'      => 3,
            'status'        => 'S',
            'day'           => 'Quarta',
            'language'      => 'PT-BR'
        ]);

        mdDaysOfWeek::create(   [
            'sequence'      => 4,
            'status'        => 'S',
            'day'           => 'Quinta',
            'language'      => 'PT-BR'
        ]);

        mdDaysOfWeek::create(   [
            'sequence'      => 5,
            'status'        => 'S',
            'day'           => 'Sexta',
            'language'      => 'PT-BR'
        ]);

        mdDaysOfWeek::create(   [
            'sequence'      => 6,
            'status'        => 'S',
            'day'           => 'Sábado',
            'language'      => 'PT-BR'
        ]);

        mdDaysOfWeek::create(   [
            'sequence'      => 7,
            'status'        => 'S',
            'day'           => 'Domingo',
            'language'      => 'PT-BR'
        ]);
    }
}
