<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Library\GeneralLibrary;
use App\mdRelStoresUsersAdm;
use App\mdStores;
use App\mdTpUsersAdm;
use App\User;
use App\Http\Requests\admin\UsersAdmFormRequest;
use App\Library\FilesControl;
use Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UsersAdmController extends Controller
{
    private $generalLibrary;

    public function __construct()
    {
        $this->generalLibrary = new GeneralLibrary();
    }

    function __destruct() {
        unset($this->generalLibrary);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $UserAuth = Auth::user();

        if( (Gate::allows('isadmintotal')) || (Gate::allows('isadminedit')) ) {

            if(Gate::allows('isadmintotal')){
                $UsersAdm = User::all();
                return view('admin.users.UsersAdm',[
                    'listUserAdm' =>  $UsersAdm,
                    'UserAuth'    =>  $UserAuth
                ]);
            } else {
                $UsersAdm = User::where('tpuser', '<>', 1)->get();
                return view('admin.users.UsersAdm',[
                    'listUserAdm' =>  $UsersAdm,
                    'UserAuth'    =>  $UserAuth
                ]);
            }

        } else {

            $UsersAdm = $this->generalLibrary->pesqUsersAdmByStores();

            return view('admin.users.UsersAdm',[
                'listUserAdm' =>  $UsersAdm,
                'UserAuth'    =>  $UserAuth
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $pathImagens = FilesControl::getPathImages();

        if( (Gate::allows('isadmintotal')) || (Gate::allows('isadminedit')) ) {

            if(Gate::allows('isadmintotal')){
                $TpUsersAdm = mdTpUsersAdm::all();

                $Stores = mdStores::pesqStoreOrderByAffiliate();

                return view('admin.users.addUsersAdm',[
                    'listTpUsersAdm' =>  $TpUsersAdm,
                    'listStore'      =>  $Stores,
                    'pathImagens'    =>  $pathImagens
                ]);
            } else {
                $TpUsersAdm = mdTpUsersAdm::where('type', '<>', 'admintotal')->get();

                $Stores = mdStores::pesqStoreOrderByAffiliate();

                return view('admin.users.addUsersAdm',[
                    'listTpUsersAdm' =>  $TpUsersAdm,
                    'listStore'      =>  $Stores,
                    'pathImagens'    =>  $pathImagens
                ]);
            }

        } else {

            $TpUsersAdm = mdTpUsersAdm::whereNotIn('type',  ['admintotal','adminedit'])->get();

            $Stores = $this->generalLibrary->pesqStoresOfUserAdm();

            return view('admin.users.addUsersAdm',[
                'listTpUsersAdm' =>  $TpUsersAdm,
                'listStore'      =>  $Stores,
                'pathImagens'    =>  $pathImagens
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersAdmFormRequest $request)
    {
        if( (Gate::allows('isadmintotal')) || (Gate::allows('isadminedit')) ) {
            if( (Gate::allows('isadminedit')) && ($request->tpuser == 1) ){
                return back()->with('error','Erro tipo de usuário (Administrador-total) não é habilitado para o usuário!!!');
            }

        } else if( (Gate::allows('isadminstore')) ){

            if( (Gate::allows('isadminstore')) && ($request->tpuser == 1) ){
                return back()->with('error','Erro tipo de usuário (Administrador-total) não é habilitado para o usuário!!!');
            } else if ( (Gate::allows('isadminstore')) && ($request->tpuser == 2) ){
                return back()->with('error','Erro tipo de usuário (Administrador-editor) não é habilitado para o usuário!!!');
            }

        } else {
            abort(404,"Sorry, You can do this actions");
        }

        if(($request->tpuser == 3)||($request->tpuser == 4)||($request->tpuser == 5)){
            if(!isset($request->store)){
                return back()->with('error','Erro Deve selecionar pelo menos uma loja para o usuário do tipo loja!!!');
            }
        } else if (($request->tpuser < 1 ) || ($request->tpuser > 5 )){
            return back()->with('error','Erro Tipo de usuário incorreto!!!');
        }

        if(User::where('cpf', preg_replace("/\D/", '', $request->cpf))->exists()) {
            $dataUserAdm = User::where('cpf', preg_replace("/\D/", '', $request->cpf))->first();
            return back()->with('error','Erro CPF: ('.$dataUserAdm->cpf.') já existe no Usuário ID: ('.$dataUserAdm->id.')');
        }

        if( ($request->sex <> "M") && ($request->sex <> "F")){
            return back()->with('error','Erro Sexo incorreto!!!');
        }

        $UserAdm = new User();

        $UserAdm->tpuser    = $request->tpuser;
        $UserAdm->name      = $request->name;
        $UserAdm->cpf       = $request->cpf; //preg_replace("/\D/", '', $request->cpf);
        $UserAdm->birth     = $request->birth;
        $UserAdm->sex       = $request->sex;
        $UserAdm->fone      = $request->fone;//preg_replace('/\D+/', '', $request->fone);
        $UserAdm->email     = $request->email;
        $UserAdm->password  = $request->password;

        if($UserAdm->save()){

            /*Stores of UserAdm*/
            if(($request->tpuser == 3)||($request->tpuser == 4)||($request->tpuser == 5)) {
                if (isset($request->store)) {
                    $StoresUserAdm = $request->store;
                    for ($i = 0; $i < count($StoresUserAdm); $i++) {

                        $RelStoresUserAdm = new mdRelStoresUsersAdm();
                        $RelStoresUserAdm->store = $StoresUserAdm[$i];
                        $RelStoresUserAdm->useradm = $UserAdm->id;
                        $RelStoresUserAdm->save();

                        unset($RelStoresUserAdm);
                    }
                }
            }

            // Upload of Imagen
            if(isset($request->imageSave)) {
                if (!is_null($request->imageSave)) {
                    if ($request->hasFile('imageInput')) {
                        $imageSave = $request->imageSave;
                        $imageInput = $request->file('imageInput');

                        try {
                            if( ($request->tpuser == 1) || ($request->tpuser == 2) ){
                                $UserAdm->path_image = FilesControl::saveImage($imageSave, $imageInput, 'usersAdm/administrator', $UserAdm->id,4);
                            } else {
                                $StoresOfUserAdm = $this->generalLibrary->pesqStoresOfUserAdm($UserAdm->id);
                                $AffiliateStore = mdStores::where('id', $StoresOfUserAdm->first()->id)->first();
                                $UserAdm->path_image = FilesControl::saveImage($imageSave, $imageInput, 'usersAdm/affiliate_id_'.$AffiliateStore->affiliate, $UserAdm->id,4);
                            }

                        } catch (\Exception $exception) {
                            $UserAdm->path_image = null;
                            return back()->with('error','Erro Usuário ID: ('.$UserAdm->id.') '.$exception->getMessage());
                        }finally{
                            $UserAdm->save();
                        }

                    }
                }
            }

            return back()->with('success','ID: ('.$UserAdm->id.') Usuário cadastrado com sucesso');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usersadm)
    {
        $UserAuth = Auth::user();
        $pathImagens = FilesControl::getPathImages();

        if( (Gate::allows('isadmintotal')) || (Gate::allows('isadminedit')) ) {

            if(Gate::allows('isadmintotal')){

                $TpUsersAdm = mdTpUsersAdm::all();
                $Stores = mdStores::pesqStoreOrderByAffiliate();
                $RelStoresUserAdm = mdRelStoresUsersAdm::where('useradm', $usersadm->id)->get();

                if($usersadm->tpuser != 1 && $usersadm->tpuser != 2){
                    $StoresOfUserAdm = $this->generalLibrary->pesqStoresOfUserAdm($usersadm->id);
                    $AffiliateStore = mdStores::where('id', $StoresOfUserAdm->first()->id)->first();
                } else {
                    $AffiliateStore = '';
                }


                return view('admin.users.editUsersAdm',[
                    'UserAdm'                   =>  $usersadm,
                    'UserAuth'                  =>  $UserAuth,
                    'listTpUsersAdm'            =>  $TpUsersAdm,
                    'listStore'                 =>  $Stores,
                    'listRelStoresUserAdm'      =>  $RelStoresUserAdm,
                    'pathImagens'               =>  $pathImagens,
                    'AffiliateStore'            =>  $AffiliateStore
                ]);

            } else {

                $TpUsersAdm = mdTpUsersAdm::where('type', '<>', 'admintotal')->get();
                $Stores = mdStores::pesqStoreOrderByAffiliate();
                $RelStoresUserAdm = mdRelStoresUsersAdm::where('useradm', $usersadm->id)->get();

                if($usersadm->tpuser != 1 && $usersadm->tpuser != 2){
                    $StoresOfUserAdm = $this->generalLibrary->pesqStoresOfUserAdm($usersadm->id);
                    $AffiliateStore = mdStores::where('id', $StoresOfUserAdm->first()->id)->first();
                } else {
                    $AffiliateStore = '';
                }

                return view('admin.users.editUsersAdm',[
                    'UserAdm'                   =>  $usersadm,
                    'UserAuth'                  =>  $UserAuth,
                    'listTpUsersAdm'            =>  $TpUsersAdm,
                    'listStore'                 =>  $Stores,
                    'listRelStoresUserAdm'      =>  $RelStoresUserAdm,
                    'pathImagens'               =>  $pathImagens,
                    'AffiliateStore'            =>  $AffiliateStore
                ]);
            }

        } else {
            $TpUsersAdm = mdTpUsersAdm::whereNotIn('type',  ['admintotal','adminedit'])->get();
            $StoresOfUserAdm = $this->generalLibrary->pesqStoresOfUserAdm();
            $AffiliateStore = mdStores::where('id', $StoresOfUserAdm->first()->id)->first();
            $RelStoresUserAdm = mdRelStoresUsersAdm::where('useradm', $usersadm->id)->get();

            return view('admin.users.editUsersAdm',[
                'UserAdm'                   =>  $usersadm,
                'UserAuth'                  =>  $UserAuth,
                'listTpUsersAdm'            =>  $TpUsersAdm,
                'listStore'                 =>  $StoresOfUserAdm,
                'listRelStoresUserAdm'      =>  $RelStoresUserAdm,
                'pathImagens'               =>  $pathImagens,
                'AffiliateStore'            =>  $AffiliateStore
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UsersAdmFormRequest $request, User $usersadm)
    {
        if( (Gate::allows('isadmintotal')) || (Gate::allows('isadminedit')) ) {
            if( (Gate::allows('isadminedit')) && ($request->tpuser == 1) ){
                return back()->with('error','Erro tipo de usuário (Administrador-total) não é habilitado para o usuário!!!');
            }

        } else if( (Gate::allows('isadminstore')) ){

            if( (Gate::allows('isadminstore')) && ($request->tpuser == 1) ){
                return back()->with('error','Erro tipo de usuário (Administrador-total) não é habilitado para o usuário!!!');
            } else if ( (Gate::allows('isadminstore')) && ($request->tpuser == 2) ){
                return back()->with('error','Erro tipo de usuário (Administrador-editor) não é habilitado para o usuário!!!');
            }

        } else {
            abort(404,"Sorry, You can do this actions");
        }

        if(($request->tpuser == 3)||($request->tpuser == 4)||($request->tpuser == 5)){
            if(!isset($request->store)){
                return back()->with('error','Erro Deve selecionar pelo menos uma loja para o usuário do tipo loja!!!');
            }
        } else if (($request->tpuser < 1 ) || ($request->tpuser > 5 )){
            return back()->with('error','Erro Tipo de usuário incorreto!!!');
        }

        if( ($request->sex <> "M") && ($request->sex <> "F")){
            return back()->with('error','Erro Sexo incorreto!!!');
        }

        $usersadm->tpuser    = $request->tpuser;
        $usersadm->name      = $request->name;
        $usersadm->status    = $request->status;
        $usersadm->sex       = $request->sex;
        $usersadm->fone      = $request->fone;//preg_replace('/\D+/', '', $request->fone);

        if(isset($request->email)){
            if(!empty($request->email)){
                $usersadm->email     = $request->email;
            }
        }

        if(isset($request->password)){
            if(!empty($request->password)){
                $usersadm->password  = $request->password;
            }
        }

        // Upload of Imagen
        if(isset($request->imageSave)) {
            if (!is_null($request->imageSave)) {
                if ($request->hasFile('imageInput')) {

                    $imageSave = $request->imageSave;
                    $imageInput = $request->file('imageInput');

                    if (!is_null($usersadm->path_image)) {
                        if( ($request->tpuser == 1) || ($request->tpuser == 2) ) {
                            $path_img = storage_path('app/public/upload/images/usersAdm/administrator/' . $usersadm->id);
                        } else {
                            $StoresOfUserAdm = $this->generalLibrary->pesqStoresOfUserAdm($usersadm->id);
                            $AffiliateStore = mdStores::where('id', $StoresOfUserAdm->first()->id)->first();
                            $path_img = storage_path('app/public/upload/images/usersAdm/affiliate_id_'.$AffiliateStore->affiliate.'/' .$usersadm->id);
                        }
                        if(FilesControl::deleteImage($path_img, $usersadm->path_image)){
                            try {
                                if( ($request->tpuser == 1) || ($request->tpuser == 2) ){
                                    $usersadm->path_image = FilesControl::saveImage($imageSave, $imageInput, 'usersAdm/administrator', $usersadm->id,4);
                                } else {
                                    $StoresOfUserAdm = $this->generalLibrary->pesqStoresOfUserAdm($usersadm->id);
                                    $AffiliateStore = mdStores::where('id', $StoresOfUserAdm->first()->id)->first();
                                    $usersadm->path_image = FilesControl::saveImage($imageSave, $imageInput, 'usersAdm/affiliate_id_'.$AffiliateStore->affiliate, $usersadm->id,4);
                                }
                            } catch (\Exception $exception) {
                                $usersadm->path_image = null;
                                return back()->with('error','Erro Usuário ID: ('.$usersadm->id.') '.$exception->getMessage());
                            }
                        }else{
                            return back()->with('error','Erro Usuário ID: ('.$usersadm->id.'). Erro ao deletar a imagen anterior!!!');
                        }
                    }else{
                        try {
                            if( ($request->tpuser == 1) || ($request->tpuser == 2) ){
                                $usersadm->path_image = FilesControl::saveImage($imageSave, $imageInput, 'usersAdm/administrator', $usersadm->id,4);
                            } else {
                                $StoresOfUserAdm = $this->generalLibrary->pesqStoresOfUserAdm($usersadm->id);
                                $AffiliateStore = mdStores::where('id', $StoresOfUserAdm->first()->id)->first();
                                $usersadm->path_image = FilesControl::saveImage($imageSave, $imageInput, 'usersAdm/affiliate_id_'.$AffiliateStore->affiliate, $usersadm->id,4);
                            }
                        } catch (\Exception $exception) {
                            $usersadm->path_image = null;
                            return back()->with('error','Erro Usuário ID: ('.$usersadm->id.') '.$exception->getMessage());
                        }
                    }

                }
            } else {
                if (!is_null($usersadm->path_image)) {
                    if( ($request->tpuser == 1) || ($request->tpuser == 2) ) {
                        $path_img = storage_path('app/public/upload/images/usersAdm/administrator/' . $usersadm->id);
                    } else {
                        $StoresOfUserAdm = $this->generalLibrary->pesqStoresOfUserAdm($usersadm->id);
                        $AffiliateStore = mdStores::where('id', $StoresOfUserAdm->first()->id)->first();
                        $path_img = storage_path('app/public/upload/images/usersAdm/affiliate_id_'.$AffiliateStore->affiliate.'/' .$usersadm->id);
                    }
                    if(FilesControl::deleteImage($path_img, $usersadm->path_image)) {
                        $usersadm->path_image = null;
                    } else {
                        return back()->with('error', 'Erro Usuário ID: (' . $usersadm->id . '). Erro ao deletar a imagen anterior!!!');
                    }
                }
            }
        } else {
            if (!is_null($usersadm->path_image)) {
                if( ($request->tpuser == 1) || ($request->tpuser == 2) ) {
                    $path_img = storage_path('app/public/upload/images/usersAdm/administrator/' . $usersadm->id);
                } else {
                    $StoresOfUserAdm = $this->generalLibrary->pesqStoresOfUserAdm($usersadm->id);
                    $AffiliateStore = mdStores::where('id', $StoresOfUserAdm->first()->id)->first();
                    $path_img = storage_path('app/public/upload/images/usersAdm/affiliate_id_'.$AffiliateStore->affiliate.'/' .$usersadm->id);
                }
                if(FilesControl::deleteImage($path_img, $usersadm->path_image)) {
                    $usersadm->path_image = null;
                } else {
                    return back()->with('error', 'Erro Usuário ID: (' . $usersadm->id . '). Erro ao deletar a imagen anterior!!!');
                }
            }
        }

        if($usersadm->save()){

            /*Stores of UserAdm*/
            $usersadm->allStoresByUserAdm()->sync($request->store);

            return back()->with('success','ID: ('.$usersadm->id.') Usuário alterado com sucesso');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $UserAuth = Auth::user();
        if( ($UserAuth->id <> $request->UserAdm_id) ){
            if(User::where('id', $request->UserAdm_id)->exists()) {

                    $userAdm = User::findOrFail($request->UserAdm_id);

                    if( ($userAdm->tpuser == 1) || ($userAdm->tpuser == 2) ){
                        $pathObject = storage_path('app/public/upload/images/usersAdm/administrator/' . $userAdm->id);
                    } else {
                        $StoresOfUserAdm = $this->generalLibrary->pesqStoresOfUserAdm($userAdm->id);
                        $AffiliateStore = mdStores::where('id', $StoresOfUserAdm->first()->id)->first();
                        $pathObject = storage_path('app/public/upload/images/usersAdm/affiliate_id_'.$AffiliateStore->affiliate.'/'. $userAdm->id);
                    }

                    try {

                        if ($userAdm->delete()) {

                            if (is_dir($pathObject)) {
                                if ( FilesControl::deleteImageAndPath($pathObject) ) {
                                    return back()->with('success', 'ID: ' . '(' . $userAdm->id . ')' . ' Usuário e imagen foram deletados com sucesso');
                                } else {
                                    return back()->with('error', 'Erro ID: ' . '(' . $userAdm->id . ')' . ' Usuário deletado, mas imagen não foi deletada!!!');
                                }
                            } else {
                                return back()->with('success', 'ID: ' . '(' . $userAdm->id . ')' . ' Usuário deletado com sucesso');
                            }

                        } else {
                            return back()->with('error', 'Erro ID: ' . '(' . $userAdm->id . ')' . ' Usuário não foi deletado!!!');
                        }

                    } catch (\Exception $exception) {
                        if($exception->getCode()==23000)
                            return back()->with('error', 'Erro: (23000) ID: '.'('. $userAdm->id .')'.
                                ' Usuário possuí registros filhos verifique as tabelas dependentes!!!');
                    }

            }else{
                return back()->with('error', 'Erro ID: '.'('. $request->UserAdm_id .')'.' Usuário não existe!!!');
            }
        } else {
            return back()->with('error', 'Erro ID: '.'('. $request->UserAdm_id .')'.' Usuário logado não pode deletar o própio usuário!!!');
        }

    }

    public function changeStatus(Request $request)
    {
        $objectID       = $request->object_id;
        $objectStatus   = $request->object_status;

        if(User::where('id', $objectID)->exists()) {

            $usersadm = User::where('id', $objectID)->first();

            $usersadm->status = $objectStatus;

            if($usersadm->save()){
                $responseObject['success'] = true;
                if(strtoupper($objectStatus) == 'S'){
                    $responseObject['message'] = 'Usuário ID ('.$objectID.') habilitado';
                } else {
                    $responseObject['message'] = 'Usuário ID ('.$objectID.') desabilitado';
                }

                unset($usersadm);

                echo json_encode($responseObject);
                return;
            } else {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Usuário ID ('.$objectID.') erro ao alterar o status!';

                unset($usersadm);

                echo json_encode($responseObject);
                return;
            }

        } else {
            $responseObject['success'] = false;
            $responseObject['message'] = 'Usuário ID ('.$objectID.') não encontrado!';
            echo json_encode($responseObject);
            return;
        }

    }
}
