<?php

namespace App\Http\Controllers\site\api;

use App\Events\site\registerUserSiteByEmailEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\site\UsersSiteFormRequest;
use App\Library\GeneralLibrary;
use App\mdStores;
use App\mdUniversitybuildings;
use App\UserSite;


class UsersSiteController extends Controller
{
    private $generalLibrary;

    public function __construct()
    {
        $this->generalLibrary = new GeneralLibrary();
    }

    function __destruct() {
        unset($this->generalLibrary);
    }

    public function storeUserSiteByEmail(UsersSiteFormRequest $request)
    {
        if(!$this->generalLibrary->validatePhoneNumber($request->fone)){
            $response['success']        = false;
            $response['typeMessage']    = 'foneError';
            $response['message']        = 'número de celular inválido!!!';
            echo json_encode($response);
            return;
        }

        if(!mdUniversitybuildings::where('id', $request->building)->exists()){
            $response['success']        = false;
            $response['typeMessage']    = 'buildingError';
            $response['message']        = 'Empresa não existe!!!';
            echo json_encode($response);
            return;
        }

        if(!mdStores::where('id', $request->store)->exists()) {
            $response['success']        = false;
            $response['typeMessage']    = 'storeError';
            $response['message']        = 'Loja não existe!!! Contate o suporte!!!';
            echo json_encode($response);
            return;
        } else {
            $store = mdStores::findOrFail($request->store);
        }

        $userSite = new UserSite();

        $userSite->universitybuilding   = $request->building;
        $userSite->name                 = $request->name;
        $userSite->email                = $request->email;
        $userSite->password             = $request->password;
        $userSite->fone                 = $request->fone;
        $userSite->verification_code    = sha1(time().rand(111,99999));

        try {
            if($userSite->save()){

                event(new registerUserSiteByEmailEvent($userSite, $store));

                $response['success']        = true;
                $response['typeMessage']    = 'defaultSuccess';
                $response['message']        = 'Confira sua caixa de e-mail para ativação da conta';
                echo json_encode($response);
                return;
            } else {
                $response['success']        = false;
                $response['typeMessage']    = 'defaultError';
                $response['message']        = 'Erro ao cadastrar o usuário contate o suporte!';
                echo json_encode($response);
                return;
            }
        } catch (\Exception $exception) {
            $response['success']        = false;
            $response['typeMessage']    = 'defaultError';
            $response['message']        = 'Erro ao cadastrar o usuário contate o suporte!';
            echo json_encode($response);
            return;
        }

    }

    public function loginUserSiteByEmail()
    {
        $response['success']        = true;
        $response['typeMessage']    = 'teste';
        $response['message']        = 'Testando api login';
        echo json_encode($response);
        return;
    }
}
