<?php

use Illuminate\Database\Seeder;
use App\mdStatusDemandsFood;

class StatusDemandsFoodSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        mdStatusDemandsFood::create(   [
            'type' => 'included',
            'name' => 'Pedido-incluído',
            'description' => 'Pedido incluído no sistema de pedidos'
        ]);

        mdStatusDemandsFood::create(   [
            'type' => 'confirmed',
            'name' => 'Pedido-atendido',
            'description' => 'Pedido confirmado pela loja e está sendo preparado'
        ]);

        mdStatusDemandsFood::create(   [
            'type' => 'togodelivery',
            'name' => 'Pedido-saiu-para-entrega',
            'description' => 'Pedido saiu para entrega'
        ]);

        mdStatusDemandsFood::create(   [
            'type' => 'delivered',
            'name' => 'Pedido-entregue',
            'description' => 'Pedido foi entregue'
        ]);

        mdStatusDemandsFood::create(   [
            'type' => 'canceled',
            'name' => 'Pedido-cancelado',
            'description' => 'Pedido foi cancelado'
        ]);
    }
}
