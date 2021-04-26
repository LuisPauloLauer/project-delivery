<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Library\GeneralLibrary;
use App\mdDemandsFood;
use App\mdStatusDemandsFood;
use App\mdStores;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gate;
use Illuminate\Support\Facades\Session;


class AdminHomeController extends Controller
{
    private $generalLibrary;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->generalLibrary = new GeneralLibrary();
    }

    function __destruct() {
        unset($this->generalLibrary);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        unset($dataStoresUserAdm);

        if( (Gate::allows('isadmintotal')) || (Gate::allows('isadminedit')) ) {

            $request->session()->forget('StoresUserAdm');

            $statusDemandsFood = mdStatusDemandsFood::where('type', 'included')->first();

            if( mdDemandsFood::where('status', $statusDemandsFood->id)->exists() ) {
                $CountDemandsFood = mdDemandsFood::where('status', $statusDemandsFood->id)->count();
            } else {
                $CountDemandsFood = 0;
            }

            return view('admin.dashboardHome', [
                'countDemandsFood' => $CountDemandsFood
            ]);

        } else {

            $storesUserAdm = $this->generalLibrary->pesqStoresOfUserAdm();

            if(count($storesUserAdm) == 0){
                Auth::logout();
                return redirect()->route('dashboard');
            }

            if($this->generalLibrary->storeSelectedByUser(true) != 0){
                for ($i = 0; $i < count($storesUserAdm); $i++){

                    if ($storesUserAdm[$i]->id == $this->generalLibrary->storeSelectedByUser()) {
                        $dataStoresUserAdm[$i] = array(
                            'id'            => $storesUserAdm[$i]->id,
                            'name'          => mdStores::find($storesUserAdm[$i]->id)->name,
                            'slug'          => mdStores::find($storesUserAdm[$i]->id)->slug,
                            'image_logo'    => mdStores::find($storesUserAdm[$i]->id)->path_image_logo,
                            'selected'      => true
                        );
                    } else {
                        $dataStoresUserAdm[$i] = array(
                            'id'            => $storesUserAdm[$i]->id,
                            'name'          => mdStores::find($storesUserAdm[$i]->id)->name,
                            'slug'          => mdStores::find($storesUserAdm[$i]->id)->slug,
                            'image_logo'    => mdStores::find($storesUserAdm[$i]->id)->path_image_logo,
                            'selected'      => false
                        );
                    }
                }
            } else {
                for ($i = 0; $i < count($storesUserAdm); $i++){
                    if ( $i == 0 ) {
                        $dataStoresUserAdm[$i] = array(
                            'id'            => $storesUserAdm[$i]->id,
                            'name'          => mdStores::find($storesUserAdm[$i]->id)->name,
                            'slug'          => mdStores::find($storesUserAdm[$i]->id)->slug,
                            'image_logo'    => mdStores::find($storesUserAdm[$i]->id)->path_image_logo,
                            'selected'      => true
                        );
                    } else {
                        $dataStoresUserAdm[$i] = array(
                            'id'            => $storesUserAdm[$i]->id,
                            'name'          => mdStores::find($storesUserAdm[$i]->id)->name,
                            'slug'          => mdStores::find($storesUserAdm[$i]->id)->slug,
                            'image_logo'    => mdStores::find($storesUserAdm[$i]->id)->path_image_logo,
                            'selected'      => false
                        );
                    }
                }
            }

            $request->session()->forget('StoresUserAdm');
            $request->session()->put('StoresUserAdm', $dataStoresUserAdm);

            if($this->generalLibrary->isUserOfStoreSelected()){

                $statusDemandsFood = mdStatusDemandsFood::where('type', 'included')->first();

                if( mdDemandsFood::where('store', $this->generalLibrary->storeSelectedByUser())->where('status', $statusDemandsFood->id)->exists() ){
                    $CountDemandsFood = mdDemandsFood::where('store', $this->generalLibrary->storeSelectedByUser())->where('status', $statusDemandsFood->id)->count();
                } else {
                    $CountDemandsFood = 0;
                }

                return view('admin.dashboardHome', [
                    'countDemandsFood' => $CountDemandsFood
                ]);

            } else {
                abort(404,"Sorry, You can do this actions");
            }

        }
    }
}
