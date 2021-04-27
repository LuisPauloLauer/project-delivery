<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\mdAffiliates;
use App\mdTpAffiliates;
use App\mdCities;
use App\Http\Requests\admin\AffiliatesFormRequest;
use Illuminate\Http\Request;

class AffiliatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Affiliates = mdAffiliates::all();
        return view('admin.affiliates.Affiliates',[
            'listAffiliate' =>  $Affiliates
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $TpAffiliates = mdTpAffiliates::where('status', 'S')->get();
        return view('admin.affiliates.addAffiliates',[
            'listTpAffiliate'   =>  $TpAffiliates
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AffiliatesFormRequest $request)
    {
        $Affiliate = new mdAffiliates();

        $Affiliate->tpaffiliate     = $request->tpaffiliate;
        $Affiliate->type_person     = $request->type_person;
        $Affiliate->corporate_name  = $request->corporate_name;
        $Affiliate->fantasy_name    = $request->fantasy_name;
        $Affiliate->zip_code        = preg_replace('/\D+/', '', $request->zip_code);
        $Affiliate->street          = $request->street;
        $Affiliate->number          = $request->number;
        $Affiliate->district        = $request->district;
        $Affiliate->complement      = $request->complement;
        $Affiliate->fone1           = preg_replace('/\D+/', '', $request->fone1);
        $Affiliate->fone2           = preg_replace('/\D+/', '', $request->fone2);
        $Affiliate->email           = $request->email;

        if (isset($request->ibge)) {
            if(mdCities::where('ibge', $request->ibge)->exists()){
                $City = mdCities::where('ibge', $request->ibge)->first();
                $Affiliate->city = $City->id;
            }else{
                return back()->with('error','Erro Cidade não encontrada. Verifique o Cep!!!');
            }
        }
        else{
            return back()->with('error','Erro Cidade não encontrada. Verifique o Cep!!!');
        }

        if ($Affiliate->type_person=='PF'){
            $Affiliate->cpf = preg_replace("/\D/", '', $request->cpf_or_cnpj);
            $Affiliate->cnpj = null;
            if(mdAffiliates::where('cpf', $Affiliate->cpf)->exists()) {
                $dataAffiliate = mdAffiliates::where('cpf', $Affiliate->cpf)->first();
                return back()->with('error','Erro CPF: ('.$request->cpf_or_cnpj.') já existe no Afiliado ID: ('.$dataAffiliate->id.')');
            }
        } else if ($Affiliate->type_person=='PJ') {
            $Affiliate->cnpj = preg_replace("/\D/", '', $request->cpf_or_cnpj);
            $Affiliate->cpf  = null;
            if(mdAffiliates::where('cnpj', $Affiliate->cnpj)->exists()) {
                $dataAffiliate = mdAffiliates::where('cnpj', $Affiliate->cnpj)->first();
                return back()->with('error','Erro CNPJ: ('.$request->cpf_or_cnpj.') já existe no Afiliado ID: ('.$dataAffiliate->id.')');
            }
        }else{
            return back()->with('error','Erro Verifique o tipo de pessoa selecionado!!!');
        }

        if($Affiliate->save())
            return back()->with('success','ID: ('.$Affiliate->id.') Afiliado cadastrado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mdAffiliates  $mdAffiliates
     * @return \Illuminate\Http\Response
     */
    public function show(mdAffiliates $affiliate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mdAffiliates  $mdAffiliates
     * @return \Illuminate\Http\Response
     */
    public function edit(mdAffiliates $affiliate)
    {
        $TpAffiliates = mdTpAffiliates::where('status', 'S')->get();
        return view('admin.affiliates.editAffiliates',[
            'Affiliate'         => $affiliate,
            'listTpAffiliate'   => $TpAffiliates
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mdAffiliates  $mdAffiliates
     * @return \Illuminate\Http\Response
     */
    public function update(AffiliatesFormRequest $request, mdAffiliates $affiliate)
    {
        $affiliate->tpaffiliate         = $request->tpaffiliate;
        $affiliate->alterStatusManual   = true;
        $affiliate->status              = $request->status;
        $affiliate->corporate_name      = $request->corporate_name;
        $affiliate->fantasy_name        = $request->fantasy_name;
        $affiliate->zip_code            = preg_replace('/\D+/', '', $request->zip_code);
        $affiliate->street              = $request->street;
        $affiliate->number              = $request->number;
        $affiliate->district            = $request->district;
        $affiliate->complement          = $request->complement;
        $affiliate->fone1               = preg_replace('/\D+/', '', $request->fone1);
        $affiliate->fone2               = preg_replace('/\D+/', '', $request->fone2);
        $affiliate->email               = $request->email;

        if (isset($request->ibge)) {
            if(mdCities::where('ibge', $request->ibge)->exists()){
                $City = mdCities::where('ibge', $request->ibge)->first();
                $affiliate->city = $City->id;
            }else{
                return back()->with('error','Erro Cidade não encontrada. Verifique o Cep!!!');
            }
        }
        else{
            return back()->with('error','Erro Cidade não encontrada. Verifique o Cep!!!');
        }

        if($affiliate->save())
            return back()->with('success','ID: ('.$affiliate->id.') Afiliado alterado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mdAffiliates  $mdAffiliates
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(mdAffiliates::where('id', $request->Affiliate_id)->exists()) {
            $affiliate = mdAffiliates::findOrFail($request->Affiliate_id);
            try {
                if ($affiliate->delete()) {
                    return back()->with('success', 'ID: '.'('. $affiliate->id .')'.' Afiliado deletado com sucesso');
                } else {
                    return back()->with('error', 'Erro ID: '.'('. $affiliate->id .')'.' Afiliado não foi deletado!!!');
                }
            } catch (\Exception $exception) {
                if($exception->getCode()==23000)
                    return back()->with('error', 'Erro: (23000) ID: '.'('. $affiliate->id .')'.
                        ' Afiliado possuí registros filhos verifique as tabelas dependentes!!!');
            }
        }else{
            return back()->with('error', 'Erro ID: '.'('. $request->Affiliate_id .')'.' Afiliado não existe!!!');
        }
    }

    public function changeStatus(Request $request)
    {
        $objectID       = $request->object_id;
        $objectStatus   = $request->object_status;

        if(mdAffiliates::where('id', $objectID)->exists()) {

            $affiliate = mdAffiliates::where('id', $objectID)->first();

            $affiliate->alterStatusManual   = true;
            $affiliate->status              = $objectStatus;

            if($affiliate->save()){
                $responseObject['success'] = true;
                if(strtoupper($objectStatus) == 'S'){
                    $responseObject['message'] = 'Afiliado ID ('.$objectID.') habilitado';
                } else {
                    $responseObject['message'] = 'Afiliado ID ('.$objectID.') desabilitado';
                }

                unset($affiliate);
                echo json_encode($responseObject);
                return;

            } else {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Afiliado ID ('.$objectID.') erro ao alterar o status!';

                unset($affiliate);
                echo json_encode($responseObject);
                return;
            }

        } else {
            $responseObject['success'] = false;
            $responseObject['message'] = 'Afiliado ID ('.$objectID.') não encontrado!';
            echo json_encode($responseObject);
            return;
        }

    }
}
