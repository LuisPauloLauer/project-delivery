<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\mdCategoriesStore;
use Illuminate\Http\Request;
use App\Http\Requests\admin\CategoriesStoreFormRequest;

class CategoriesStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $CategoriesStore = mdCategoriesStore::all();
        return view('admin.categoriesstore.CategoriesStore',[
            'listCategoriesStore'=>$CategoriesStore
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categoriesstore.addCategoriesStore');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriesStoreFormRequest $request)
    {
        $CategoriesStore = new mdCategoriesStore();
        $CategoriesStore->name = $request->name;
        $CategoriesStore->description = $request->description;

        if($CategoriesStore->save())
            return back()->with('success','ID: ('.$CategoriesStore->id.') Categoria cadastrada com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mdCategoriesStore  $mdCategoriesStore
     * @return \Illuminate\Http\Response
     */
    public function show(mdCategoriesStore $mdCategoriesStore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mdCategoriesStore  $mdCategoriesStore
     * @return \Illuminate\Http\Response
     */
    public function edit(mdCategoriesStore $categoriesstore)
    {
        return view('admin.categoriesstore.editCategoriesStore',[
            'Categoriesstore' => $categoriesstore
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mdCategoriesStore  $mdCategoriesStore
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriesStoreFormRequest $request, mdCategoriesStore $categoriesstore)
    {
        $categoriesstore->name = $request->name;
        $categoriesstore->status = $request->status;
        $categoriesstore->description = $request->description;

        if($categoriesstore->save())
            return back()->with('success','ID: ('.$categoriesstore->id.') Categoria alterada com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mdCategoriesStore  $mdCategoriesStore
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(mdCategoriesStore::where('id', $request->categoriesStore_id)->exists()) {
            $categoriesstore = mdCategoriesStore::findOrFail($request->categoriesStore_id);
            try {
                if ($categoriesstore->delete()) {
                    return back()->with('success', 'ID: '.'('. $categoriesstore->id .')'.' Categoria deletada com sucesso');
                } else {
                    return back()->with('error', 'Erro ID: '.'('. $categoriesstore->id .')'.' Categoria não foi deletada!!!');
                }
            } catch (\Exception $exception) {
                if($exception->getCode()==23000)
                    return back()->with('error', 'Erro: (23000) ID: '.'('. $categoriesstore->id .')'.
                        ' Categoria possuí registros filhos verifique as tabelas dependentes!!!');
            }
        }else{
            return back()->with('error', 'Erro ID: '.'('. $request->categoriesStore_id .')'.' Categoria não existe!!!');
        }
    }

    public function changeStatus(Request $request)
    {
        $objectID       = $request->object_id;
        $objectStatus   = $request->object_status;

        if(mdCategoriesStore::where('id', $objectID)->exists()) {

            $categoriesstore = mdCategoriesStore::where('id', $objectID)->first();

            $categoriesstore->status = $objectStatus;

            if($categoriesstore->save()){
                $responseObject['success'] = true;
                if(strtoupper($objectStatus) == 'S'){
                    $responseObject['message'] = 'Categoria ID ('.$objectID.') habilitada';
                } else {
                    $responseObject['message'] = 'Categoria ID ('.$objectID.') desabilitada';
                }

                unset($categoriesstore);
                echo json_encode($responseObject);
                return;
            } else {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Categoria ID ('.$objectID.') erro ao alterar o status!';

                unset($categoriesstore);
                echo json_encode($responseObject);
                return;
            }

        } else {
            $responseObject['success'] = false;
            $responseObject['message'] = 'Categoria ID ('.$objectID.') não encontrado!';
            echo json_encode($responseObject);
            return;
        }

    }
}
