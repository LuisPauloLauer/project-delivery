<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//    Route::group(['middleware' => 'auth:api'], function () {
//        Route::get('store/delivery/status/{store}','StoresController@verifyStoreOpenToDelivery');
//    });

Route::namespace('site\api')->group(function() {
    Route::get('usuario/retorna-predios','UsersSiteController@getBuildings');
    Route::get('usuario/retorna-predio/{building}','UsersSiteController@getBuildingByParameters');
    Route::post('usuario/registro/email', 'UsersSiteController@storeUserSiteByEmail');
    Route::post('usuario/login/email', 'UsersSiteController@loginUserSiteByEmail');
    //Route::get('store/delivery/status/{store}','StoresController@verifyStoreOpenToDelivery');

    Route::group(['middleware' => 'auth:site'], function () {
        Route::get('store/delivery/status/{store}','StoresController@verifyStoreOpenToDelivery');
    });

//    Route::group(['middleware' => 'auth:site'], function () {
//        Route::get('store/delivery/status/{store}','StoresController@verifyStoreOpenToDelivery');
//    });
});



