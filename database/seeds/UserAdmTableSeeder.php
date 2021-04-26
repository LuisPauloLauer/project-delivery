<?php

use Illuminate\Database\Seeder;
use App\User;
use App\mdTpUsersAdm;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserAdmTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::truncate();

        $admintotal = mdTpUsersAdm::where('type', 'admintotal')->first();

        User::create(
            [
                'status'    => 'S',
                'tpuser'    => $admintotal->id,
                'name'      => 'Luís Paulo',
                'slug'      => Str::slug('Luís Paulo'),
                'cpf'       => '99999999999',
                'birth'     => '19990909',
                'sex'       => 'M',
                'fone'      => 999999999,
                'email'     => 'teste@teste.com',
                'password'  => Hash::make('sdflulu9494')
            ]
        );
    }
}