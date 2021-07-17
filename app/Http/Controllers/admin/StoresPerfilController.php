<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Library\FilesControl;
use App\Library\GeneralLibrary;
use App\mdCities;
use App\mdDaysOfWeek;
use App\mdDeliveryStoreTimes;
use App\mdStores;
use App\mdStoresPayment;
use App\mdStoresPaymentKeys;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StoresPerfilController extends Controller
{
    private $generalLibrary;

    public function __construct()
    {
        $this->generalLibrary = new GeneralLibrary();
    }

    function __destruct() {
        unset($this->generalLibrary);
    }

    public function viewStorePerfil()
    {

        if($this->generalLibrary->isUserOfStoreSelected()){

            $Store = mdStores::where('id', $this->generalLibrary->storeSelectedByUser())->first();
            $storePayment = mdStoresPayment::where('store', $Store->id)->get();
            $daysOfWeek = mdDaysOfWeek::where('language', 'PT-BR')->orderBy('sequence', 'asc')->get();
            $storeTime = mdDeliveryStoreTimes::where('store', $Store->id)->get();
            $pathFilesImages = FilesControl::getPathImages(true);
            $pathImagens = FilesControl::getPathImages();

            return view('admin.stores.editStorePerfil',[
                'Store'                 => $Store,
                'listStorePayment'      => $storePayment,
                'listdaysOfWeek'        => $daysOfWeek,
                'listStoretime'         => $storeTime,
                'pathFilesImages'       => $pathFilesImages,
                'pathImagens'           => $pathImagens
            ]);

        } else {
            abort(404,"Sorry, You can do this actions");
        }
    }

    public function changeActiveStore(Request $request)
    {
        $objectID       = $request->object_id;
        $objectStatus   = $request->object_status;

        if(mdStores::where('id', $objectID)->exists()) {

            $store = mdStores::where('id', $objectID)->first();

            if ($store->id != $this->generalLibrary->storeSelectedByUser(true)) {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Usuário não pertence à loja selecionada!';
                echo json_encode($responseObject);
                return;
            }

            $store->active_store_site = $objectStatus;

            if($store->save()){
                $responseObject['success'] = true;
                if(strtoupper($objectStatus) == 'S'){
                    $responseObject['message'] = 'Loja id ('.$objectID.') habilitada para venda';
                } else {
                    $responseObject['message'] = 'Loja id ('.$objectID.') desabilitada para venda';
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

    public function updateStorePerfilDados(Request $request, mdStores $store)
    {
        if($this->generalLibrary->isUserOfStoreSelected()){

            $store->name = $request->name;
            $store->fone_store_site = preg_replace('/\D+/', '', $request->fone_store_site);
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

            if (is_null($request->minimum_order)) {
                $store->minimum_order = 0;
            } else {
                $store->minimum_order = $request->minimum_order;
            }

            if ($store->save()) {

                if(isset($request->imageLogoSave)){
                    if(!is_null($request->imageLogoSave)){
                        if($request->hasFile('imageLogoInput')){
                            $dataStoresUserAdm = Session::get('StoresUserAdm');

                            for ($i = 0; $i < count($dataStoresUserAdm); $i++) {
                                if ($dataStoresUserAdm[$i]['selected']) {
                                    $dataStoresUserAdm[$i]['image_logo'] = $store->path_image_logo;
                                }
                            }

                            $request->session()->put('StoresUserAdm', $dataStoresUserAdm);
                        }
                    }
                }

                return back()->with('success', 'ID: ' . $store->id . ' Nome: ' . $store->name . ' (DADOS) alterados com sucesso');

            }
        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    public function updateStorePerfilEndereco(Request $request, mdStores $store)
    {
        if($this->generalLibrary->isUserOfStoreSelected()){

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

            if ($store->save()) {

                return back()->with('success', 'ID: ' . $store->id . ' Nome: ' . $store->name . ' (ENDEREÇO) alterados com sucesso');

            }

        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    public function updateStorePerfilTimeDelivery(Request $request, mdStores $store)
    {
        if($this->generalLibrary->isUserOfStoreSelected()){

            $daysOfWeek = mdDaysOfWeek::where('language', 'PT-BR')->orderBy('sequence', 'asc')->get();

            foreach($daysOfWeek as $day){

                $ckbDay = 'ckb_'.$day->day;

                if($request->$ckbDay){

                    $txtp1Ini = 'txt_p1_ini_'.$day->day;
                    $txtp1End = 'txt_p1_end_'.$day->day;
                    $txtp2Ini = 'txt_p2_ini_'.$day->day;
                    $txtp2End = 'txt_p2_end_'.$day->day;



                    if( (isset($request->$txtp1Ini)) && (isset($request->$txtp2Ini)) ){

                        $datap1Ini = $request->$txtp1Ini;
                        $datap1End = $request->$txtp1End;
                        $datap2Ini = $request->$txtp2Ini;
                        $datap2End = $request->$txtp2End;

                    } else if (isset($request->$txtp1Ini)){

                        $datap1Ini = $request->$txtp1Ini;
                        $datap1End = $request->$txtp1End;
                        $datap2Ini = 0;
                        $datap2End = 0;

                    }

                    if (mdDeliveryStoreTimes::where('store', $store->id)->where('day', $day->id)->exists()) {

                        $deliveryTime = mdDeliveryStoreTimes::where('store', $store->id)->where('day', $day->id)->first();

                        $deliveryTime->status       = "S";
                        $deliveryTime->periodo1_ini = $datap1Ini;
                        $deliveryTime->periodo1_end = $datap1End;
                        $deliveryTime->periodo2_ini = $datap2Ini;
                        $deliveryTime->periodo2_end = $datap2End;

                        if (!$deliveryTime->save()) {
                            return back()->with('error', 'Erro ao salvar Horários da loja!!!');
                        }

                    } else {

                        $deliveryTime = new mdDeliveryStoreTimes();

                        $deliveryTime->status       = "S";
                        $deliveryTime->store        = $store->id;
                        $deliveryTime->day          = $day->id;
                        $deliveryTime->periodo1_ini = $datap1Ini;
                        $deliveryTime->periodo1_end = $datap1End;
                        $deliveryTime->periodo2_ini = $datap2Ini;
                        $deliveryTime->periodo2_end = $datap2End;

                        if (!$deliveryTime->save()) {
                            unset($deliveryTime);
                            return back()->with('error', 'Erro ao salvar Horários da loja!!!');
                        }

                        unset($deliveryTime);
                    }

                } else {

                    if (mdDeliveryStoreTimes::where('store', $store->id)->where('day', $day->id)->exists()) {

                        $deliveryTime = mdDeliveryStoreTimes::where('store', $store->id)->where('day', $day->id)->first();

                        $deliveryTime->status       = "N";

                        if (!$deliveryTime->save()) {
                            return back()->with('error', 'Erro ao salvar Horários da loja!!!');
                        }

                    } else {

                        $deliveryTime = new mdDeliveryStoreTimes();

                        $deliveryTime->status       = "N";
                        $deliveryTime->store        = $store->id;
                        $deliveryTime->day          = $day->id;
                        $deliveryTime->periodo1_ini = 0;
                        $deliveryTime->periodo1_end = 0;
                        $deliveryTime->periodo2_ini = 0;
                        $deliveryTime->periodo2_end = 0;

                        if (!$deliveryTime->save()) {
                            unset($deliveryTime);
                            return back()->with('error', 'Erro ao salvar Horários da loja!!!');
                        }

                        unset($deliveryTime);
                    }

                }
            }

            return back()->with('success', 'ID: ' . $store->id . ' Nome: ' . $store->name . ' (Horários) alterados com sucesso');

        } else {
            abort(404, "Sorry, You can do this actions");
        }
    }

    public function updateStorePerfilPayment(Request $request, mdStores $store)
    {
        if($this->generalLibrary->isUserOfStoreSelected()){

            $typePayment        = null;
            $nPayments          = 0;

            if (!is_null($request->moneystore)) {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'money',
                    "typePaymentOrigin"     => 'money',
                    "typePaymentName"       => 'money',
                    "typePaymentFlag"       => 'money',
                    "status"                => 'S'
                );
                $nPayments++;
            } else {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'money',
                    "typePaymentOrigin"     => 'money',
                    "typePaymentName"       => 'money',
                    "typePaymentFlag"       => 'money',
                    "status"                => 'N'
                );
                $nPayments++;
            }

            if (!is_null($request->debitmastercardstore)) {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'machine',
                    "typePaymentOrigin"     => 'card',
                    "typePaymentName"       => 'debit',
                    "typePaymentFlag"       => 'mastercard',
                    "status"                => 'S'
                );
                $nPayments++;
            } else {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'machine',
                    "typePaymentOrigin"     => 'card',
                    "typePaymentName"       => 'debit',
                    "typePaymentFlag"       => 'mastercard',
                    "status"                => 'N'
                );
                $nPayments++;
            }

            if (!is_null($request->debitvisastore)) {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'machine',
                    "typePaymentOrigin"     => 'card',
                    "typePaymentName"       => 'debit',
                    "typePaymentFlag"       => 'visa',
                    "status"                => 'S'
                );
                $nPayments++;
            } else {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'machine',
                    "typePaymentOrigin"     => 'card',
                    "typePaymentName"       => 'debit',
                    "typePaymentFlag"       => 'visa',
                    "status"                => 'N'
                );
                $nPayments++;
            }

            if (!is_null($request->debitelostore)) {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'machine',
                    "typePaymentOrigin"     => 'card',
                    "typePaymentName"       => 'debit',
                    "typePaymentFlag"       => 'elo',
                    "status"                => 'S'
                );
                $nPayments++;
            } else {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'machine',
                    "typePaymentOrigin"     => 'card',
                    "typePaymentName"       => 'debit',
                    "typePaymentFlag"       => 'elo',
                    "status"                => 'N'
                );
                $nPayments++;
            }

            if (!is_null($request->creditmastercardstore)) {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'machine',
                    "typePaymentOrigin"     => 'card',
                    "typePaymentName"       => 'credit',
                    "typePaymentFlag"       => 'mastercard',
                    "status"                => 'S'
                );
                $nPayments++;
            } else {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'machine',
                    "typePaymentOrigin"     => 'card',
                    "typePaymentName"       => 'credit',
                    "typePaymentFlag"       => 'mastercard',
                    "status"                => 'N'
                );
                $nPayments++;
            }

            if (!is_null($request->creditvisastore)) {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'machine',
                    "typePaymentOrigin"     => 'card',
                    "typePaymentName"       => 'credit',
                    "typePaymentFlag"       => 'visa',
                    "status"                => 'S'
                );
                $nPayments++;
            } else {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'store',
                    "typePaymentSystem"     => 'machine',
                    "typePaymentOrigin"     => 'card',
                    "typePaymentName"       => 'credit',
                    "typePaymentFlag"       => 'visa',
                    "status"                => 'N'
                );
                $nPayments++;
            }

            if (!is_null($request->paypalapp)) {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'app',
                    "typePaymentSystem"     => 'paypal',
                    "typePaymentOrigin"     => 'paypal',
                    "typePaymentName"       => 'paypal',
                    "typePaymentFlag"       => 'paypal',
                    "status"                => 'S'
                );
            } else {
                $typePayment[$nPayments] = array(
                    "typePaymentLocal"      => 'app',
                    "typePaymentSystem"     => 'paypal',
                    "typePaymentOrigin"     => 'paypal',
                    "typePaymentName"       => 'paypal',
                    "typePaymentFlag"       => 'paypal',
                    "status"                => 'N'
                );
            }

            for($i = 0; $i <=$nPayments; $i++){

                if(!mdStoresPayment::where('store', $store->id)->
                                     where('type_payment_local', $typePayment[$i]['typePaymentLocal'])->
                                     where('type_payment_system', $typePayment[$i]['typePaymentSystem'])->
                                     where('type_payment_origin', $typePayment[$i]['typePaymentOrigin'])->
                                     where('type_payment_name', $typePayment[$i]['typePaymentName'])->
                                     where('type_payment_flag', $typePayment[$i]['typePaymentFlag'])->exists()){

                    $storePayment = new mdStoresPayment();

                    $storePayment->status               = $typePayment[$i]['status'];
                    $storePayment->store                = $store->id;
                    $storePayment->type_payment_local   = $typePayment[$i]['typePaymentLocal'];
                    $storePayment->type_payment_system  = $typePayment[$i]['typePaymentSystem'];
                    $storePayment->type_payment_origin  = $typePayment[$i]['typePaymentOrigin'];
                    $storePayment->type_payment_name    = $typePayment[$i]['typePaymentName'];
                    $storePayment->type_payment_flag    = $typePayment[$i]['typePaymentFlag'];

                    if(!$storePayment->save()){
                        return back()->with('error','Erro ao salvar tipos de pagamentos!!!');
                    } else {
                        if($typePayment[$i]['typePaymentSystem'] == 'paypal'){

                            $storePaymentKeysFind = mdStoresPayment::find($storePayment->id)->pesqPaymentKeys;

                            if(count($storePaymentKeysFind) == 0 ){

                                $storePaymentKeys = new mdStoresPaymentKeys();

                                $storePaymentKeys->type_payment_system  = $storePayment->id;
                                $storePaymentKeys->client_id            = $request->name;
                                $storePaymentKeys->client_secret        = $request->name;

                                $storePaymentKeys->save();
                            }

                        }
                    }

                    unset($storePayment);

                } else {

                    $storePayment     =    mdStoresPayment::where('store', $store->id)->
                                                            where('type_payment_local', $typePayment[$i]['typePaymentLocal'])->
                                                            where('type_payment_system', $typePayment[$i]['typePaymentSystem'])->
                                                            where('type_payment_origin', $typePayment[$i]['typePaymentOrigin'])->
                                                            where('type_payment_name', $typePayment[$i]['typePaymentName'])->
                                                            where('type_payment_flag', $typePayment[$i]['typePaymentFlag'])->first();

                    $storePayment->status               = $typePayment[$i]['status'];
                    $storePayment->store                = $store->id;
                    $storePayment->type_payment_local   = $typePayment[$i]['typePaymentLocal'];
                    $storePayment->type_payment_system  = $typePayment[$i]['typePaymentSystem'];
                    $storePayment->type_payment_origin  = $typePayment[$i]['typePaymentOrigin'];
                    $storePayment->type_payment_name    = $typePayment[$i]['typePaymentName'];
                    $storePayment->type_payment_flag    = $typePayment[$i]['typePaymentFlag'];

                    if(!$storePayment->save()){
                        return back()->with('error','Erro ao salvar tipos de pagamentos!!!');
                    } else {
                        if($typePayment[$i]['typePaymentSystem'] == 'paypal'){

                            $storePaymentKeysFind = mdStoresPayment::find($storePayment->id)->pesqPaymentKeys;

                            if(count($storePaymentKeysFind) == 0 ){

                                $storePaymentKeys = new mdStoresPaymentKeys();

                                $storePaymentKeys->type_payment_system  = $storePayment->id;
                                $storePaymentKeys->client_id            = $request->name;
                                $storePaymentKeys->client_secret        = $request->name;

                                $storePaymentKeys->save();
                            }

                        }
                    }

                    unset($storePayment);
                }
            }

            return back()->with('success', 'ID: ' . $store->id . ' Nome: ' . $store->name . ' (PAGAMENTO) alterados com sucesso');

        }
    }
}
