<?php

namespace App\Http\Controllers\site\api;

use App\Events\site\registerUserSiteByEmailEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\site\UsersSiteFormRequest;
use App\Http\Resources\site\UniversityBuildingsResource;
use App\Http\Resources\site\UserSiteResource;
use App\Library\GeneralLibrary;
use App\mdStores;
use App\mdUniversitybuildings;
use App\UserSite;
use Illuminate\Http\Request;


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

    public function getBuildings()
    {
        $universityBuildings = mdUniversitybuildings::where('id', '<>', 1)->get();

        if(!$universityBuildings){
            $response['success'] = false;
            $response['message'] = 'Erro ao buscar prédios!';
        } else {
            $universityBuildings = UniversityBuildingsResource::collection($universityBuildings);

            $response['success'] = true;
            $response['message'] = 'OK!';
            $response['buildings'] = $universityBuildings;
        }
        echo json_encode($response);
        return;
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

    public function loginUserSiteByEmail(Request $request)
    {
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $response['success']        = false;
            $response['typeMessage']    = 'emailError';
            $response['message']        = 'O e-mail informado não é valido!';
        }

        $credentials = $request->only('email', 'password');

        // return response()->json(new JsonResponse(new UserResource($user)), Response::HTTP_OK);

        if(UserSite::where('email', $request->email)->exists()) {
            if(auth()->guard('site')->attempt($credentials)){
                $userAuth = auth()->guard('site')->user();
                if($userAuth->is_verified === 1){
                    if($userAuth->status === 'S'){
                        $userAuth = new UserSiteResource($userAuth);

                        $response['success']        = true;
                        $response['typeMessage']    = 'userSuccess';
                        $response['user']           = $userAuth;
                    } else {
                        $response['success']        = false;
                        $response['typeMessage']    = 'userError';
                        $response['message']        = 'Usuário bloqueado!';
                    }
                } else {
                    $response['success']        = false;
                    $response['typeMessage']    = 'userError';
                    $response['message']        = 'Ative o usuário verifique seu email: '.$userAuth->email;
                }
            } else {
                $response['success']        = false;
                $response['typeMessage']    = 'userError';
                $response['message']        = 'Os dados informados não conferem!';
            }
        } else {
            $response['success']        = false;
            $response['typeMessage']    = 'userError';
            $response['message']        = 'usuário não cadastrado';
        }

        echo json_encode($response);
        return;

    }
}
