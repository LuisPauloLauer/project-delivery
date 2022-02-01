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

Route::get('usuario/retorna-predios','site\api\UsersSiteController@getBuildings');
Route::post('usuario/registro/email', 'site\api\UsersSiteController@storeUserSiteByEmail');
Route::post('usuario/login/email', 'site\api\UsersSiteController@loginUserSiteByEmail');

