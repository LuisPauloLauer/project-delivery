<?php


namespace App\Library;


use App\mdRelStoresUsersAdm;
use App\mdStores;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GeneralLibrary
{
    public function storeSelectedByUser($pNotExcept = false )
    {
        $storeUserAdmId = 0;

        if(Session::has('StoresUserAdm')){
            foreach(Session::get('StoresUserAdm') as $store){
                if($store['selected']){
                    $storeUserAdmId = $store['id'];
                }
            }
        } else {
            if($pNotExcept){
                return 0;
            } else {
                throw new \ErrorException('Não existe loja selecionada');
            }
        }

        return $storeUserAdmId;
    }

    public function isUserOfStoreSelected()
    {
        $isUserOfStoreSelected = false;
        $UserAuth = Auth::user();

        $relUsersAdmBystore = User::pesqUserAdmByStore($this->storeSelectedByUser());

        foreach($relUsersAdmBystore as $userAdmByStore){
            if($userAdmByStore->useradm ==  $UserAuth->id){
                $isUserOfStoreSelected = true;
            }
        }

        return $isUserOfStoreSelected;
    }

    public function pesqStoresOfUserAdm($pUserAdm = null)
    {
        $userAuth = Auth::user();
        if(is_null($pUserAdm)){
            $allStoresUserAdm = User::find($userAuth->id)->allStoresByUserAdm;
        } else {
            $allStoresUserAdm = User::find($pUserAdm)->allStoresByUserAdm;
        }

        for ($i = 0; $i < count($allStoresUserAdm); $i++){
            $inStoresUserAdm[$i] = $allStoresUserAdm[$i]->id;
        }

        return $storesUserAdm = mdStores::where('status', 'S')->whereIn('id', $inStoresUserAdm)->orderBy('affiliate','asc')->orderBy('id','asc')->get();
    }

    public function pesqUsersAdmByStores()
    {
        $storesUserAdm = $this->pesqStoresOfUserAdm();

        for ($i = 0; $i < count($storesUserAdm); $i++){
            $inStoresUserAdm[$i] = $storesUserAdm[$i]->id;
        }

        $RelStoresUsersAdm = mdRelStoresUsersAdm::select('useradm')->whereIn('store', $inStoresUserAdm)->groupBy('useradm')->get();

        for ($i = 0; $i < count($RelStoresUsersAdm); $i++){
            $inUsersAdmStore[$i] = $RelStoresUsersAdm[$i]->useradm;
        }

        return $UsersAdm = User::whereIn('id', $inUsersAdmStore)->orderBy('id','asc')->get();
    }
}
