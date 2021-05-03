<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\ProductsFormRequest;
use App\Library\GeneralLibrary;
use App\mdImagensProducts;
use App\mdKits;
use App\mdProducts;
use App\mdRelKitsProducts;
use App\mdStores;
use App\mdCategoriesProduct;
use App\Library\FilesControl;
use Illuminate\Http\Request;
use Gate;

class ProductsController extends Controller
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

            $Kits = mdKits::where('status', 'S')->where('store', $this->generalLibrary->storeSelectedByUser())->orderBy('store', 'asc')->orderBy('id', 'asc')->get();

            return view('admin.products.addProducts', [
                'Store' => $Store,
                'listCategoriesProduct' => $CategoriesProduct,
                'listKit' => $Kits
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
    public function store(ProductsFormRequest $request)
    {
        if ($this->generalLibrary->isUserOfStoreSelected()) {

            $Product = new mdProducts();

            $Product->store = $request->store;
            $Product->category_product = $request->category_product;
            $Product->id_pdv_store = $request->id_pdv_store;
            $Product->codigo_pdv_store = $request->codigo_pdv_store;
            $Product->codigo_barras_pdv_store = $request->codigo_barras_pdv_store;
            $Product->name = $request->name;
            $Product->amount = $request->amount;
            $Product->unit_price = $request->unit_price;

            if (isset($request->unit_promotion_price)) {
                if (!empty($request->unit_promotion_price) && ($request->unit_promotion_price > 0)) {
                    $Product->unit_promotion_price = $request->unit_promotion_price;
                    $Product->unit_discount = 100 - (round((($request->unit_promotion_price * 100) / $request->unit_price), 4));
                } else {
                    $Product->unit_promotion_price = 0;
                    $Product->unit_discount = 0;
                }
            } else {
                $Product->unit_promotion_price = 0;
                $Product->unit_discount = 0;
            }

            $Product->description = $request->description;


            if ($Product->save()) {

                /*Kits of Product*/
                if (isset($request->kit)) {
                    $KitsProduct = $request->kit;
                    for ($i = 0; $i < count($KitsProduct); $i++) {

                        $RelKitsProduct = new mdRelKitsProducts();
                        $RelKitsProduct->kit = $KitsProduct[$i];
                        $RelKitsProduct->product = $Product->id;
                        $RelKitsProduct->save();

                        unset($RelKitsProduct);
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

                                $imagensProducts = new mdImagensProducts();
                                $imagensProducts->product = $Product->id;
                                $imagensProducts->store = $Product->store;

                                try {
                                    $imagensProducts->path_image = FilesControl::saveImage($imageSave, $imageInput, 'products/store_id_'.$Product->store, $Product->id, 1);
                                } catch (\Exception $exception) {
                                    $imagensProducts->path_image = null;
                                    return back()->with('error', 'Erro Produto ID: (' . $Product->id . ') ' . $exception->getMessage());
                                }finally{
                                    unset($imageSave);
                                    unset($imageInput);
                                    $imagensProducts->save();
                                }
                                unset($imagensProducts);
                            }
                        }
                    }
                }

                return back()->with('success', 'ID: ' . $Product->id . ' Produto cadastrado com sucesso');
            }

        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mdProducts  $mdProducts
     * @return \Illuminate\Http\Response
     */
    public function show(mdProducts $mdProducts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mdProducts  $mdProducts
     * @return \Illuminate\Http\Response
     */
    public function edit(mdProducts $product)
    {
        $pathImagens = FilesControl::getPathImages();

        if ($this->generalLibrary->isUserOfStoreSelected()) {
            if ($this->generalLibrary->storeSelectedByUser() == $product->store) {

                $Store = mdStores::where('id', $product->store)->first();

                $CategoriesProduct = mdCategoriesProduct::where('store', $this->generalLibrary->storeSelectedByUser())->get();

                $Kits = mdKits::where('status', 'S')->where('store', $this->generalLibrary->storeSelectedByUser())->orderBy('store', 'asc')->orderBy('id', 'asc')->get();

                $RelKitsProducts = mdRelKitsProducts::where('product', $product->id)->get();

                $relImagensProduct = mdImagensProducts::where('product', $product->id)->get();

                return view('admin.products.editProducts', [
                    'Product'               => $product,
                    'Store'                 => $Store,
                    'listCategoriesProduct' => $CategoriesProduct,
                    'listKit'               => $Kits,
                    'listRelKitsProducts'   => $RelKitsProducts,
                    'relImagensProduct'     => $relImagensProduct,
                    'pathImagens'           => $pathImagens
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
     * @param  \App\mdProducts  $mdProducts
     * @return \Illuminate\Http\Response
     */
    public function update(ProductsFormRequest $request, mdProducts $product)
    {
        if ($this->generalLibrary->isUserOfStoreSelected()) {

            $product->category_product          = $request->category_product;
            $product->status                    = $request->status;
            $product->id_pdv_store              = $request->id_pdv_store;
            $product->codigo_pdv_store          = $request->codigo_pdv_store;
            $product->codigo_barras_pdv_store   = $request->codigo_barras_pdv_store;
            $product->name                      = $request->name;
            $product->amount                    = $request->amount;
            $product->unit_price                = $request->unit_price;

            if(isset($request->unit_promotion_price)){
                if(!empty($request->unit_promotion_price) && ($request->unit_promotion_price > 0) ){
                    $product->unit_promotion_price = $request->unit_promotion_price;
                    $product->unit_discount = 100 - (round((($request->unit_promotion_price * 100) / $request->unit_price) , 4));
                } else {
                    $product->unit_promotion_price = 0;
                    $product->unit_discount = 0;
                }
            } else {
                $product->unit_promotion_price = 0;
                $product->unit_discount = 0;
            }

            $product->description = $request->description;

            if($product->save()){

                /*Kits of Product*/
                $product->allkitsByProduct()->sync($request->kit);

                //Delete old images or old images altereds
                if(isset($request->oldImagen)){

                    $oldImagens = $request->oldImagen;
                    $oldRelImagensProduct = mdImagensProducts::where('product', $product->id)->whereNotIn('id', $oldImagens)->get();

                    foreach ($oldRelImagensProduct as $oldImagenProduct){
                        $path_img = storage_path('app/public/upload/images/products/store_id_'.$product->store. '/' . $product->id);

                        try {
                            FilesControl::deleteImage($path_img,$oldImagenProduct->path_image);
                            mdImagensProducts::where('id', $oldImagenProduct->id)->delete();
                        } catch (\Exception $exception) {
                            return back()->with('error','Erro Produto ID: ('.$product->id.') '.$exception->getMessage());
                        }
                    }

                } else {

                    if(mdImagensProducts::where('product', $product->id)->exists()){
                        $oldRelImagensProduct = mdImagensProducts::where('product', $product->id)->get();

                        foreach ($oldRelImagensProduct as $oldImagenProduct){
                            $path_img = storage_path('app/public/upload/images/products/store_id_'.$product->store. '/' . $product->id);

                            try {
                                FilesControl::deleteImage($path_img,$oldImagenProduct->path_image);
                                mdImagensProducts::where('id', $oldImagenProduct->id)->delete();
                            } catch (\Exception $exception) {
                                return back()->with('error','Erro Produto ID: ('.$product->id.') '.$exception->getMessage());
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

                                $imagensProducts = new mdImagensProducts();
                                $imagensProducts->product = $product->id;
                                $imagensProducts->store = $product->store;

                                try {
                                    $imagensProducts->path_image = FilesControl::saveImage($imageSave, $imageInput, 'products/store_id_'.$product->store, $product->id, 1);
                                } catch (\Exception $exception) {
                                    $imagensProducts->path_image = null;
                                    return back()->with('error', 'Erro Produto ID: (' . $product->id . ') ' . $exception->getMessage());
                                }finally{
                                    unset($imageSave);
                                    unset($imageInput);
                                    $imagensProducts->save();
                                }
                                unset($imagensProducts);
                            }
                        }
                    }
                }

                return back()->with('success','ID: '.$product->id.' Produto alterado com sucesso');
            }

        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mdProducts  $mdProducts
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($this->generalLibrary->isUserOfStoreSelected()) {

            if(mdProducts::where('id', $request->Product_id)->exists()) {
                $product = mdProducts::findOrFail($request->Product_id);
                $pathObject = storage_path('app/public/upload/images/products/store_id_'.$product->store. '/' . $product->id);
                try {
                    if ($product->delete()) {
                        if (is_dir($pathObject)) {
                            if ( FilesControl::deleteImageAndPath($pathObject)) {
                                return back()->with('success', 'ID: ' . '(' . $product->id . ')' . ' Produto e imagen(s) foram deletados com sucesso');
                            } else {
                                return back()->with('error', 'Erro ID: ' . '(' . $product->id . ')' . ' Produto deletado, mas imagen(s) não foram deletada(s)!!!');
                            }
                        } else {
                            return back()->with('success', 'ID: ' . '(' . $product->id . ')' . ' Produto deletado com sucesso');
                        }
                    } else {
                        return back()->with('error', 'Erro ID: ' . '(' . $product->id . ')' . ' Produto não foi deletado!!!');
                    }
                } catch (\Exception $exception) {
                    if($exception->getCode()==23000)
                        return back()->with('error', 'Erro: (23000) ID: '.'('. $product->id .')'.
                            ' Produto possuí registros filhos verifique as tabelas dependentes!!!');
                }
            }else{
                return back()->with('error', 'Erro ID: '.'('. $request->Product_id .')'.' Produto não existe!!!');
            }
        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    public function changeOrder(Request $request)
    {
        $products = mdProducts::where('store', $this->generalLibrary->storeSelectedByUser())->orderBy('n_order', 'asc')->get();

        foreach ($products as $product) {
            $product->timestamps = false;
            $id = $product->id;
            foreach ($request->products as $productFrontEnd) {
                if ($productFrontEnd['id'] == $id) {
                    $product->update(['n_order' => $productFrontEnd['n_order']]);
                }
            }
        }

        $responseObject['success'] = true;
        $responseObject['message'] = 'Produto Ordem atualizada com sucesso.';
        echo json_encode($responseObject);
        return;

    }

    public function changeStatus(Request $request)
    {
        $objectID       = $request->object_id;
        $objectStatus   = $request->object_status;

        if(mdProducts::where('id', $objectID)->exists()) {

            $product = mdProducts::where('id', $objectID)->first();

            if ($product->store != $this->generalLibrary->storeSelectedByUser(true)) {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Usuário não pertence à loja do Produto!';
                echo json_encode($responseObject);
                return;
            }

            $product->alterStatusManual   = true;
            $product->status              = $objectStatus;

            if($product->save()){
                $responseObject['success'] = true;
                if(strtoupper($objectStatus) == 'S'){
                    $responseObject['message'] = 'Produto id ('.$objectID.') habilitado para venda';
                } else {
                    $responseObject['message'] = 'Produto id ('.$objectID.') desabilitado para venda';
                }

                unset($product);

                echo json_encode($responseObject);
                return;
            } else {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Produto id ('.$objectID.') erro ao alterar o status!';

                unset($product);

                echo json_encode($responseObject);
                return;
            }

        } else {
            $responseObject['success'] = false;
            $responseObject['message'] = 'Produto id ('.$objectID.') não encontrado!';
            echo json_encode($responseObject);
            return;
        }

    }

    public function pesqProducts($pesqdefault)
    {
        if($pesqdefault == 'default'){

            if ($this->generalLibrary->isUserOfStoreSelected()) {

                $Products = mdProducts::select('products.*')
                    ->join('categoriesproduct', 'products.category_product', '=', 'categoriesproduct.id')
                    ->where('products.store', $this->generalLibrary->storeSelectedByUser())
                    ->orderBy('categoriesproduct.n_order', 'asc')
                    ->orderBy('products.n_order', 'asc')
                    ->limit(1500)->get();

                $CategoriesProduct = mdCategoriesProduct::where('store', $this->generalLibrary->storeSelectedByUser())->get();

                return view('admin.products.Products', [
                    'listProduct' => $Products,
                    'listCategoriesProduct' => $CategoriesProduct
                ]);

            } else {
                abort(404, "Sorry, You can do this actions");
            }

        } else if ($pesqdefault == 'index'){

            if ($this->generalLibrary->isUserOfStoreSelected()) {

                $CategoriesProduct = mdCategoriesProduct::where('store', $this->generalLibrary->storeSelectedByUser())->get();

                return view('admin.products.Products', [
                    'listCategoriesProduct' => $CategoriesProduct
                ]);

            } else {
                abort(404, "Sorry, You can do this actions");
            }
        }

    }

    public function searchProductBySelected(Request $request)
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
                    $Products = mdProducts::where('store', $this->generalLibrary->storeSelectedByUser())->where('id', $txtFilterObject)->get();
                    $msgErrorFilterProducts = 'ID';
                    break;
                case "2":
                    $Products = mdProducts::where('store', $this->generalLibrary->storeSelectedByUser())->where('id_pdv_store', $txtFilterObject)->get();
                    $msgErrorFilterProducts = 'ID PDV';
                    break;
                case "3":
                    $Products = mdProducts::where('store', $this->generalLibrary->storeSelectedByUser())->where('codigo_pdv_store', $txtFilterObject)->get();
                    $msgErrorFilterProducts = 'Código PDV';
                    break;
                case "4":
                    $Products = mdProducts::where('store', $this->generalLibrary->storeSelectedByUser())->where('codigo_barras_pdv_store', $txtFilterObject)->get();
                    $msgErrorFilterProducts = 'Código de barras PDV';
                    break;
                case "5":
                    if ($optionFilterCategoria == 'T') {
                        $Products = mdProducts::where('store', $this->generalLibrary->storeSelectedByUser())->where('description', 'like', '%' . $txtFilterObject . '%')->get();
                    } else {
                        $Products = mdProducts::where('store', $this->generalLibrary->storeSelectedByUser())->where('description', 'like', '%' . $txtFilterObject . '%')->where('category_product', $optionFilterCategoria)->get();
                    }
                    $msgErrorFilterProducts = 'Descrição';
                    break;
                case "6":
                    if ($optionFilterCategoria == 'T') {

                        $Products = [];

                        return view('admin.products.Products', [
                            'msgErrorFilterProducts' => 'Selecione uma categoria',
                            'listProduct' => $Products,
                            'optionFilterObject' => $optionFilterObject,
                            'txtFilterObject' => $txtFilterObject,
                            'listCategoriesProduct' => $CategoriesProduct,
                            'statusckbFilterObject' => $statusckbFilterObject,
                            'optionFilterCategoria' => $optionFilterCategoria
                        ]);

                    } else {
                        $Products = mdProducts::where('store', $this->generalLibrary->storeSelectedByUser())->where('category_product', $optionFilterCategoria)->get();
                    }
                    $msgErrorFilterProducts = 'Categoria apenas';
                    break;
                default:
                    $Products = mdProducts::where('store', $this->generalLibrary->storeSelectedByUser())->where('id', $txtFilterObject)->get();
                    $msgErrorFilterProducts = 'ID';
                    break;
            }

            if (empty($txtFilterObject) && ($optionFilterObject == '1' || $optionFilterObject == '5')) {

                $Products = [];

                return view('admin.products.Products', [
                    'msgErrorFilterProducts' => 'Insira algum valor na consulta por: ' . $msgErrorFilterProducts,
                    'listProduct' => $Products,
                    'optionFilterObject' => $optionFilterObject,
                    'txtFilterObject' => $txtFilterObject,
                    'listCategoriesProduct' => $CategoriesProduct,
                    'statusckbFilterObject' => $statusckbFilterObject,
                    'optionFilterCategoria' => $optionFilterCategoria
                ]);
            }

            return view('admin.products.Products', [
                'listProduct' => $Products,
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
