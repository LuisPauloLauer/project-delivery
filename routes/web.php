<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*++++++++++++++++++++++++++++++++++++++++++++++++   ADMIN    ++++++++++++++++++++++++++++++++++++++++++++++++*/

Route::get('/','admin\AdminController@dashboard')->name('dashboard');
Route::get('login','admin\AdminController@ShowDashboardLoginForm')->name('dashboard.login');
Route::get('logout','admin\AdminController@DashboardLogout')->name('dashboard.logout');
Route::post('login/do','admin\AdminController@DashboardLogin')->name('dashboard.login.do');
Route::get('home', 'admin\AdminHomeController@index')->name('dashboard.home');

Route::group(['middleware' => ['auth']], function(){
    ////// ---------------  Acces of Users Administrator  --------------- /////
    Route::resource('tpaffiliates', 'admin\TpAffiliatesController', ['except' => ['show','destroy','changeStatus']])->middleware('can:managerAdministrator');
    Route::delete('tpaffiliates', 'admin\TpAffiliatesController@destroy')->name('tpaffiliates.delete')->middleware('can:managerAdministrator');
    Route::post('tpaffiliates/change/status', 'admin\TpAffiliatesController@changeStatus')->name('tpaffiliates.change.status')->middleware('can:managerAdministrator');

    Route::resource('affiliates', 'admin\AffiliatesController', ['except' => ['show','destroy','changeStatus']])->middleware('can:managerAdministrator');
    Route::delete('affiliates', 'admin\AffiliatesController@destroy')->name('affiliates.delete')->middleware('can:managerAdministrator');
    Route::post('affiliates/change/status', 'admin\AffiliatesController@changeStatus')->name('affiliates.change.status')->middleware('can:managerAdministrator');

    Route::resource('segments', 'admin\SegmentsController', ['except' => ['show','destroy','changeStatus']])->middleware('can:managerAdministrator');
    Route::delete('segments', 'admin\SegmentsController@destroy')->name('segments.delete')->middleware('can:managerAdministrator');
    Route::post('segments/change/status', 'admin\SegmentsController@changeStatus')->name('segments.change.status')->middleware('can:managerAdministrator');

    Route::resource('categoriesstore', 'admin\CategoriesStoreController', ['except' => ['show','destroy','changeStatus']])->middleware('can:managerAdministrator');
    Route::delete('categoriesstore', 'admin\CategoriesStoreController@destroy')->name('categoriesstore.delete')->middleware('can:managerAdministrator');
    Route::post('categoriesstore/change/status', 'admin\CategoriesStoreController@changeStatus')->name('categoriesstore.change.status')->middleware('can:managerAdministrator');

    Route::resource('stores', 'admin\StoresController', ['except' => ['show','destroy','changeStatus']])->middleware('can:managerAdministrator');
    Route::delete('stores', 'admin\StoresController@destroy')->name('stores.delete')->middleware('can:managerAdministrator');
    Route::post('stores/change/status', 'admin\StoresController@changeStatus')->name('stores.change.status')->middleware('can:managerAdministrator');

    ////// ---------------  Acces of Users Admin Store  --------------- /////
    Route::resource('usersadm', 'admin\UsersAdmController', ['except' => ['show','destroy','changeStatus']])->middleware('can:managerUsersAdm');
    Route::delete('usersadm', 'admin\UsersAdmController@destroy')->name('usersadm.delete')->middleware('can:managerUsersAdm');
    Route::post('usersadm/change/status', 'admin\UsersAdmController@changeStatus')->name('usersadm.change.status')->middleware('can:managerUsersAdm');

    Route::get('store/perfil', 'admin\StoresPerfilController@viewStorePerfil')->name('store.perfil')->middleware('can:managerUsersAdm');
    Route::put('store/perfil/edit/dados/{store}', 'admin\StoresPerfilController@updateStorePerfilDados')->name('store.perfil.edit.dados')->middleware('can:managerUsersAdm');
    Route::put('store/perfil/edit/endereco/{store}', 'admin\StoresPerfilController@updateStorePerfilEndereco')->name('store.perfil.edit.endereco')->middleware('can:managerUsersAdm');
    Route::put('store/perfil/edit/timedelivery/{store}', 'admin\StoresPerfilController@updateStorePerfilTimeDelivery')->name('store.perfil.edit.timedelivery')->middleware('can:managerUsersAdm');
    Route::put('store/perfil/edit/payment/{store}', 'admin\StoresPerfilController@updateStorePerfilPayment')->name('store.perfil.edit.payment')->middleware('can:managerUsersAdm');
    Route::post('store/perfil/change/activestore', 'admin\StoresPerfilController@changeActiveStore')->name('store.perfil.change.activestore')->middleware('can:managerUsersAdm');

    ////// ---------------  Acces of Users Store  --------------- /////
    Route::get('usersadm/selectstore/{store}', 'admin\AdminController@SelectStoreDashboard')->name('usersadm.select.store')->middleware('can:managerProducts');

    Route::resource('categoriesproduct', 'admin\CategoriesProductController', ['except' => ['show','destroy','changeStatus']])->middleware('can:managerProducts');
    Route::delete('categoriesproduct', 'admin\CategoriesProductController@destroy')->name('categoriesproduct.delete')->middleware('can:managerProducts');
    Route::post('categoriesproduct/change/status', 'admin\CategoriesProductController@changeStatus')->name('categoriesproduct.change.status')->middleware('can:managerProducts');
    Route::post('categoriesproduct/change/order', 'admin\CategoriesProductController@changeOrder')->name('categoriesproduct.change.order')->middleware('can:managerProducts');
    Route::post('categoriesproduct/listitensbycategory', 'admin\CategoriesProductController@listItensByCategory')->name('categoriesproduct.listitens')->middleware('can:managerProducts');

    Route::resource('kits', 'admin\KitsController', ['except' => ['index','show','destroy', 'pesqKits', 'searchKitBySelected', 'changeStatus']])->middleware('can:managerProducts');
    Route::delete('kits', 'admin\KitsController@destroy')->name('kits.delete')->middleware('can:managerProducts');
    Route::get('kits/search/{pesqdefault}', 'admin\KitsController@pesqKits')->name('kits.pesq')->middleware('can:managerProducts');
    Route::post('kits/search/filter', 'admin\KitsController@searchKitBySelected')->name('kits.search')->middleware('can:managerProducts');
    Route::post('kits/change/status', 'admin\KitsController@changeStatus')->name('kits.change.status')->middleware('can:managerProducts');
    Route::post('categoriesproduct/kits/change/order', 'admin\KitsController@changeOrder')->name('kits.change.order')->middleware('can:managerProducts');

    Route::resource('products', 'admin\ProductsController', ['except' => ['index','show','destroy', 'pesqProducts', 'searchProductBySelected']])->middleware('can:managerProducts');
    Route::delete('products', 'admin\ProductsController@destroy')->name('products.delete')->middleware('can:managerProducts');
    Route::get('products/search/{pesqdefault}', 'admin\ProductsController@pesqProducts')->name('products.pesq')->middleware('can:managerProducts');
    Route::post('products/search/filter', 'admin\ProductsController@searchProductBySelected')->name('products.search')->middleware('can:managerProducts');
    Route::post('products/change/status', 'admin\ProductsController@changeStatus')->name('products.change.status')->middleware('can:managerProducts');
    Route::post('categoriesproduct/products/change/order', 'admin\ProductsController@changeOrder')->name('products.change.order')->middleware('can:managerProducts');

    Route::get('store/mapregions', 'admin\StoresMapRegions@index')->name('store.mapregions')->middleware('can:managerUsersAdm');

    Route::get('orders/{status}', 'admin\DemandsController@viewOrders')->name('view.orders')->middleware('can:managerProducts');
    Route::post('orders/changestatus', 'admin\DemandsController@ordersChangeStatusType')->name('orders.change.status')->middleware('can:managerProducts');
    Route::get('orders/print/{demands}', 'admin\DemandsController@ordersToPrint')->name('orders.print')->middleware('can:managerProducts');
});

/*++++++++++++++++++++++++++++++++++++++++++++++++   SITE    ++++++++++++++++++++++++++++++++++++++++++++++++*/

Route::get('usuario/verificar-conta/{store}/{email}/{vcode}', 'site\UsersSiteController@verifyAccountUserSite');

/*
Route::get('/', 'site\indexController@index')->name('home.index');

///----------  Shop Cart ------------////
Route::post('/product','site\shopCartController@showModalProduct')->name('product.showmodal');
Route::post('/addcarrinho','site\shopCartController@addToCart')->name('cart.add');
Route::post('/editaritemcarrinho','site\shopCartController@editQntyItemToCart')->name('cart.edititem');
Route::post('/deletaritemcarrinho','site\shopCartController@dellItemToCart')->name('cart.dellitem');
Route::get('/vercarrinho','site\shopCartController@getCart')->name('cart.view');
Route::get('/excluircarrinho','site\shopCartController@emptyCart')->name('cart.empty');
Route::get('/pedido/pagar','site\shopCartController@viewPayment')->name('cart.payment');

///----------- Payments -----------///
Route::post('/paypal/pay','site\paypalPaymentController@create')->name('paypal.pay');
Route::get('/paypal/execute-payment','site\paypalPaymentController@payPalStatus')->name('paypal.execute');
Route::get('/paypal/cancel','site\paypalPaymentController@payPalStatus')->name('cancel');

///----------- UsersSite -----------///
Route::get('usuario/login','site\UsersSiteController@loginUserSite')->name('usersite.login');
Route::get('usuario/logout', 'site\UsersSiteController@logoutUserSite')->name('usersite.logout');

Route::get('usuario/login/facebook', 'site\UsersSiteController@redirectToProviderFacebook')->name('usersite.login.facebook');
Route::get('usuario/login/facebook/callback', 'site\UsersSiteController@handleProviderCallbackFacebook')->name('facebook.callback');

Route::get('usuario/login/google', 'site\UsersSiteController@redirectToProviderGoogle')->name('usersite.login.google');
Route::get('usuario/login/google/callback', 'site\UsersSiteController@handleProviderCallbackGoogle')->name('google.callback');

Route::get('usuario/cadastro','site\UsersSiteController@createUserSite')->name('usersite.create');
Route::post('usuario/store/','site\UsersSiteController@storeUserSite')->name('usersite.store');

///-------- Shop Stores -------////
Route::get('segmento/{segment}', 'site\pageSegmentsController@showStoresBySegment')->name('segment.page');
Route::get('segmento/{segment}/{category}', 'site\pageSegmentsController@showStoresBySegmentByCategory')->name('segment.category.page');

Route::get('segmento/{segment}/loja/{store}', 'site\pageStoresController@showStore')->name('store.page');
Route::get('segmento/{segment}/loja/{store}/{category}', 'site\pageStoresController@showStoreByCategory')->name('store.category.page');

//Route::get('/{segment}/loja/{store}/produto/{id}', 'site\shopCartController@addToCart')->name('cart.add');
//Route::get('/{segment}/loja/{store}/produto/{id}', 'site\ProductController@index')->name('product.index');

//Route::match(['get', 'post'], '/{segment}/{category}', 'site\pageSegmentsController@showStoresBySegmentByCategory')->name('segment.category.page');
//Route::get('/home', 'HomeController@index')->name('home');
*/
