<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Library\GeneralLibrary;
use App\mdStores;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Gate;

class AdminController extends Controller
{

    private $generalLibrary;

    public function __construct()
    {
        $this->generalLibrary = new GeneralLibrary();
    }

    function __destruct() {
        unset($this->generalLibrary);
    }

    public function dashboard()
    {
        if(Auth::check() === true){
            //return view('admin.dashboardHome');
            return redirect()->route('dashboard.home');
        }

        return redirect()->route('dashboard.login');
    }

    public function ShowDashboardLoginForm()
    {
        if(Auth::check() === true){
            //return view('admin.dashboardHome');
            return redirect()->route('dashboard.home');
        }

        return view('admin.dashboardLogin');
    }

    public function DashboardLogin(Request $request)
    {
        /*$user = User::find(1);
        Auth::login($user);
        $login['success'] = true;
        echo json_encode($login);
        return;*/

        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $login['success'] = false;
            $login['message'] = 'O e-mail informado não é valido!';
            echo json_encode($login);
            return;
        }

        $credentials = [
            'email'     => $request->email,
            'password'  => $request->password
        ];

        if(Auth::attempt($credentials)){

            $userAuth = Auth::user();

            if(strtoupper($userAuth->status) == 'S'){

                if( (Gate::allows('isadmintotal')) || (Gate::allows('isadminedit')) ) {
                    $login['success'] = true;
                    echo json_encode($login);
                    return;
                }

                $storesUserAdm = $this->generalLibrary->pesqStoresOfUserAdm();

                if(count($storesUserAdm) == 0){
                    Auth::logout();
                    $login['success'] = false;
                    $login['message'] = 'Nenhuma loja está habilitada!';
                    echo json_encode($login);
                    return;
                } else {
                    $login['success'] = true;
                    echo json_encode($login);
                    return;
                }

            } else {
                Auth::logout();
                $login['success'] = false;
                $login['message'] = 'O usuário não está habilitado!';
                echo json_encode($login);
                return;
            }

        }

        $login['success'] = false;
        $login['message'] = 'Os dados informados não conferem!';
        echo json_encode($login);
        return;

    }

    public function SelectStoreDashboard(Request $request, mdStores $store)
    {
        $dataStoresUserAdm = Session::get('StoresUserAdm');

        for ($i = 0; $i < count($dataStoresUserAdm); $i++){
            if($dataStoresUserAdm[$i]['id'] == $store->id){
                $dataStoresUserAdm[$i]['selected'] = true;
            } else {
                $dataStoresUserAdm[$i]['selected'] = false;
            }
        }

        $request->session()->put('StoresUserAdm', $dataStoresUserAdm);

        return back();

    }

    public function DashboardLogout(Request $request)
    {
        $request->session()->forget('StoresUserAdm');
        Auth::logout();
        return redirect()->route('dashboard');
    }
}
