<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\mdSocialAccount;
use App\mdStores;
use App\UserSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class UsersSiteController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mdStores  $mdStores
     * @return \Illuminate\Http\Response
     */
    public function verifyAccountUserSite(mdStores $store, $email, $vcode)
    {
        if(UserSite::where('email', $email)->where('is_verified', 1)->exists()){
            $userSite = UserSite::where('email', $email)->where('is_verified', 1)->first();

            return view('site.user.verifyUser', [
                'appName'       => env('APP_NAME'),
                'store'         => $store,
                'userSite'      => $userSite,
                'userVerified'  => true
            ]);

        } else if (UserSite::where('email', $email)->where('verification_code', $vcode)->where('is_verified', 0)->exists()) {
            $userSite = UserSite::where('email', $email)->where('verification_code', $vcode)->where('is_verified', 0)->first();

            $userSite->status               = 'S';
            $userSite->is_verified          = 1;
            $userSite->verification_code    = '';

            if($userSite->save()){
                return view('site.user.verifyUser', [
                    'appName'       => env('APP_NAME'),
                    'store'         => $store,
                    'userSite'      => $userSite,
                    'userVerified'  => false
                ]);
            }
        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    public function loginUserSite(Request $request)
    {
        $urlCallBack = url()->previous();
        $request->session()->put('returnurlcallback', $urlCallBack);

        if(!Session::has('userSiteLogged')){
            return view('site.user.loginUser');
        } else {
            return redirect()->route('home.index');
        }
    }

    public function redirectToProviderFacebook(Request $request)
    {
        if(Session::has('userSiteLogged')){
            return redirect()->route('home.index');
        }

        return Socialite::driver('facebook')->redirect();
    }

    public function redirectToProviderGoogle()
    {
        if(Session::has('userSiteLogged')){
            return redirect()->route('home.index');
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallbackFacebook(Request $request)
    {
        $request->session()->forget('userSiteLogged');

        $userSiteFacebook = Socialite::driver('facebook')->user();

        $SocialAccount = mdSocialAccount::where('provider_name', 'facebook')
            ->where('provider_id', $userSiteFacebook->getId())
            ->first();

        if ($SocialAccount) {

            $userSite  = mdSocialAccount::find($SocialAccount->id)->pesqUserSite;

            $request->session()->put('userSiteLogged', $userSite);

            //return redirect()->route('home.index');
            return redirect($request->session()->get('returnurlcallback'));

        } else {

            $userSite = null;

            if ($emailUserSiteFacebook = $userSiteFacebook->getEmail()) {
                $userSite = UserSite::where('email', $emailUserSiteFacebook)->first();
            }

            if (!$userSite) {

               $userSiteData = [
                    'status'            => 'S',
                    'name'              => $userSiteFacebook->getName(),
                    'slug'              => Str::slug($userSiteFacebook->getName()),
                    'email'             => $userSiteFacebook->getEmail(),
                    'email_verified_at' => now(),
                    'avatar'            => $userSiteFacebook->getAvatar(),
                    'token'             => $userSiteFacebook->token,
                    'provider'          => 'facebook',
                    'provider_id'       => $userSiteFacebook->getId(),
                ];

                $request->session()->put('userSiteData', $userSiteData);

                return redirect('usuario/cadastro');
            }

            if($userSite){

                $userSite->allSocialAccountsByUserSite()->create([
                    'provider_name'     => 'facebook',
                    'provider_id'       => $userSiteFacebook->getId(),
                    'email'             => $userSiteFacebook->getEmail(),
                ]);

                $request->session()->put('userSiteLogged', $userSite);

                //return redirect()->route('home.index');
                return redirect($request->session()->get('returnurlcallback'));

            }
        }
    }

    public function handleProviderCallbackGoogle(Request $request)
    {
        $request->session()->forget('userSiteLogged');

        $userSiteGoogle = Socialite::driver('google')->user();

        $SocialAccount = mdSocialAccount::where('provider_name', 'google')
            ->where('provider_id', $userSiteGoogle->getId())
            ->first();

        if ($SocialAccount) {

            $userSite  = mdSocialAccount::find($SocialAccount->id)->pesqUserSite;

            $request->session()->put('userSiteLogged', $userSite);
            //dd(Session::get('userSiteLogged'));

            //return redirect()->route('home.index');
            return redirect($request->session()->get('returnurlcallback'));

        } else {

            $userSite = null;

            if ($emailUserSiteGoogle = $userSiteGoogle->getEmail()) {
                $userSite = UserSite::where('email', $emailUserSiteGoogle)->first();
            }

            if (!$userSite) {

                $userSiteData = [
                    'status'            => 'S',
                    'name'              => $userSiteGoogle->getName(),
                    'slug'              => Str::slug($userSiteGoogle->getName()),
                    'email'             => $userSiteGoogle->getEmail(),
                    'email_verified_at' => now(),
                    'avatar'            => $userSiteGoogle->getAvatar(),
                    'token'             => $userSiteGoogle->token,
                    'provider'          => 'google',
                    'provider_id'       => $userSiteGoogle->getId(),
                ];

                $request->session()->put('userSiteData', $userSiteData);

                return redirect('usuario/cadastro');
            }

            if($userSite){

                $userSite->allSocialAccountsByUserSite()->create([
                    'provider_name'     => 'google',
                    'provider_id'       => $userSiteGoogle->getId(),
                    'email'             => $userSiteGoogle->getEmail(),
                ]);

                $request->session()->put('userSiteLogged', $userSite);

                //return redirect()->route('home.index');
                return redirect($request->session()->get('returnurlcallback'));

            }
        }
    }

    public function createUserSite(Request $request)
    {

        $userSiteData = $request->session()->get('userSiteData');

        return view('site.user.addUser',[
            'userSiteData' =>  $userSiteData
        ]);
    }

    public function storeUserSite(Request $request)
    {
        $userSiteData = $request->session()->get('userSiteData');
        $fone = $request->fone;

        if(!filter_var($userSiteData['email'], FILTER_VALIDATE_EMAIL)){
            $login['success'] = false;
            $login['message'] = 'O e-mail informado n??o ?? valido!';
            echo json_encode($login);
            return;
        }

        if(!isset($fone)){
            $login['success'] = false;
            $login['message'] = 'Informe um n??mero de telefone celular';
            echo json_encode($login);
            return;
        } else {
            if(UserSite::where('fone', preg_replace('/\D+/', '', $fone))->exists()) {
                $login['success'] = false;
                $login['message'] = 'Usu??rio j?? cadastrado com esse n??mero de telefone';
                echo json_encode($login);
                return;
            }
        }

        $UserSite = new UserSite();

        if($userSiteData){
            $UserSite->status               = $userSiteData['status'];
            $UserSite->name                 = $userSiteData['name'];
            $UserSite->slug                 = $userSiteData['slug'];
            $UserSite->fone                 = $fone;
            $UserSite->email                = $userSiteData['email'];
            $UserSite->email_verified_at    = $userSiteData['email_verified_at'];
            $UserSite->remember_token       = $userSiteData['token']; //Str::random(10);
        }

        try {

            if($UserSite->save()){

                $request->session()->put('userSiteLogged', $UserSite);

                //dd($request->session()->get('userSiteLogged'));

                $UserSite->allSocialAccountsByUserSite()->create([
                    'provider_name' => $userSiteData['provider'],
                    'provider_id'   => $userSiteData['provider_id'],
                    'email'         => $userSiteData['email'],
                ]);

                $login['success'] = true;
                echo json_encode($login);
                return;

            } else {
                $login['success'] = false;
                $login['message'] = 'Erro ao cadastrar o usu??rio';
                echo json_encode($login);
                return;
            }

        } catch (\Exception $exception) {
            $login['success'] = false;
            $login['message'] = 'Erro ao cadastrar o usu??rio';
            echo json_encode($login);
            return;
        }
    }

    public function logoutUserSite(Request $request)
    {
        $request->session()->forget('userSiteLogged');
        $request->session()->forget('shopCartKit');
        $request->session()->forget('shopCartProduct');

        return redirect()->route('home.index');
    }
}
