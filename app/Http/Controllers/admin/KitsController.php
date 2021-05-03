<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Library\GeneralLibrary;
use App\Library\FilesControl;
use App\mdImagensKits;
use App\mdKits;
use App\mdStores;
use App\mdCategoriesProduct;
use App\Http\Requests\admin\KitsFormRequest;
use Illuminate\Http\Request;
use Gate;

class KitsController extends Controller
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
    public function index($pesqdefault = false)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->generalLibrary->isUserOfStoreSelected()) {

            $Store = mdStores::where('status', 'S')->where('id', $this->generalLibrary->storeSelectedByUser())->first();

            $CategoriesProduct = mdCategoriesProduct::where('store', $this->generalLibrary->storeSelectedByUser())->get();

            return view('admin.kits.addKits', [
                'Store' => $Store,
                'listCategoriesProduct' => $CategoriesProduct
            ]);

        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KitsFormRequest $request)
    {
        if ($this->generalLibrary->isUserOfStoreSelected()) {

            if($this->generalLibrary->storeSelectedByUser() == $request->store){
                $Kit = new mdKits();

                $Kit->store                     = $request->store;
                $Kit->category_product          = $request->category_product;
                $Kit->id_pdv_store              = $request->id_pdv_store;
                $Kit->codigo_pdv_store          = $request->codigo_pdv_store;
                $Kit->codigo_barras_pdv_store   = $request->codigo_barras_pdv_store;
                $Kit->name                      = $request->name;
                $Kit->amount                    = $request->amount;
                $Kit->unit_price                = $request->unit_price;

                if(isset($request->unit_promotion_price)){
                    if(!empty($request->unit_promotion_price) && ($request->unit_promotion_price > 0) ){
                        $Kit->unit_promotion_price = $request->unit_promotion_price;
                        $Kit->unit_discount = 100 - (round((($request->unit_promotion_price * 100) / $request->unit_price) , 4));
                    } else {
                        $Kit->unit_promotion_price = 0;
                        $Kit->unit_discount = 0;
                    }
                } else {
                    $Kit->unit_promotion_price = 0;
                    $Kit->unit_discount = 0;
                }

                $Kit->description = $request->description;

                if($Kit->save()){

                    // Upload of Images
                    if($request->hasFile('imagen')){
                        $imagens = $request->file('imagen');

                        for ($i = 0; $i < count($imagens); $i++){
                            $imageSave = 'imageSave_n'.strval($i);

                            if(isset($request->$imageSave)){
                                if(!is_null($request->$imageSave)){

                                    $imageSave = $request->$imageSave;
                                    $imageInput = $imagens[$i];

                                    $imagensKits = new mdImagensKits();
                                    $imagensKits->kit = $Kit->id;
                                    $imagensKits->store = $Kit->store;

                                    try {
                                        $imagensKits->path_image = FilesControl::saveImage($imageSave, $imageInput,'kits/store_id_'.$Kit->store,$Kit->id,1);
                                    } catch (\Exception $exception) {
                                        $imagensKits->path_image = null;
                                        return back()->with('error','Erro Kit ID: ('.$Kit->id.') '.$exception->getMessage());
                                    }finally{
                                        unset($imageSave);
                                        unset($imageInput);
                                        $imagensKits->save();
                                    }
                                    unset($imagensKits);
                                }
                            }
                        }
                    }

                    return back()->with('success','ID: '.$Kit->id.' Kit cadastrado com sucesso');
                }
            } else {
                return back()->with('error','Erro Loja ID: ('.$request->store.') incorreta para cadastro');
            }
        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mdKits  $mdKits
     * @return \Illuminate\Http\Response
     */
    public function show(mdKits $mdKits)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mdKits  $mdKits
     * @return \Illuminate\Http\Response
     */
    public function edit(mdKits $kit)
    {
        $pathImagens = FilesControl::getPathImages();

        if ($this->generalLibrary->isUserOfStoreSelected()) {
            if ($this->generalLibrary->storeSelectedByUser() == $kit->store) {
                $Store = mdStores::where('id', $kit->store)->first();

                $CategoriesProduct = mdCategoriesProduct::where('store', $this->generalLibrary->storeSelectedByUser())->get();

                $relImagensKit = mdImagensKits::where('kit', $kit->id)->get();

                return view('admin.kits.editKits', [
                    'Kit' => $kit,
                    'Store' => $Store,
                    'listCategoriesProduct' => $CategoriesProduct,
                    'relImagensKit' => $relImagensKit,
                    'pathImagens' => $pathImagens
                ]);
            } else {
                abort(404, "Sorry, You can do this actions");
            }
        } else {
            abort(404, "Sorry, You can do this actions");
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mdKits  $mdKits
     * @return \Illuminate\Http\Response
     */
    public function update(KitsFormRequest $request, mdKits $kit)
    {
        if($this->generalLibrary->isUserOfStoreSelected()) {
            if($this->generalLibrary->storeSelectedByUser() == $kit->store) {
                $kit->category_product          = $request->category_product;
                $kit->status                    = $request->status;
                $kit->id_pdv_store              = $request->id_pdv_store;
                $kit->codigo_pdv_store          = $request->codigo_pdv_store;
                $kit->codigo_barras_pdv_store   = $request->codigo_barras_pdv_store;
                $kit->name                      = $request->name;
                $kit->amount                    = $request->amount;
                $kit->unit_price                = $request->unit_price;

                if(isset($request->unit_promotion_price)){
                    if(!empty($request->unit_promotion_price) && ($request->unit_promotion_price > 0) ){
                        $kit->unit_promotion_price = $request->unit_promotion_price;
                        $kit->unit_discount = 100 - (round((($request->unit_promotion_price * 100) / $request->unit_price) , 4));
                    } else {
                        $kit->unit_promotion_price = 0;
                        $kit->unit_discount = 0;
                    }
                } else {
                    $kit->unit_promotion_price = 0;
                    $kit->unit_discount = 0;
                }

                $kit->description = $request->description;

                if($kit->save()){

                    //Delete old images or old images altereds
                    if(isset($request->oldImagen)){

                        $oldImagens = $request->oldImagen;
                        $oldRelImagensKit = mdImagensKits::where('kit', $kit->id)->whereNotIn('id', $oldImagens)->get();

                        foreach ($oldRelImagensKit as $oldImagenKit){
                            $path_img = storage_path('app/public/upload/images/kits/store_id_'.$kit->store. '/' . $kit->id);

                            try {
                                FilesControl::deleteImage($path_img,$oldImagenKit->path_image);
                                mdImagensKits::where('id', $oldImagenKit->id)->delete();
                            } catch (\Exception $exception) {
                                return back()->with('error','Erro Kit ID: ('.$kit->id.') '.$exception->getMessage());
                            }
                        }

                    } else {

                        if(mdImagensKits::where('kit', $kit->id)->exists()){
                            $oldRelImagensKit = mdImagensKits::where('kit', $kit->id)->get();

                            foreach ($oldRelImagensKit as $oldImagenKit){
                                $path_img = storage_path('app/public/upload/images/kits/store_id_'.$kit->store. '/' . $kit->id);

                                try {
                                    FilesControl::deleteImage($path_img,$oldImagenKit->path_image);
                                    mdImagensKits::where('id', $oldImagenKit->id)->delete();
                                } catch (\Exception $exception) {
                                    return back()->with('error','Erro Kit ID: ('.$kit->id.') '.$exception->getMessage());
                                }
                            }
                        }

                    }

                    // Upload of Images
                    if($request->hasFile('imagen')){
                        $imagens = $request->file('imagen');

                        for ($i = 0; $i < count($imagens); $i++){
                            $imageSave = 'imageSave_n'.strval($i);

                            if(isset($request->$imageSave)){
                                if(!is_null($request->$imageSave)){

                                    $imageSave = $request->$imageSave;
                                    $imageInput = $imagens[$i];

                                    $imagensKits = new mdImagensKits();
                                    $imagensKits->kit = $kit->id;
                                    $imagensKits->store = $kit->store;

                                    try {
                                        $imagensKits->path_image = FilesControl::saveImage($imageSave, $imageInput,'kits/store_id_'.$kit->store,$kit->id,1);
                                    } catch (\Exception $exception) {
                                        $imagensKits->path_image = null;
                                        return back()->with('error','Erro Kit ID: ('.$kit->id.') '.$exception->getMessage());
                                    }finally{
                                        unset($imageSave);
                                        unset($imageInput);
                                        $imagensKits->save();
                                    }
                                    unset($imagensKits);
                                }
                            }
                        }
                    }

                    return back()->with('success','ID: '.$kit->id.' Kit alterado com sucesso');
                }
            } else {
                return back()->with('error','Erro Kit ID: ('.$kit->id.') incorreto para esta loja ID ('.$this->generalLibrary->storeSelectedByUser().')');
            }
        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mdKits  $mdKits
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($this->generalLibrary->isUserOfStoreSelected()) {

            if (mdKits::where('id', $request->Kit_id)->exists()) {
                $kit = mdKits::findOrFail($request->Kit_id);
                $pathObject = storage_path('app/public/upload/images/kits/store_id_' . $kit->store . '/' . $kit->id);
                try {
                    if ($kit->delete()) {
                        if (is_dir($pathObject)) {
                            if (FilesControl::deleteImageAndPath($pathObject)) {
                                return back()->with('success', 'ID: ' . '(' . $kit->id . ')' . ' Kit e imagen(s) foram deletados com sucesso');
                            } else {
                                return back()->with('error', 'Erro ID: ' . '(' . $kit->id . ')' . ' Kit deletado, mas imagen(s) não foram deletada(s)!!!');
                            }
                        } else {
                            return back()->with('success', 'ID: ' . '(' . $kit->id . ')' . ' Kit deletado com sucesso');
                        }
                    } else {
                        return back()->with('error', 'Erro ID: ' . '(' . $kit->id . ')' . ' Kit não foi deletado!!!');
                    }
                } catch (\Exception $exception) {
                    if ($exception->getCode() == 23000)
                        return back()->with('error', 'Erro: (23000) ID: ' . '(' . $kit->id . ')' .
                            ' Kit possuí registros filhos verifique as tabelas dependentes!!!');
                }
            } else {
                return back()->with('error', 'Erro ID: ' . '(' . $request->Kit_id . ')' . ' Kit não existe!!!');
            }

        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    public function changeOrder(Request $request)
    {

        $kits = mdKits::where('store', $this->generalLibrary->storeSelectedByUser())->orderBy('n_order', 'asc')->get();

        foreach ($kits as $kit) {
            $kit->timestamps = false;
            $id = $kit->id;
            foreach ($request->kits as $kitFrontEnd) {
                if ($kitFrontEnd['id'] == $id) {
                    $kit->update(['n_order' => $kitFrontEnd['n_order']]);
                }
            }
        }

        $responseObject['success'] = true;
        $responseObject['message'] = 'Kit Ordem atualizada com sucesso.';
        echo json_encode($responseObject);
        return;

    }

    public function changeStatus(Request $request)
    {
        $objectID       = $request->object_id;
        $objectStatus   = $request->object_status;

        if(mdKits::where('id', $objectID)->exists()) {

            $kit = mdKits::where('id', $objectID)->first();

            if ($kit->store != $this->generalLibrary->storeSelectedByUser(true)) {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Usuário não pertence à loja do Kit!';
                echo json_encode($responseObject);
                return;
            }

            $kit->alterStatusManual   = true;
            $kit->status              = $objectStatus;

            if($kit->save()){
                $responseObject['success'] = true;
                if(strtoupper($objectStatus) == 'S'){
                    $responseObject['message'] = 'Kit id ('.$objectID.') habilitado para venda';
                } else {
                    $responseObject['message'] = 'Kit id ('.$objectID.') desabilitado para venda';
                }

                unset($kit);

                echo json_encode($responseObject);
                return;
            } else {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Kit id ('.$objectID.') erro ao alterar o status!';

                unset($kit);

                echo json_encode($responseObject);
                return;
            }

        } else {
            $responseObject['success'] = false;
            $responseObject['message'] = 'Kit id ('.$objectID.') não encontrado!';
            echo json_encode($responseObject);
            return;
        }
    }

    public function pesqKits($pesqdefault)
    {
        if($pesqdefault == 'default'){

            if ($this->generalLibrary->isUserOfStoreSelected()) {

                $Kits = mdKits::select('kits.*')
                                ->join('categoriesproduct', 'kits.category_product', '=', 'categoriesproduct.id')
                                ->where('kits.store', $this->generalLibrary->storeSelectedByUser())
                                ->orderBy('categoriesproduct.n_order', 'asc')
                                ->orderBy('kits.n_order', 'asc')
                                ->limit(1500)->get();

                $CategoriesProduct = mdCategoriesProduct::where('store', $this->generalLibrary->storeSelectedByUser())->get();

                return view('admin.kits.Kits', [
                    'listKit' => $Kits,
                    'listCategoriesProduct' => $CategoriesProduct
                ]);

            } else {
                abort(404, "Sorry, You can do this actions");
            }

        } else if ($pesqdefault == 'index'){

            if ($this->generalLibrary->isUserOfStoreSelected()) {

                $CategoriesProduct = mdCategoriesProduct::where('store', $this->generalLibrary->storeSelectedByUser())->get();

                return view('admin.kits.Kits', [
                    'listCategoriesProduct' => $CategoriesProduct
                ]);

            } else {
                abort(404, "Sorry, You can do this actions");
            }
        }
    }

    public function searchKitBySelected(Request $request)
    {
        $ckbFilterObject            = $request->ckbFilterObject;
        $optionFilterObject         = $request->opFilterObject;
        $txtFilterObject            = $request->txtFilterObject;
        $optionFilterCategoria      = $request->opFilterCategoria;

        if(!is_null($ckbFilterObject)){
            $statusckbFilterObject = true;
        } else {
            $statusckbFilterObject = false;
        }

        $CategoriesProduct = mdCategoriesProduct::where('store', $this->generalLibrary->storeSelectedByUser())->get();

        if ($this->generalLibrary->isUserOfStoreSelected()) {

            switch ($optionFilterObject) {
                case "1":
                    $Kits = mdKits::where('store', $this->generalLibrary->storeSelectedByUser())->where('id', $txtFilterObject)->get();
                    $msgErrorFilterKits = 'ID';
                    break;
                case "2":
                    $Kits = mdKits::where('store', $this->generalLibrary->storeSelectedByUser())->where('id_pdv_store', $txtFilterObject)->get();
                    $msgErrorFilterKits = 'ID PDV';
                    break;
                case "3":
                    $Kits = mdKits::where('store', $this->generalLibrary->storeSelectedByUser())->where('codigo_pdv_store', $txtFilterObject)->get();
                    $msgErrorFilterKits = 'Código PDV';
                    break;
                case "4":
                    $Kits = mdKits::where('store', $this->generalLibrary->storeSelectedByUser())->where('codigo_barras_pdv_store', $txtFilterObject)->get();
                    $msgErrorFilterKits = 'Código de barras PDV';
                    break;
                case "5":
                    if ($optionFilterCategoria == 'T') {
                        $Kits = mdKits::where('store', $this->generalLibrary->storeSelectedByUser())->where('description', 'like', '%' . $txtFilterObject . '%')->get();
                    } else {
                        $Kits = mdKits::where('store', $this->generalLibrary->storeSelectedByUser())->where('description', 'like', '%' . $txtFilterObject . '%')->where('category_product', $optionFilterCategoria)->get();
                    }
                    $msgErrorFilterKits = 'Descrição';
                    break;
                case "6":
                    if ($optionFilterCategoria == 'T') {

                        $Kits = [];

                        return view('admin.kits.Kits', [
                            'msgErrorFilterKits' => 'Selecione uma categoria',
                            'listKit' => $Kits,
                            'optionFilterObject' => $optionFilterObject,
                            'txtFilterObject' => $txtFilterObject,
                            'listCategoriesProduct' => $CategoriesProduct,
                            'statusckbFilterObject' => $statusckbFilterObject,
                            'optionFilterCategoria' => $optionFilterCategoria
                        ]);

                    } else {
                        $Kits = mdKits::where('store', $this->generalLibrary->storeSelectedByUser())->where('category_product', $optionFilterCategoria)->get();
                    }
                    $msgErrorFilterKits = 'Categoria apenas';
                    break;
                default:
                    $Kits = mdKits::where('store', $this->generalLibrary->storeSelectedByUser())->where('id', $txtFilterObject)->get();
                    $msgErrorFilterKits = 'ID';
                    break;
            }

            if (empty($txtFilterObject) && ($optionFilterObject == '1' || $optionFilterObject == '5')) {

                $Kits = [];

                return view('admin.kits.Kits', [
                    'msgErrorFilterKits' => 'Insira algum valor na consulta por: ' . $msgErrorFilterKits,
                    'listKit' => $Kits,
                    'optionFilterObject' => $optionFilterObject,
                    'txtFilterObject' => $txtFilterObject,
                    'listCategoriesProduct' => $CategoriesProduct,
                    'statusckbFilterObject' => $statusckbFilterObject,
                    'optionFilterCategoria' => $optionFilterCategoria
                ]);
            }

            return view('admin.kits.Kits', [
                'listKit' => $Kits,
                'optionFilterObject' => $optionFilterObject,
                'txtFilterObject' => $txtFilterObject,
                'listCategoriesProduct' => $CategoriesProduct,
                'statusckbFilterObject' => $statusckbFilterObject,
                'optionFilterCategoria' => $optionFilterCategoria
            ]);

        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

}
