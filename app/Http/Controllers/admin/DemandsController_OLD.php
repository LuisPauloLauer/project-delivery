<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\mdDemandsFood;
use App\mdStores;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class DemandsController_OLD extends Controller
{
    public function viewOrders($status = null)
    {
        if(is_null($status)){
            abort(404,"Sorry, You can do this actions");
        }

        switch ($status) {
            case 'included':
                $statusType = 'included';
                break;
            case 'confirmed':
                $statusType = 'confirmed';
                break;
            case 'togodelivery':
                $statusType = 'togodelivery';
                break;
            case 'delivered':
                $statusType = 'delivered';
                break;
            default:
                abort(404,"Sorry, You can do this actions");
        }

        $isUserOfStoreSelected = false;

        if( (Gate::allows('isadmintotal')) || (Gate::allows('isadminedit')) ) {

            $DemandsFood = DB::select('select
                                                d.id as demand,
                                                d.store,
                                                d.user_site,
                                                d.type_deliver,
                                                d.type_payment,
                                                d.sub_total_price,
                                                d.tax_price,
                                                d.shipping_price,
                                                d.shipping_discount_price,
                                                d.insurance_price,
                                                d.handling_fee_price,
                                                d.total_amount,
                                                d.total_price,
                                                ROW_NUMBER() OVER (
                                                            PARTITION BY d.id
                                                      ORDER BY  d.id, i.id ASC) itens,
                                                (select count(demands_itens_food.id) from demands_itens_food
                                                            WHERE demands_itens_food.demand = d.id
                                                ) as total_itens,
                                                i.kit_id,
                                                i.product_id,
                                                i.kit_sub_itens,
                                                i.amount,
                                                i.observation
                                            FROM
                                              demands_food as d
                                            JOIN
                                              demands_itens_food as i
                                            ON
                                             d.id = i.demand
                                            JOIN
                                             status_demands_food as s
                                            ON
                                             s.id = d.status
                                            WHERE
                                               s.type = "'.$statusType.'"
                                            order by
                                             d.id,
                                             i.id');

            switch ($status) {
                case 'included':
                    $returnView = 'admin.demands.ordersIncluded';
                    break;
                case 'confirmed':
                    $returnView = 'admin.demands.ordersConfirmed';
                    break;
                case 'togodelivery':
                    $returnView = 'admin.demands.ordersTogoDelivery';
                    break;
                case 'delivered':
                    $returnView = 'admin.demands.ordersDelivered';
                    break;
                default:
                    abort(404,"Sorry, You can do this actions");
            }

            return view($returnView, [
                'listDemandsFood' => $DemandsFood
            ]);

        } else {

            $UserAuth = Auth::user();

            foreach(Session::get('StoresUserAdm') as $store){
                if($store['selected']){
                    $storeUserAdmId = $store['id'];
                }
            }

            $relUsersAdmByStore = User::pesqUserAdmByStore($storeUserAdmId);

            foreach($relUsersAdmByStore as $userAdmByStore){
                if($userAdmByStore->useradm ==  $UserAuth->id){
                    $isUserOfStoreSelected = true;
                }
            }

            if($isUserOfStoreSelected) {

                $DemandsFood = DB::select('select
                                                d.id as demand,
                                                d.store,
                                                d.user_site,
                                                d.type_deliver,
                                                d.type_payment,
                                                d.sub_total_price,
                                                d.tax_price,
                                                d.shipping_price,
                                                d.shipping_discount_price,
                                                d.insurance_price,
                                                d.handling_fee_price,
                                                d.total_amount,
                                                d.total_price,
                                                ROW_NUMBER() OVER (
                                                            PARTITION BY d.id
                                                      ORDER BY  d.id, i.id ASC) itens,
                                                (select count(demands_itens_food.id) from demands_itens_food
                                                            WHERE demands_itens_food.demand = d.id
                                                ) as total_itens,
                                                i.kit_id,
                                                i.product_id,
                                                i.kit_sub_itens,
                                                i.amount,
                                                i.observation
                                            FROM
                                              demands_food as d
                                            JOIN
                                              demands_itens_food as i
                                            ON
                                             d.id = i.demand
                                            JOIN
                                             status_demands_food as s
                                            ON
                                             s.id = d.status
                                            WHERE
                                               s.type = "'.$statusType.'"
                                               and d.store = ' . $storeUserAdmId . '
                                            order by
                                             d.id,
                                             i.id');

                switch ($status) {
                    case 'included':
                        $returnView = 'admin.demands.ordersIncluded';
                        break;
                    case 'confirmed':
                        $returnView = 'admin.demands.ordersConfirmed';
                        break;
                    case 'togodelivery':
                        $returnView = 'admin.demands.ordersTogoDelivery';
                        break;
                    case 'delivered':
                        $returnView = 'admin.demands.ordersDelivered';
                        break;
                    default:
                        abort(404,"Sorry, You can do this actions");
                }

                return view($returnView, [
                    'listDemandsFood' => $DemandsFood
                ]);

            } else {
                abort(404,"Sorry, You can do this actions");
            }
        }
    }

    public function ordersChangeStatusType(Request $request)
    {
        $isUserOfStoreSelected = false;
        $demandID = $request->demand_id;
        $statusType = $request->status_type;

        if( mdDemandsFood::where('id', $demandID)->where('status', '<>', 5)->exists() ){

            $demand = mdDemandsFood::findOrFail($demandID);

            if( (Gate::allows('isadmintotal')) || (Gate::allows('isadminedit')) ) {

                $demand->status = $statusType;
                if($demand->save()){
                    $responseDemand['success'] = true;
                    switch ($statusType) {
                        case 2:
                            $responseDemand['message'] = 'Pedido código: ('.$demandID.') atendido.';
                            break;
                        case 3:
                            $responseDemand['message'] = 'Pedido código: ('.$demandID.') despachado.';
                            break;
                        case 4:
                            $responseDemand['message'] = 'Pedido código: ('.$demandID.') Concluído.';
                            break;
                        case 5:
                            $responseDemand['message'] = 'Pedido código: ('.$demandID.') Cancelado.';
                            break;
                    }
                    echo json_encode($responseDemand);
                    return;
                } else {
                    $responseDemand['success'] = false;
                    switch ($statusType) {
                        case 2:
                            $responseDemand['message'] = 'Erro ao atender o pedido código: ('.$demandID.')!!!';
                            break;
                        case 3:
                            $responseDemand['message'] = 'Erro ao despachar o pedido código: ('.$demandID.')!!!';
                            break;
                        case 4:
                            $responseDemand['message'] = 'Erro ao concluir o pedido código: ('.$demandID.')!!!';
                            break;
                        case 5:
                            $responseDemand['message'] = 'Erro ao cancelar o pedido código: ('.$demandID.')!!!';
                            break;
                    }
                    echo json_encode($responseDemand);
                    return;
                }

            } else {

                $UserAuth = Auth::user();

                foreach(Session::get('StoresUserAdm') as $store){
                    if($store['selected']){
                        $storeUserAdmId = $store['id'];
                    }
                }

                $relUsersAdmByStore = User::pesqUserAdmByStore($storeUserAdmId);

                foreach($relUsersAdmByStore as $userAdmByStore){
                    if($userAdmByStore->useradm ==  $UserAuth->id){
                        $isUserOfStoreSelected = true;
                    }
                }

                if($isUserOfStoreSelected){

                    if($demand->store == $storeUserAdmId ){

                        $demand->status = $statusType;
                        if($demand->save()){
                            $responseDemand['success'] = true;
                            switch ($statusType) {
                                case 2:
                                    $responseDemand['message'] = 'Pedido código: ('.$demandID.') atendido.';
                                    break;
                                case 3:
                                    $responseDemand['message'] = 'Pedido código: ('.$demandID.') despachado.';
                                    break;
                                case 4:
                                    $responseDemand['message'] = 'Pedido código: ('.$demandID.') Concluído.';
                                    break;
                                case 5:
                                    $responseDemand['message'] = 'Pedido código: ('.$demandID.') Cancelado.';
                                    break;
                            }
                            echo json_encode($responseDemand);
                            return;
                        } else {
                            $responseDemand['success'] = false;
                            switch ($statusType) {
                                case 2:
                                    $responseDemand['message'] = 'Erro ao atender o pedido código: ('.$demandID.')!!!';
                                    break;
                                case 3:
                                    $responseDemand['message'] = 'Erro ao despachar o pedido código: ('.$demandID.')!!!';
                                    break;
                                case 4:
                                    $responseDemand['message'] = 'Erro ao concluir o pedido código: ('.$demandID.')!!!';
                                    break;
                                case 5:
                                    $responseDemand['message'] = 'Erro ao cancelar o pedido código: ('.$demandID.')!!!';
                                    break;
                            }
                            echo json_encode($responseDemand);
                            return;
                        }

                    } else {
                        $responseDemand['success'] = false;
                        $responseDemand['message'] = 'Pedido código: ('.$demandID.') não pertence a loja selecionada!!!';
                        echo json_encode($responseDemand);
                        return;
                    }

                } else {
                    abort(404,"Sorry, You can do this actions");
                }

            }

        } else {
            $responseDemand['success'] = false;
            $responseDemand['message'] = 'Pedido código: ('.$demandID.') não existe!!!';
            echo json_encode($responseDemand);
            return;
        }

    }

    public function ordersToPrint(mdDemandsFood $demand)
    {
        $isUserOfStoreSelected = false;

        if( mdDemandsFood::where('id', $demand->id)->where('status', '<>', 5 )->exists() ){

            if( (Gate::allows('isadmintotal')) || (Gate::allows('isadminedit')) ) {

                $DemandsFood = DB::select('select
                                                d.id as demand,
                                                d.store,
                                                d.user_site,
                                                d.type_deliver,
                                                d.type_payment,
                                                d.sub_total_price,
                                                d.tax_price,
                                                d.shipping_price,
                                                d.shipping_discount_price,
                                                d.insurance_price,
                                                d.handling_fee_price,
                                                d.total_amount,
                                                d.total_price,
                                                ROW_NUMBER() OVER (
                                                            PARTITION BY d.id
                                                      ORDER BY  d.id, i.id ASC) itens,
                                                (select count(demands_itens_food.id) from demands_itens_food
                                                            WHERE demands_itens_food.demand = d.id
                                                ) as total_itens,
                                                i.kit_id,
                                                i.product_id,
                                                i.kit_sub_itens,
                                                i.amount,
                                                i.observation
                                            FROM
                                              demands_food as d
                                            JOIN
                                              demands_itens_food as i
                                            ON
                                             d.id = i.demand
                                            JOIN
                                             status_demands_food as s
                                            ON
                                             s.id = d.status
                                            WHERE
                                               s.type <> "canceled"
                                               and d.id = '.$demand->id.'
                                            order by
                                             d.id,
                                             i.id');

                //return View::Make('admin.demands.ordersToPrint')->with('listDemandsFood', $DemandsFood);

                $fileName = 'pedidos.pdf';

                $mPDF = new Mpdf([
                    'margin_left'   => 10,
                    'margin_right'  => 10,
                    'margin_top'    => 15,
                    'margin_bottom' => 20,
                    'margin_header' => 10,
                    'margin_footer' => 10
                ]);

                $html = View::Make('admin.demands.ordersToPrint')->with('listDemandsFood', $DemandsFood);
                $html = $html->render();

                $mPDF->SetHeader('Loja: todas|Lista de Pedido|Página: {PAGENO}');
                $mPDF->SetFooter('Loja: todas');

                $styleSheet = file_get_contents(url('admin/node_modules/css/PDF_ordersToPrint.css'));
                $mPDF->WriteHTML($styleSheet,1);

                $mPDF->WriteHTML($html);
                $mPDF->Output($fileName, 'I');

            } else {

                $UserAuth = Auth::user();

                foreach(Session::get('StoresUserAdm') as $store){
                    if($store['selected']){
                        $storeUserAdmId = $store['id'];
                    }
                }

                $store = mdStores::where('id', $storeUserAdmId)->first();

                $relUsersAdmByStore = User::pesqUserAdmByStore($storeUserAdmId);

                foreach($relUsersAdmByStore as $userAdmByStore){
                    if($userAdmByStore->useradm ==  $UserAuth->id){
                        $isUserOfStoreSelected = true;
                    }
                }

                if($isUserOfStoreSelected){

                    if($demand->store == $storeUserAdmId ){

                        $DemandsFood = DB::select('select
                                                d.id as demand,
                                                d.store,
                                                d.user_site,
                                                d.type_deliver,
                                                d.type_payment,
                                                d.sub_total_price,
                                                d.tax_price,
                                                d.shipping_price,
                                                d.shipping_discount_price,
                                                d.insurance_price,
                                                d.handling_fee_price,
                                                d.total_amount,
                                                d.total_price,
                                                ROW_NUMBER() OVER (
                                                            PARTITION BY d.id
                                                      ORDER BY  d.id, i.id ASC) itens,
                                                (select count(demands_itens_food.id) from demands_itens_food
                                                            WHERE demands_itens_food.demand = d.id
                                                ) as total_itens,
                                                i.kit_id,
                                                i.product_id,
                                                i.kit_sub_itens,
                                                i.amount,
                                                i.observation
                                            FROM
                                              demands_food as d
                                            JOIN
                                              demands_itens_food as i
                                            ON
                                             d.id = i.demand
                                            JOIN
                                             status_demands_food as s
                                            ON
                                             s.id = d.status
                                            WHERE
                                               s.type <> "canceled"
                                               and d.id = '.$demand->id.'
                                               and d.store = '.$storeUserAdmId.'
                                            order by
                                             d.id,
                                             i.id');

                        //return View::Make('admin.demands.ordersToPrint')->with('listDemandsFood', $DemandsFood);

                        $fileName = 'pedidos.pdf';

                        $mPDF = new Mpdf([
                            'margin_left'   => 10,
                            'margin_right'  => 10,
                            'margin_top'    => 15,
                            'margin_bottom' => 20,
                            'margin_header' => 10,
                            'margin_footer' => 10
                        ]);

                        $html = View::Make('admin.demands.ordersToPrint')->with('listDemandsFood', $DemandsFood);
                        $html = $html->render();

                        $mPDF->SetHeader('Loja: '.$store->name.'|Lista de Pedido|Página: {PAGENO}');
                        $mPDF->SetFooter('Loja: '.$store->name);

                        $styleSheet = file_get_contents(url('admin/node_modules/css/PDF_ordersToPrint.css'));
                        $mPDF->WriteHTML($styleSheet,1);

                        $mPDF->WriteHTML($html);
                        $mPDF->Output($fileName, 'I');

                    } else {
                        abort(404,"Sorry, You can do this actions");
                    }

                } else {
                    abort(404,"Sorry, You can do this actions");
                }

            }

        } else {
            abort(404,"Sorry, You can do this actions");
        }

    }
}
