@extends('admin.layout_master.admin_design')

@section('Stylescss')
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/icheck-bootstrap/icheck-bootstrap.css') }}">
    <style>
        th.thead-demand{
            border-top: 0;
        }
        .breadcrumb-item-button{
            padding-right: .5rem;
        }
        .breadcrumb-item-button+.breadcrumb-item-button{
            padding-right: 0;
        }
        @media only screen and (max-width: 320px){
            .breadcrumb-item-button{
                padding-bottom: .5rem;
            }
            .breadcrumb-item-button+.breadcrumb-item-button{
                padding-bottom: 0;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Modal Cancel -->
    <div id="idconfirmCancelDemandModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Confirma Cancelar?</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 align="center" style="margin:0;">Pedido ID: <span id="idSpanCancelDemandModal"></span></h4>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="cancelDemandModal" id="idCancelDemandModal" value="">
                    <button type="button" class="btn btn-danger"  id="idOkCancelModal">Sim</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal Cancel -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-1">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><h5 class="text-bold">Pedidos</h5></li>
                            <li class="breadcrumb-item"><h5>{{$txtStatus}}</h5></li>

                        </ol>
                    </div>
                </div>
                @if($listDemandsFood && $statusDemand->type <> 'delivered')
                    <div class="row mb-1">
                        <div class="col">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item-button"><button id="idBtnGroupAtend" class="btn btn-success disabled" disabled><span class="glyphicon fas fa-check"></span> Atender pedidos</button></li>
                                <li class="breadcrumb-item-button"><button id='idBtnGroupPrint' class="btn btn-secondary disabled" disabled><span class="glyphicon fas fa-print"></span> Imprimir pedidos</button></li>
                            </ol>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- /.Content Header (Page header) -->

        <!-- Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php $atualDemand = 'null' ?>
                        @foreach($listDemandsFood as $DemandFood)
                            @if( $atualDemand <> $DemandFood->demand )
                                <div class="card card-outline card-danger collapsed-card">
                                    <div class="card-header">
                                        <div>
                                            <div class="table-responsive">
                                                <table class="table m-0">
                                                    <thead>
                                                    <tr>
                                                        <th class="thead-demand">Pedido</th>
                                                        <th class="thead-demand">Data</th>
                                                        <th class="thead-demand">Empresa</th>
                                                        <th class="thead-demand">Prédio</th>
                                                        <th class="thead-demand">Cliente</th>
                                                        <th class="thead-demand">Fone</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>{{$DemandFood->demand}}</td>
                                                        <td>{{date( "d/m/Y H:i", strtotime($DemandFood->datetime))}}</td>
                                                        <td>{{$DemandFood->company_name}}</td>
                                                        <td>{{$DemandFood->building_name}}</td>
                                                        <td>{{$DemandFood->user_site_name}}</td>
                                                        <td class="fone">{{$DemandFood->user_site_fone}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table m-0">
                                                <thead>
                                                <tr>
                                                    <th class="thead-demand">Item</th>
                                                    <th class="thead-demand">Código</th>
                                                    <th class="thead-demand">Observação</th>
                                                    <th class="thead-demand">Quantidade</th>
                                                    <th class="thead-demand">Valor unitário</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($listDemandsFood as $itemDemandFood)
                                                    @if($itemDemandFood->demand == $DemandFood->demand )
                                                        <tr>
                                                            <td>
                                                                {{((!is_null($itemDemandFood->kit_id) ? \App\mdKits::find($itemDemandFood->kit_id)->name : \App\mdProducts::find($itemDemandFood->product_id)->name))}}
                                                            </td>
                                                            @if(!is_null($itemDemandFood->kit_id))
                                                                @if(!is_null(\App\mdKits::find($itemDemandFood->kit_id)->codigo_pdv_store))
                                                                    <td>{{\App\mdKits::find($itemDemandFood->kit_id)->codigo_pdv_store}}</td>
                                                                @else
                                                                    <td>sem código</td>
                                                                @endif
                                                            @else
                                                                @if(!is_null(\App\mdProducts::find($itemDemandFood->product_id)->codigo_pdv_store))
                                                                    <td>{{\App\mdProducts::find($itemDemandFood->product_id)->codigo_pdv_store}}</td>
                                                                @else
                                                                    <td>sem código</td>
                                                                @endif
                                                            @endif
                                                            <td>
                                                                {{ $itemDemandFood->observation }}
                                                            </td>
                                                            <td>{{ round($itemDemandFood->amount, 4) }}</td>
                                                            <td>
                                                                R$ {{((!is_null($itemDemandFood->kit_id) ? number_format(\App\mdKits::find($itemDemandFood->kit_id)->pesqPrice(),2, ',', '.')  : number_format(\App\mdProducts::find($itemDemandFood->product_id)->pesqPrice(),2, ',', '.')))}}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div>
                                            <div class="table-responsive">
                                                <table class="table m-0">
                                                    <thead>
                                                    <tr>
                                                        <th class="thead-demand">Tp. Entrega</th>
                                                        <th class="thead-demand">Tp. Pagamento</th>
                                                        <th class="thead-demand">Valor itens</th>
                                                        <th class="thead-demand">Valor total</th>
                                                        <th class="thead-demand">Qnt. total</th>
                                                        <th class="thead-demand">Troco</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>{{$DemandFood->type_deliver}}</td>
                                                        <td>{{$DemandFood->type_payment}}</td>
                                                        <td>R$ {{ number_format($DemandFood->sub_total_price,2, ',', '.') }}</td>
                                                        <td>R$ {{ number_format($DemandFood->total_price,2, ',', '.') }}</td>
                                                        <td>{{ round($DemandFood->total_amount, 4) }}</td>
                                                        <td>R$ {{ number_format($DemandFood->money_change,2, ',', '.') }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="form-row">
                                            @if($statusDemand->type <> 'delivered')
                                            <div class="d-inline p-2">
                                                <button class="btn-change-status-demand btn btn-success" data-demandid="{{$DemandFood->demand}}"><span class="glyphicon fas fa-check"></span> Atender pedido</button>
                                            </div>
                                            @endif
                                            <div class="d-inline p-2">
                                                <a target="_blank" href="{{ route('orders.print', ['demands' => $DemandFood->demand]) }}" id='idBtnAtender' class="btn btn-secondary" ><span class="glyphicon fas fa-print"></span> Imprimir pedido</a>
                                            </div>
                                            <div class="d-inline p-2">
                                                <button class="btn-cancel-demand-modal btn btn-danger" data-demandid="{{$DemandFood->demand}}"><span class="glyphicon fas fa-times"></span> Cancelar pedido</button>
                                            </div>
                                            @if($statusDemand->type <> 'delivered')
                                            <div class="d-inline p-2">
                                                <div class="form-group clearfix">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" class="icheck-group-demand" id="ckbGroupPed-{{$DemandFood->demand}}" data-demandid="{{$DemandFood->demand}}">
                                                        <label class="text-primary" for="ckbGroupPed-{{$DemandFood->demand}}">
                                                            Agrupar pedido para atender e imprimir
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <?php $atualDemand = $DemandFood->demand ?>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <!-- /.Content -->
    </div>
    <!-- /.Content Wrapper. Contains page content -->
@endsection

@section('javascript')
    <script src="{{ asset('admin/node_modules/js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/js/plugins-demands.js') }}"></script>
    <script>
        $('.fone').mask('(00) 00000-0000');
        $('#idBtnGroupAtend').prop("disabled", true);
        $('#idBtnGroupPrint').prop("disabled", true);
        defaultValues.defaultValues("{{$APP_URL}}",{{$statusDemand->id}});
        $(document).on("click", ".btn-change-status-demand", function (event) {
            event.preventDefault();
            var idDemand = $(this).data("demandid");
            changeStatusDemand.changeStatusDemand("{{ route('orders.change.status') }}", idDemand, {{$statusDemand->id+1}}, {{$statusDemand->id}});
        });
        $(document).on("click", "#idOkCancelModal", function (event) {
            event.preventDefault();
            var idDemand = $('#idCancelDemandModal').val();
            changeStatusDemand.changeStatusDemand("{{ route('orders.change.status') }}", idDemand, 5, {{$statusDemand->id}});
        });
    </script>
@endsection
