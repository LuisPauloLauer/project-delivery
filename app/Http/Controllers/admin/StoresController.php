<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\mdStores;
use App\mdSegments;
use App\mdCategoriesStore;
use App\mdRelCategoriesStore;
use App\mdAffiliates;
use App\mdCities;
use Illuminate\Http\Request;
use App\Library\FilesControl;
use App\Http\Requests\admin\StoresFormRequest;
use Illuminate\Support\Facades\Config;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Stores = mdStores::all();

        $pathImagens = FilesControl::getPathImages();

        return view('admin.stores.Stores',[
            'listStore'     =>  $Stores,
            'pathImagens'   =>  $pathImagens
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Affiliates = mdAffiliates::where('status', 'S')->get();
        $Segments = mdSegments::where('status', 'S')->get();
        $CategoryStore = mdCategoriesStore::where('status', 'S')->get();
        $pathFilesImages = FilesControl::getPathImages(true);

        return view('admin.stores.addStores',[
            'listAffiliates'    => $Affiliates,
            'listSegments'      => $Segments,
            'listCategoryStore' => $CategoryStore,
            'pathFilesImages'   => $pathFilesImages
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoresFormRequest $request)
    {
        $Store = New mdStores();

        $ConsultAffiliate = mdAffiliates::where('id', $request->affiliate)->exists();
        if($ConsultAffiliate){
            $Affiliate = mdAffiliates::where('id', $request->affiliate)->first();
        }else{
            return back()->with('error','Erro Afiliado ID: '.'('.$request->affiliate.')'.' não encontrado!!!');
        }

        $Store->affiliate = $Affiliate->id;

        $ConsultSegment = mdSegments::where('id', $request->segment)->exists();
        if($ConsultSegment){
            $Segment = mdSegments::where('id', $request->segment)->first();
        }else{
            return back()->with('error','Erro Segmento ID: '.'('.$request->segment.')'.' não encontrado!!!');
        }

        $Store->segment = $Segment->id;

        if(!isset($request->categorystore)){
            return back()->with('error','Erro Selecione pelo menos uma Categoria para a loja!!!');
        }

        $Store->name = $request->name;

        /*Validar*/
        $Store->zip_code = preg_replace('/\D+/', '', $request->zip_code);

        $Store->street = $request->street;
        $Store->number = $request->number;
        $Store->district = $request->district;
        $Store->complement = $request->complement;

        if (isset($request->ibge)) {
            if(mdCities::where('ibge', $request->ibge)->exists()){
                $City = mdCities::where('ibge', $request->ibge)->first();
                $Store->city = $City->id;
            }else{
                return back()->with('error','Erro Cidade não encontrada. Verifique o Cep!!!');
            }
        }
        else{
            return back()->with('error','Erro Cidade não encontrada. Verifique o Cep!!!');
        }

        $Store->fone1 = preg_replace('/\D+/', '', $request->fone1);
        $Store->fone2 = preg_replace('/\D+/', '', $request->fone2);
        $Store->email = $request->email;
        $Store->description = $request->description;

        if($Store->save()){

            /*Categories of Store*/
            if(isset($request->categorystore)){
                $CategoryStore = $request->categorystore;
                for ($i = 0; $i < count($CategoryStore); $i++){

                    $RelCategoryStore = New mdRelCategoriesStore();
                    $RelCategoryStore->category = $CategoryStore[$i];
                    $RelCategoryStore->store = $Store->id;
                    $RelCategoryStore->save();

                    unset($RelCategoryStore);
                }
            }

            // Upload of Images
            if(isset($request->imageCapaSave)){
                if(!is_null($request->imageCapaSave)){
                    if($request->hasFile('imageCapaInput')){
                        $imageSaveCapa  = $request->imageCapaSave;
                        $imageInputCapa = $request->file('imageCapaInput');

                        try {
                            $Store->path_image_capa = FilesControl::saveImage($imageSaveCapa, $imageInputCapa, 'stores/capa', $Store->id,2);
                        } catch (\Exception $exception) {
                            $Store->path_image_capa = null;
                            return back()->with('error','Erro Loja ID: ('.$Store->id.') '.$exception->getMessage());
                        }finally{
                            $Store->save();
                        }

                    }
                }
            }

            if(isset($request->imageLogoSave)){
                if(!is_null($request->imageLogoSave)){
                    if($request->hasFile('imageLogoInput')){
                        $imageSaveLogo  = $request->imageLogoSave;
                        $imageInputLogo = $request->file('imageLogoInput');

                        try {
                            $Store->path_image_logo = FilesControl::saveImage($imageSaveLogo, $imageInputLogo, 'stores/logo', $Store->id,1);
                        } catch (\Exception $exception) {
                            $Store->path_image_logo = null;
                            return back()->with('error','Erro Loja ID: ('.$Store->id.') '.$exception->getMessage());
                        }finally{
                            $Store->save();
                        }

                    }
                }
            }

            return back()->with('success','ID: ('.$Store->id.') Loja cadastrada com sucesso');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mdStores  $mdStores
     * @return \Illuminate\Http\Response
     */
    public function show(mdStores $mdStores)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mdStores  $mdStores
     * @return \Illuminate\Http\Response
     */
    public function edit(mdStores $store)
    {
        $Affiliate = mdAffiliates::where('id', $store->affiliate)->first();
        $Segment = mdSegments::where('id', $store->segment)->first();

        $CategoryStore = mdCategoriesStore::where('status', 'S')->get();
        $RelCategoryStore = mdRelCategoriesStore::where('store', $store->id)->get();
        $pathFilesImages = FilesControl::getPathImages(true);
        $pathImagens = FilesControl::getPathImages();

        return view('admin.stores.editStores',[
            'Store'                 => $store,
            'Affiliate'             => $Affiliate,
            'Segment'               => $Segment,
            'listCategoryStore'     => $CategoryStore,
            'listRelCategoryStore'  => $RelCategoryStore,
            'pathFilesImages'       => $pathFilesImages,
            'pathImagens'           => $pathImagens
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mdStores  $mdStores
     * @return \Illuminate\Http\Response
     */
    public function update(StoresFormRequest $request, mdStores $store)
    {
        if(!isset($request->categorystore)){
            return back()->with('error','Erro Selecione pelo menos uma Categoria para a loja!!!');
        }

        $store->alterStatusManual   = true;
        $store->status              = $request->status;
        $store->name                = $request->name;

        /*Validar*/
        $store->zip_code = preg_replace('/\D+/', '', $request->zip_code);

        $store->street = $request->street;
        $store->number = $request->number;
        $store->district = $request->district;
        $store->complement = $request->complement;

        if (isset($request->ibge)) {
            if(mdCities::where('ibge', $request->ibge)->exists()){
                $City = mdCities::where('ibge', $request->ibge)->first();
                $store->city = $City->id;
            }else{
                return back()->with('error','Erro Cidade não encontrada. Verifique o Cep!!!');
            }
        }
        else{
            return back()->with('error','Erro Cidade não encontrada. Verifique o Cep!!!');
        }

        $store->fone1 = preg_replace('/\D+/', '', $request->fone1);
        $store->fone2 = preg_replace('/\D+/', '', $request->fone2);
        $store->email = $request->email;
        $store->description = $request->description;

        // Upload of Images
        if(isset($request->imageCapaSave)){
            if(!is_null($request->imageCapaSave)){
                if($request->hasFile('imageCapaInput')){
                    $imageSaveCapa  = $request->imageCapaSave;
                    $imageInputCapa = $request->file('imageCapaInput');

                    if (!is_null($store->path_image_capa)) {
                        $path_img = storage_path('app/public/upload/images/stores/capa/' . $store->id);

                        if(FilesControl::deleteImage($path_img,$store->path_image_capa)){
                            try {
                                $store->path_image_capa = FilesControl::saveImage($imageSaveCapa, $imageInputCapa, 'stores/capa', $store->id,2);
                            } catch (\Exception $exception) {
                                $store->path_image_capa = null;
                                return back()->with('error','Erro Loja ID: ('.$store->id.') '.$exception->getMessage());
                            }
                        }else{
                            return back()->with('error','Erro Loja ID: ('.$store->id.'). Erro ao deletar a imagen de capa anterior!!!');
                        }
                    }else{
                        try {
                            $store->path_image_capa = FilesControl::saveImage($imageSaveCapa, $imageInputCapa, 'stores/capa', $store->id,2);
                        } catch (\Exception $exception) {
                            $store->path_image_capa = null;
                            return back()->with('error','Erro Loja ID: ('.$store->id.') '.$exception->getMessage());
                        }
                    }
                }
            }
        }

        if(isset($request->imageLogoSave)){
            if(!is_null($request->imageLogoSave)){
                if($request->hasFile('imageLogoInput')){
                    $imageSaveLogo  = $request->imageLogoSave;
                    $imageInputLogo = $request->file('imageLogoInput');

                    if (!is_null($store->path_image_logo)) {
                        $path_img = storage_path('app/public/upload/images/stores/logo/' . $store->id);

                        if(FilesControl::deleteImage($path_img,$store->path_image_logo)){
                            try {
                                $store->path_image_logo = FilesControl::saveImage($imageSaveLogo, $imageInputLogo, 'stores/logo', $store->id,1);
                            } catch (\Exception $exception) {
                                $store->path_image_logo = null;
                                return back()->with('error','Erro Loja ID: ('.$store->id.') '.$exception->getMessage());
                            }
                        }else{
                            return back()->with('error','Erro Loja ID: ('.$store->id.'). Erro ao deletar a imagen de logo anterior!!!');
                        }
                    }else{
                        try {
                            $store->path_image_logo = FilesControl::saveImage($imageSaveLogo, $imageInputLogo, 'stores/logo', $store->id,1);
                        } catch (\Exception $exception) {
                            $store->path_image_logo = null;
                            return back()->with('error','Erro Loja ID: ('.$store->id.') '.$exception->getMessage());
                        }
                    }
                }
            }
        }

        if($store->save()) {

            //Categories of store
            $store->allCategoriesByStore()->sync($request->categorystore);

            return back()->with('success','ID: '.$store->id.' Loja alterada com sucesso');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mdStores  $mdStores
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(mdStores::where('id', $request->Store_id)->exists()) {
            $store = mdStores::findOrFail($request->Store_id);

            $capaPathObject = storage_path('app/public/upload/images/stores/capa/' . $store->id);
            $logoPathObject = storage_path('app/public/upload/images/stores/logo/' . $store->id);

            try {
                if ($store->delete()) {
                    if (is_dir($capaPathObject) || is_dir($logoPathObject)) {
                        if ( FilesControl::deleteImageAndPath($capaPathObject) && FilesControl::deleteImageAndPath($logoPathObject) ) {
                            return back()->with('success', 'ID: ' . '(' . $store->id . ')' . ' Loja e imagens foram deletados com sucesso');
                        } else {
                            return back()->with('error', 'Erro ID: ' . '(' . $store->id . ')' . ' Loja deletada, mas imagens não foram deletadas!!!');
                        }
                    } else {
                        return back()->with('success', 'ID: ' . '(' . $store->id . ')' . ' Loja deletada com sucesso');
                    }
                } else {
                    return back()->with('error', 'Erro ID: ' . '(' . $store->id . ')' . ' Loja não foi deletada!!!');
                }
            } catch (\Exception $exception) {
                if($exception->getCode()==23000)
                    return back()->with('error', 'Erro: (23000) ID: '.'('. $store->id .')'.
                        ' Loja possuí registros filhos verifique as tabelas dependentes!!!');
            }
        }else{
            return back()->with('error', 'Erro ID: '.'('. $request->Store_id .')'.' Loja não existe!!!');
        }
    }

    public function changeStatus(Request $request)
    {
        $objectID       = $request->object_id;
        $objectStatus   = $request->object_status;

        if(mdStores::where('id', $objectID)->exists()) {

            $store = mdStores::where('id', $objectID)->first();

            $store->alterStatusManual   = true;
            $store->status              = $objectStatus;

            if($store->save()){
                $responseObject['success'] = true;
                if(strtoupper($objectStatus) == 'S'){
                    $responseObject['message'] = 'Loja ID ('.$objectID.') habilitada';
                } else {
                    $responseObject['message'] = 'Loja ID ('.$objectID.') desabilitada';
                }

                unset($store);
                echo json_encode($responseObject);
                return;
            } else {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Loja ID ('.$objectID.') erro ao alterar o status!';

                unset($store);
                echo json_encode($responseObject);
                return;
            }

        } else {
            $responseObject['success'] = false;
            $responseObject['message'] = 'Loja ID ('.$objectID.') não encontrado!';
            echo json_encode($responseObject);
            return;
        }

    }
}
