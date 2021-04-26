<?php

use Illuminate\Database\Seeder;
use App\mdTpUsersAdm;

class TpUserAdmTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        mdTpUsersAdm::create(   [
            'type' => 'admintotal',
            'name' => 'Administrador-total',
            'description' => 'Usuário administrador com controle total de acesso e dos cadastros'
        ]);

        mdTpUsersAdm::create(   [
            'type' => 'adminedit',
            'name' => 'Administrador-editor',
            'description' => 'Usuário administrador com controle dos cadastros bases'
        ]);

        mdTpUsersAdm::create(   [
            'type' => 'adminstore',
            'name' => 'Administrador-loja',
            'description' => 'Usuário administrador da loja com controle total dos cadastros fererentes a loja'
        ]);

        mdTpUsersAdm::create(   [
            'type' => 'editstore',
            'name' => 'Usuário-editor-loja',
            'description' => 'Usuário da loja com controle dos cadastros'
        ]);

        mdTpUsersAdm::create(   [
            'type' => 'readstore',
            'name' => 'Usuário-básico-loja',
            'description' => 'Usuário da loja com funções básicas normalmente apenas leitura'
        ]);
    }
}
