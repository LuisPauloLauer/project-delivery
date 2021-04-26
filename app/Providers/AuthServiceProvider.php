<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('managerAdministrator', function($user){
            return  (
                ($user::find($user->id)->pesqTypeUserAdm->type == 'admintotal') ||
                ($user::find($user->id)->pesqTypeUserAdm->type == 'adminedit')
            );
        });

        $gate->define('managerUsersAdm', function($user){
            return  (
                ($user::find($user->id)->pesqTypeUserAdm->type == 'admintotal') ||
                ($user::find($user->id)->pesqTypeUserAdm->type == 'adminedit') ||
                ($user::find($user->id)->pesqTypeUserAdm->type == 'adminstore')
            );
        });

        $gate->define('managerProducts', function($user){
            return (
                ($user::find($user->id)->pesqTypeUserAdm->type !== 'admintotal')&&
                ($user::find($user->id)->pesqTypeUserAdm->type !== 'adminedit')
            );
        });

        ////////////////////////////////////////////////////////////////////////////////////

        $gate->define('isadmintotal', function($user){
            return $user::find($user->id)->pesqTypeUserAdm->type == 'admintotal';
        });


        $gate->define('isadminedit', function($user){
            return $user::find($user->id)->pesqTypeUserAdm->type == 'adminedit';
        });

        $gate->define('isadminstore', function($user){
            return $user::find($user->id)->pesqTypeUserAdm->type == 'adminstore';
        });

        $gate->define('iseditstore', function($user){
            return $user::find($user->id)->pesqTypeUserAdm->type == 'editstore';
        });

        $gate->define('isreadstore', function($user){
            return $user::find($user->id)->pesqTypeUserAdm->type == 'readstore';
        });
    }
}
