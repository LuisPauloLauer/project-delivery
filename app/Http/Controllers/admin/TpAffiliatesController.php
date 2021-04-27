<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\mdTpAffiliates;
use App\Http\Requests\admin\TpAffiliatesFormRequest;
use Illuminate\Http\Request;

class TpAffiliatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $TpAffiliates = mdTpAffiliates::all();
        return view('admin.tpaffiliates.TpAffiliates',[
            'listTpAffiliate'   =>  $TpAffiliates
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tpaffiliates.addTpAffiliates');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TpAffiliatesFormRequest $request)
    {
        $TpAffiliate = new mdTpAffiliates();
        $TpAffiliate->name = $request->name;
        $TpAffiliate->description = $request->description;

        if($TpAffiliate->save())
            return back()->with('success','ID: ('.$TpAffiliate->id.') Tipo de afiliado cadastrado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mdTpAffiliates  $mdTpAffiliates
     * @return \Illuminate\Http\Response
     */
    public function show(mdTpAffiliates $mdTpAffiliates)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mdTpAffiliates  $mdTpAffiliates
     * @return \Illuminate\Http\Response
     */
    public function edit(mdTpAffiliates $tpaffiliate)
    {
        return view('admin.tpaffiliates.editTpAffiliates',[
            'TpAffiliate' => $tpaffiliate
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mdTpAffiliates  $mdTpAffiliates
     * @return \Illuminate\Http\Response
     */
    public function update(TpAffiliatesFormRequest $request, mdTpAffiliates $tpaffiliate)
    {
        $tpaffiliate->name = $request->name;
        $tpaffiliate->status = $request->status;
        $tpaffiliate->description = $request->description;

        if($tpaffiliate->save())
            return back()->with('success','ID: ('.$tpaffiliate->id.') Tipo de afiliado alterado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mdTpAffiliates  $mdTpAffiliates
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(mdTpAffiliates::where('id', $request->TpAffiliate_id)->exists()) {
            $tpaffiliate = mdTpAffiliates::findOrFail($request->TpAffiliate_id);
            try {
                if ($tpaffiliate->delete()) {
                    return back()->with('success', 'ID: '.'('. $tpaffiliate->id .')'.' Tipo de afiliado deletado com sucesso');
                } else {
                    return back()->with('error', 'Erro ID: '.'('. $tpaffiliate->id .')'.' Tipo de afiliado não foi deletado!!!');
                }
            } catch (\Exception $exception) {
                if($exception->getCode()==23000)
                    return back()->with('error', 'Erro: (23000) ID: '.'('. $tpaffiliate->id .')'.
                        ' Tipo de afiliado possuí registros filhos verifique as tabelas dependentes!!!');
            }
        }else{
            return back()->with('error', 'Erro ID: '.'('. $request->TpAffiliate_id .')'.' Tipo de afiliado não existe!!!');
        }
    }

    public function changeStatus(Request $request)
    {
        $objectID       = $request->object_id;
        $objectStatus   = $request->object_status;

        if(mdTpAffiliates::where('id', $objectID)->exists()) {

            $tpaffiliate = mdTpAffiliates::where('id', $objectID)->first();

            $tpaffiliate->status = $objectStatus;

            if($tpaffiliate->save()){
                $responseObject['success'] = true;
                if(strtoupper($objectStatus) == 'S'){
                    $responseObject['message'] = 'Tipo de Afiliado ID ('.$objectID.') habilitado';
                } else {
                    $responseObject['message'] = 'Tipo de Afiliado ID ('.$objectID.') desabilitado';
                }

                unset($tpaffiliate);
                echo json_encode($responseObject);
                return;
            } else {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Tipo de Afiliado ID ('.$objectID.') erro ao alterar o status!';

                unset($tpaffiliate);
                echo json_encode($responseObject);
                return;
            }

        } else {
            $responseObject['success'] = false;
            $responseObject['message'] = 'Tipo de Afiliado ID ('.$objectID.') não encontrado!';
            echo json_encode($responseObject);
            return;
        }

    }
}
