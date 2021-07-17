<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Library\GeneralLibrary;
use App\mdCategoriesProduct;
use App\Http\Requests\admin\CategoriesProductFormRequest;
use App\mdKits;
use App\mdProducts;
use App\mdStores;
use Gate;
use Illuminate\Http\Request;

class CategoriesProductController extends Controller
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
        if ($this->generalLibrary->isUserOfStoreSelected()) {

            //$appUrl = env('APP_URL');
            //dd($appUrl);
            $CategoriesProduct = mdCategoriesProduct::where('store', $this->generalLibrary->storeSelectedByUser())->orderBy('n_order', 'asc')->get();
           // dd($CategoriesProduct);

            return view('admin.categoriesproduct.CategoriesProduct', [
                'listCategoriesProduct' => $CategoriesProduct
            ]);

        } else {
            abort(404, "Sorry, You can do this actions");
        }
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

            return view('admin.categoriesproduct.addCategoriesProduct', [
                'Store' => $Store
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
    public function store(CategoriesProductFormRequest $request)
    {
        if ($this->generalLibrary->isUserOfStoreSelected()) {

            $CategoriesProduct = new mdCategoriesProduct();
            $CategoriesProduct->store = $this->generalLibrary->storeSelectedByUser();
            $CategoriesProduct->name = $request->name;
            $CategoriesProduct->description = $request->description;
            $CategoriesProduct->n_order = (mdCategoriesProduct::where('store', $this->generalLibrary->storeSelectedByUser())->count()+1);

            if ($CategoriesProduct->save()) {

                /*Stores of CategoryProduct*/
                /*if (isset($request->store)) {
                    $StoresCategoryProduct = $request->store;
                    for ($i = 0; $i < count($StoresCategoryProduct); $i++) {

                        $countCategoriesByStore = mdRelStoresCategoriesProduct::where('store', $StoresCategoryProduct[$i])->count();

                        $RelStoresCategoryProduct = new mdRelStoresCategoriesProduct();
                        $RelStoresCategoryProduct->store = $StoresCategoryProduct[$i];
                        $RelStoresCategoryProduct->category_product = $CategoriesProduct->id;
                        $RelStoresCategoryProduct->n_order = $countCategoriesByStore+1;
                        $RelStoresCategoryProduct->save();

                        unset($RelStoresCategoryProduct);
                    }
                }*/

                return back()->with('success', 'ID: ' . $CategoriesProduct->id . ' Categoria cadastrada com sucesso');
            }

        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mdCategoriesProduct  $mdCategoriesProduct
     * @return \Illuminate\Http\Response
     */
    public function show(mdCategoriesProduct $mdCategoriesProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mdCategoriesProduct  $mdCategoriesProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(mdCategoriesProduct $categoriesproduct)
    {

        if ($this->generalLibrary->isUserOfStoreSelected()) {

            $Store = mdStores::where('status', 'S')->where('id', $this->generalLibrary->storeSelectedByUser())->first();

            //$RelStoresOfCategory = mdRelStoresCategoriesProduct::where('category_product', $categoriesproduct->id)->get();


            return view('admin.categoriesproduct.editCategoriesProduct',[
                'CategoriesProduct'     => $categoriesproduct,
                'Store'                 => $Store
            ]);

        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mdCategoriesProduct  $mdCategoriesProduct
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriesProductFormRequest $request, mdCategoriesProduct $categoriesproduct)
    {
        if ($this->generalLibrary->isUserOfStoreSelected()) {

            $categoriesproduct->name = $request->name;
            $categoriesproduct->status = $request->status;
            $categoriesproduct->description = $request->description;

            if ($categoriesproduct->save()) {

                /*Stores of CategoryProduct*/
                //$categoriesproduct->allStoresByCategoriesProduct()->sync($request->store);

                return back()->with('success', 'ID: ' . $categoriesproduct->id . ' Categoria alterada com sucesso');
            }

        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mdCategoriesProduct  $mdCategoriesProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($this->generalLibrary->isUserOfStoreSelected()) {

            if (mdCategoriesProduct::where('id', $request->categoriesProduct_id)->exists()) {
                $categoriesProduct = mdCategoriesProduct::findOrFail($request->categoriesProduct_id);
                try {
                    if ($categoriesProduct->delete()) {
                        return back()->with('success', 'ID: ' . '(' . $categoriesProduct->id . ')' . ' Categoria deletada com sucesso');
                    } else {
                        return back()->with('error', 'Erro ID: ' . '(' . $categoriesProduct->id . ')' . ' Categoria não foi deletada!!!');
                    }
                } catch (\Exception $exception) {
                    if ($exception->getCode() == 23000)
                        return back()->with('error', 'Erro: (23000) ID: ' . '(' . $categoriesProduct->id . ')' .
                            ' Categoria possuí registros filhos verifique as tabelas dependentes!!!');
                }
            } else {
                return back()->with('error', 'Erro ID: ' . '(' . $request->categoriesProduct_id . ')' . ' Categoria não existe!!!');
            }

        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    public function listItensByCategory(Request $request)
    {
        if (mdCategoriesProduct::where('id', $request->idCategory)->exists()) {
            $categoriesProduct = mdCategoriesProduct::findOrFail($request->idCategory);

            if($categoriesProduct->store == $this->generalLibrary->storeSelectedByUser()){
                $kits = mdKits::where('category_product', $request->idCategory)->where('store', $this->generalLibrary->storeSelectedByUser())->orderBy('n_order', 'asc')->get();

                $products = mdProducts::where('category_product', $request->idCategory)->where('store', $this->generalLibrary->storeSelectedByUser())->orderBy('n_order', 'asc')->get();

                $responseObject['success'] = true;
                if(count($kits) > 0){
                    $responseObject['kits'] = $kits;
                } else {
                    $responseObject['kits'] = null;
                }
                if(count($products) > 0){
                    $responseObject['products'] = $products;
                } else {
                    $responseObject['products'] = null;
                }
                echo json_encode($responseObject);
                return;
            }else{
                $responseObject['success'] = false;
                $responseObject['message'] = 'Categoria não pertence a loja selecionada!';
                echo json_encode($responseObject);
                return;
            }

        } else {
            $responseObject['success'] = false;
            $responseObject['message'] = 'Categoria não encontrada!';
            echo json_encode($responseObject);
            return;
        }
    }

    public function changeOrder(Request $request)
    {

        $listCategoriesProduct = mdCategoriesProduct::where('store', $this->generalLibrary->storeSelectedByUser())->orderBy('n_order', 'asc')->get();

        foreach ($listCategoriesProduct as $categoriesProduct) {
            $categoriesProduct->timestamps = false;
            $id = $categoriesProduct->id;
            foreach ($request->categoriesProduct as $categoriesProductFrontEnd) {
                if ($categoriesProductFrontEnd['id'] == $id) {
                    $categoriesProduct->update(['n_order' => $categoriesProductFrontEnd['n_order']]);
                }
            }
        }

        $responseObject['success'] = true;
        $responseObject['message'] = 'Categoria Ordem atualizada com sucesso.';
        echo json_encode($responseObject);
        return;

    }

    public function changeStatus(Request $request)
    {
        $objectID       = $request->object_id;
        $objectStatus   = $request->object_status;

        if(mdCategoriesProduct::where('id', $objectID)->exists()) {

            $categoriesproduct = mdCategoriesProduct::where('id', $objectID)->first();

            if ($categoriesproduct->store != $this->generalLibrary->storeSelectedByUser(true)) {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Usuário não pertence à loja da categoria!';
                echo json_encode($responseObject);
                return;
            }

            $categoriesproduct->alterStatusManual   = true;
            $categoriesproduct->status              = $objectStatus;

            if($categoriesproduct->save()){
                $responseObject['success'] = true;
                if(strtoupper($objectStatus) == 'S'){
                    $responseObject['message'] = 'Categoria id ('.$objectID.') habilitada para venda';
                } else {
                    $responseObject['message'] = 'Categoria id ('.$objectID.') desabilitada para venda';
                }

                unset($categoriesproduct);

                echo json_encode($responseObject);
                return;
            } else {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Categoria id ('.$objectID.') erro ao alterar o status!';

                unset($categoriesproduct);

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
}
