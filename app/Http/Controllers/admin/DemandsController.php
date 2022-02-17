<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Library\GeneralLibrary;
use App\mdDemandsFood;
use App\mdStatusDemandsFood;
use App\mdStores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class DemandsController extends Controller
{
    //Demands controller
    private $generalLibrary;

    public function __construct()
    {
        $this->generalLibrary = new GeneralLibrary();
    }

    function __destruct()
    {
        unset($this->generalLibrary);
    }

    public function viewOrders($status = null)
    {
        if(is_null($status)){
            abort(404,"Sorry, You can not do this actions");
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
                abort(404,"Sorry, You can not do this actions");
        }

        if ($this->generalLibrary->isUserOfStoreSelected()) {

            $APP_URL = env('APP_URL').'/orders/';
            $statusDemand = mdStatusDemandsFood::where('type', $statusType)->first();

            $DemandsFood = DB::select('select
												tt.demand,
												tt.datetime,
                                                tt.store,
                                                tt.user_site_id,
                                                tt.user_site_name,
												tt.user_site_fone,
												tt.user_site_email,
												tt.company_name,
												tt.building_name,
                                                tt.type_deliver,
                                                tt.type_payment,
                                                tt.sub_total_price,
                                                tt.tax_price,
                                                tt.shipping_price,
                                                tt.shipping_discount_price,
                                                tt.insurance_price,
                                                tt.handling_fee_price,
                                                tt.percentage_discount,
                                                tt.value_discount,
                                                tt.total_amount,
                                                tt.total_price,
                                                tt.money_change,
                                                tt.id_item,
                                                @row_number := CASE WHEN @item_1 = tt.demand THEN
													@row_number + 1
												ELSE
													1
												END AS itens,
												@item_1:=tt.demand item_id_RN,
                                                tt.total_itens,
                                                tt.kit_id,
                                                tt.product_id,
                                                tt.kit_sub_itens,
                                                tt.amount,
                                                tt.observation
                                            from
                                                (select
                                                    d.id as demand,
                                                    d.created_at as datetime,
                                                    d.store,
                                                    d.user_site as user_site_id,
                                                    u.name as user_site_name,
                                                    u.fone as user_site_fone,
                                                    u.email as user_site_email,
                                                    bu.company_name,
                                                    bu.building_name,
                                                    d.type_deliver,
                                                    d.type_payment,
                                                    d.sub_total_price,
                                                    d.tax_price,
                                                    d.shipping_price,
                                                    d.shipping_discount_price,
                                                    d.insurance_price,
                                                    d.handling_fee_price,
                                                    d.percentage_discount,
                                                    d.value_discount,
                                                    d.total_amount,
                                                    d.total_price,
                                                    d.money_change,
                                                    i.id as id_item,
                                                    (select count(demands_itens_food.id) from demands_itens_food
                                                                WHERE demands_itens_food.demand = d.id
                                                    ) as total_itens,
                                                    i.kit_id,
                                                    i.product_id,
                                                    i.kit_sub_itens,
                                                    i.amount,
                                                    i.observation
                                                FROM
                                                  demands_food as d,
                                                  demands_itens_food as i,
                                                  status_demands_food as s,
                                                  userssite as u,
                                                  universitybuildings as bu
                                                WHERE
                                                   d.id = i.demand
                                                   and s.id = d.status
                                                   and u.id = d.user_site
                                                   and bu.id = u.universitybuilding
                                                   and s.type = "' . $statusType . '"
                                                   and d.store = ' . $this->generalLibrary->storeSelectedByUser() . '
                                                order by
                                                 d.id,
                                                 i.id	) as tt,
                                                 (SELECT @item_1:=0,@row_number:=0) as rn
                                             order by
												tt.demand,
                                                tt.id_item');

            switch ($status) {
                case 'included':
                    $txtStatus = 'Incluídos';
                    break;
                case 'confirmed':
                    $txtStatus = 'Preparando';
                    break;
                case 'togodelivery':
                    $txtStatus = 'Entregando';
                    break;
                case 'delivered':
                    $txtStatus = 'Prontos';
                    break;
                default:
                    abort(404, "Sorry, You can do this actions");
            }

            return view('admin.demands.orders', [
                'APP_URL'           => $APP_URL,
                'txtStatus'         => $txtStatus,
                'statusDemand'      => $statusDemand,
                'listDemandsFood'   => $DemandsFood
            ]);

        } else {
            abort(404,"Sorry, You can not do this actions");
        }
    }

    public function ordersChangeStatusType(Request $request)
    {
        if(is_array($request->demands)){
            $demands = $request->demands;
        } else {
            $demands = mdDemandsFood::arrayOfDemands($request->demands);
        }
        $statusType = $request->status_type;
        $oldStatusType = $request->old_status_type;
        $i = 0;

        if ($this->generalLibrary->isUserOfStoreSelected()) {
            foreach ($demands as $demand){
                if (mdDemandsFood::where('id', $demand['demand_id'])->where('status', '<>', 5)->where('status', $oldStatusType)->exists()) {
                    $demand = mdDemandsFood::findOrFail($demand['demand_id']);
                    if ($demand->store == $this->generalLibrary->storeSelectedByUser()) {
                        $demand->status = $statusType;
                        if ($demand->save()) {
                            $responseDemand[$i]['success'] = true;
                            switch ($statusType) {
                                case 2:
                                    $responseDemand[$i]['message'] = 'Pedido código: (' . $demand->id . ') atendido.';
                                    break;
                                case 3:
                                    $responseDemand[$i]['message'] = 'Pedido código: (' . $demand->id . ') despachado.';
                                    break;
                                case 4:
                                    $responseDemand[$i]['message'] = 'Pedido código: (' . $demand->id . ') Concluído.';
                                    break;
                                case 5:
                                    $responseDemand[$i]['message'] = 'Pedido código: (' . $demand->id . ') Cancelado.';
                                    break;
                            }
                        } else {
                            $responseDemand[$i]['success'] = false;
                            switch ($statusType) {
                                case 2:
                                    $responseDemand[$i]['message'] = 'Erro ao atender o pedido código: (' . $demand->id . ')!!!';
                                    break;
                                case 3:
                                    $responseDemand[$i]['message'] = 'Erro ao despachar o pedido código: (' . $demand->id . ')!!!';
                                    break;
                                case 4:
                                    $responseDemand[$i]['message'] = 'Erro ao concluir o pedido código: (' . $demand->id . ')!!!';
                                    break;
                                case 5:
                                    $responseDemand[$i]['message'] = 'Erro ao cancelar o pedido código: (' . $demand->id . ')!!!';
                                    break;
                            }
                        }
                    } else {
                        $responseDemand[$i]['success'] = false;
                        $responseDemand[$i]['message'] = 'Pedido código: (' . $demand->id . ') não pertence a loja selecionada!!!';
                    }
                } else {
                    $responseDemand[$i]['success'] = false;
                    $responseDemand[$i]['message'] = 'Pedido código: (' . $demand['demand_id'] . ') não existe!!!';
                }
                $i++;
            }
            echo json_encode($responseDemand);
            return;
        } else {
            $responseDemand[0]['success'] = false;
            $responseDemand[0]['message'] = 'Usuário não pertence à loja!!!';
            echo json_encode($responseDemand);
            return;
        }
    }

    public function ordersToPrint($demands)
    {
        $parDemands = $demands;
        $demands = mdDemandsFood::arrayOfDemands($demands);

        if ($this->generalLibrary->isUserOfStoreSelected()) {
            foreach ($demands as $demand){
                if (!mdDemandsFood::where('id', $demand['demand_id'])->where('status', '<>', 5)->exists()) {
                    $responseDemand['success'] = false;
                    $responseDemand['message'] = 'Pedido código: (' . $demand['demand_id'] . ') não existe!!!';
                    echo json_encode($responseDemand);
                    return;
                }
                if ($demand['demand_store'] !== $this->generalLibrary->storeSelectedByUser()) {
                    $responseDemand['success'] = false;
                    $responseDemand['message'] = 'Pedido código: (' . $demand['demand_id']. ') não pertence a loja selecionada!!!';
                    echo json_encode($responseDemand);
                    return;
                }
            }
            $store = mdStores::where('id', $this->generalLibrary->storeSelectedByUser())->first();

            $DemandsFood = DB::select('select
												tt.demand,
												tt.datetime,
                                                tt.store,
                                                tt.user_site_id,
                                                tt.user_site_name,
												tt.user_site_fone,
												tt.user_site_email,
												tt.company_name,
												tt.building_name,
                                                tt.type_deliver,
                                                tt.type_payment,
                                                tt.sub_total_price,
                                                tt.tax_price,
                                                tt.shipping_price,
                                                tt.shipping_discount_price,
                                                tt.insurance_price,
                                                tt.handling_fee_price,
                                                tt.percentage_discount,
                                                tt.value_discount,
                                                tt.total_amount,
                                                tt.total_price,
                                                tt.money_change,
                                                tt.id_item,
                                                @row_number := CASE WHEN @item_1 = tt.demand THEN
													@row_number + 1
												ELSE
													1
												END AS itens,
												@item_1:=tt.demand item_id_RN,
                                                tt.total_itens,
                                                tt.kit_id,
                                                tt.product_id,
                                                tt.kit_sub_itens,
                                                tt.amount,
                                                tt.observation
                                            from
                                                (select
                                                    d.id as demand,
                                                    d.created_at as datetime,
                                                    d.store,
                                                    d.user_site as user_site_id,
                                                    u.name as user_site_name,
                                                    u.fone as user_site_fone,
                                                    u.email as user_site_email,
                                                    bu.company_name,
                                                    bu.building_name,
                                                    d.type_deliver,
                                                    d.type_payment,
                                                    d.sub_total_price,
                                                    d.tax_price,
                                                    d.shipping_price,
                                                    d.shipping_discount_price,
                                                    d.insurance_price,
                                                    d.handling_fee_price,
                                                    d.percentage_discount,
                                                    d.value_discount,
                                                    d.total_amount,
                                                    d.total_price,
                                                    d.money_change,
                                                    i.id as id_item,
                                                    (select count(demands_itens_food.id) from demands_itens_food
                                                                WHERE demands_itens_food.demand = d.id
                                                    ) as total_itens,
                                                    i.kit_id,
                                                    i.product_id,
                                                    i.kit_sub_itens,
                                                    i.amount,
                                                    i.observation
                                                FROM
                                                  demands_food as d,
                                                  demands_itens_food as i,
                                                  status_demands_food as s,
                                                  userssite as u,
                                                  universitybuildings as bu
                                                WHERE
                                                   d.id = i.demand
                                                   and s.id = d.status
                                                   and u.id = d.user_site
                                                   and bu.id = u.universitybuilding
                                                   and s.type <> "canceled"
                                                   and d.id in ('.$parDemands.')
                                                   and d.store = ' . $store->id . '
                                                order by
                                                 d.id,
                                                 i.id	) as tt,
                                                 (SELECT @item_1:=0,@row_number:=0) as rn
                                             order by
												tt.demand,
                                                tt.id_item');

            //return View::Make('admin.demands.ordersToPrint')->with('listDemandsFood', $DemandsFood);

            $fileName = 'pedidos.pdf';

            $mPDF = new Mpdf([
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 15,
                'margin_bottom' => 20,
                'margin_header' => 10,
                'margin_footer' => 10
            ]);

            $html = View::Make('admin.demands.ordersToPrint')->with('listDemandsFood', $DemandsFood);
            $html = $html->render();

            $mPDF->SetHeader('Loja: ' . $store->name . '|Lista de Pedido|Página: {PAGENO}');
            $mPDF->SetFooter('Loja: ' . $store->name);

            $styleSheet = file_get_contents(url('admin/node_modules/css/PDF_ordersToPrint.css'));
            $mPDF->WriteHTML($styleSheet, 1);

            $mPDF->WriteHTML($html);
            $mPDF->Output($fileName, 'I');

        } else {
            $responseDemand['success'] = false;
            $responseDemand['message'] = 'Usuário não pertence à loja!!!';
            echo json_encode($responseDemand);
            return;
        }
    }
}
