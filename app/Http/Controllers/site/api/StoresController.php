<?php

namespace App\Http\Controllers\site\api;

use App\Http\Controllers\Controller;
use App\Library\GeneralLibrary;
use App\mdStores;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    private $isStoreOpen = false;

    public function __construct()
    {
        $this->generalLibrary = new GeneralLibrary();
    }

    function __destruct() {
        unset($this->generalLibrary);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mdStores  $mdStores
     * @return \Illuminate\Http\Response
     */
    public function verifyStoreOpenToDelivery(mdStores $store)
    {
        if($store){
            $this->isStoreOpen = $this->generalLibrary->isStoreOpenToDelivery($store);
            $response['success'] = true;
            $response['message'] = 'OK';
            $response['isStoreOpen'] = $this->isStoreOpen;
        } else {
            $response['success'] = false;
            $response['message'] = 'Erro loja não existe!';
        }

        echo json_encode($response);
        return;
    }

}
