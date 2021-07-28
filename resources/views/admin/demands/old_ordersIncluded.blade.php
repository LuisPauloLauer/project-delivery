@extends('admin.layout_master.admin_design')

@section('Stylescss')
    <style>
        th.thead-demand{
            border-top: 0;
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
                            <li class="breadcrumb-item"><h5>Novos</h5></li>
                            <li class="breadcrumb-item active">Pedidos</li>
                        </ol>
                    </div>
                </div>
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
                                                        <td>{{date_format (new DateTime($DemandFood->datetime), 'd/m/Y h:i')}}</td>
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
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                            </button>
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
                                                            <td>
                                                                {{((!is_null($itemDemandFood->kit_id) ? $itemDemandFood->kit_id : $itemDemandFood->product_id))}}
                                                            </td>
                                                            <td>{{ round($DemandFood->amount, 4) }}</td>
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

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <br>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-notes-medical"></i>
                                    Novos pedidos
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="card-body">
                                    <div id="accordion">
                                        <?php $atualDemand = 'teste' ?>
                                        @foreach($listDemandsFood as $DemandFood)
                                            @if( $atualDemand <> $DemandFood->demand )
                                                <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                                                <div class="card card-red">
                                                    <div class="card-header">
                                                        <div class="row">
                                                            <div class="col-md-2 col-sm-12">
                                                                <strong>Pedido: </strong><span>{{$DemandFood->demand}}</span>
                                                            </div>
                                                            <div class="col-md-5 col-sm-12">
                                                                <strong>Empresa: </strong><span>TECH - MASCHINEN ENGENHARIA</span>
                                                            </div>
                                                            <div class="col-md-5 col-sm-12">
                                                                <strong>Prédio: </strong><span>Prédio Campus</span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-5 col-sm-12">
                                                                <strong>Cliente: </strong><span>{{$DemandFood->user_site_name}}</span>
                                                            </div>
                                                            <div class="col-md-5 col-sm-10">
                                                                <strong>Cliente Fone: </strong><span class="fone">{{$DemandFood->user_site_fone}}</span>
                                                            </div>
                                                            <div class="col-md-2 col-sm-2">
                                                                <a class="float-right"data-toggle="collapse" data-parent="#pedido{{$DemandFood->demand}}" href="#pedido{{$DemandFood->demand}}"><strong>ver detalhes</strong></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="pedido{{$DemandFood->demand}}" class="panel-collapse collapse in">
                                                        <div class="card-body">
                                                            <ul>

                                            @endif
                                                                <li>
                                                                    <div class="form-row">
                                                                        <div class="col-2">
                                                                            <span>iten: {{$DemandFood->itens}}</span>
                                                                        </div>
                                                                        @if(!is_null($DemandFood->kit_id))
                                                                            <div class="col-4">
                                                                                <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                                <strong> {{ \App\mdKits::find($DemandFood->kit_id)->name }}</strong>
                                                                            </div>
                                                                        @else
                                                                            <div class="col-4">
                                                                                <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                                <strong> {{ \App\mdProducts::find($DemandFood->product_id)->name }}</strong>
                                                                            </div>
                                                                        @endif
                                                                        @if(!is_null($DemandFood->kit_id))
                                                                            <div class="col-2">
                                                                                <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                                <strong> Código: </strong><span> {{$DemandFood->kit_id}}</span>
                                                                            </div>
                                                                        @else
                                                                            <div class="col-2">
                                                                                <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                                <strong> Código: </strong><span> {{$DemandFood->product_id}}</span>
                                                                            </div>
                                                                        @endif
                                                                        <div class="col-2">
                                                                            <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                            <span> quantidade: </span><span> {{ round($DemandFood->amount, 4) }}</span>
                                                                        </div>
                                                                    </div>
                                                                    @if(!is_null($DemandFood->kit_id))
                                                                        <?php
                                                                        $arraySubItens = \App\mdProducts::pesqSubItensOfKit($DemandFood->kit_sub_itens);
                                                                        $countSubIten = 0;
                                                                        foreach($arraySubItens as $subIten){
                                                                        ?>
                                                                            @if($countSubIten == 0)
                                                                                <br>
                                                                            @endif
                                                                            <div class="form-row">
                                                                            <ul>
                                                                                <li>
                                                                                    <div class="form-row">
                                                                                        <span><b>Sub-iten código:</b> {{$subIten['product_id']}}<span style='color:red;margin-right:0.5em; display:inline-block;'>&nbsp;</span>{{$subIten['product_name']}}</span>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                            </div>
                                                                        <?php
                                                                            $countSubIten++;
                                                                        }
                                                                        ?>
                                                                    @endif
                                                                    @if(!is_null($DemandFood->observation))
                                                                    <br>
                                                                    <strong> Observações: </strong>
                                                                    <div class="form-row">
                                                                        <div class="col-6">
                                                                            <textarea name="observation" id="idobservation" class="txt-observation md-textarea form-control" rows="2">{{$DemandFood->observation}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                </li>
                                                                <hr class="mb-4">

                                            @if( $DemandFood->itens == $DemandFood->total_itens )
                                                            </ul>
                                                            <div class="form-row">
                                                                <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                <span>Valor do pedido: R$ {{ number_format($DemandFood->total_price,2, ',', '.') }}</span>
                                                                <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                <span>Tipo do pagamento: {{$DemandFood->type_payment}}</span>
                                                                @if($DemandFood->type_payment == 'Dinheiro' && $DemandFood->money_change > 0)
                                                                    <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                    <span>Troco para: R$ {{ number_format($DemandFood->money_change,2, ',', '.') }}</span>
                                                                @endif
                                                            </div>
                                                            <br>
                                                            <div class="form-row">
                                                                <div class="col-lg-auto">
                                                                    <button class="btn-change-status-demand btn btn-success" data-demandid="{{$DemandFood->demand}}"><span class="glyphicon fas fa-check"></span> Atender pedido</button>
                                                                </div>
                                                                <div class="col-lg-auto">
                                                                    <a target="_blank" href="{{ route('orders.print', ['demand' => $DemandFood->demand]) }}" id='idBtnAtender' class="btn btn-secondary" ><span class="glyphicon fas fa-print"></span> Imprimir pedido</a>
                                                                </div>
                                                                <div class="col-lg-auto">
                                                                    <button class="btn-cancel-demand-modal btn btn-danger" data-demandid="{{$DemandFood->demand}}"><span class="glyphicon fas fa-times"></span> Cancelar pedido</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <?php $atualDemand = $DemandFood->demand ?>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('javascript')
    <script src="{{ asset('admin/node_modules/js/jquery.mask.min.js') }}"></script>
    <script>
        $(document).ready(function () {

            $('.txt-observation').prop("disabled",true);

        });

        $('.fone').mask('(00) 00000-0000');

        $(document).on("click", ".btn-change-status-demand", function (event) {

            event.preventDefault();
            var idDemand = $(this).data("demandid");

            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('orders.change.status') }} ",
                type: "post",
                data : {
                    demand_id : idDemand,
                    status_type : 2
                },
                dataType: "json",
                success : function (response) {

                    if(response.success === true){

                        alert(response.message);
                        window.location.reload();

                    } else {

                        alert(response.message);

                    }

                    console.log(response);

                }

            });

        });

        <!-- Modal Cancel Demand show modal -->
        $(document).on('click','.btn-cancel-demand-modal', function () {

            var idDemand = $(this).data("demandid");

            $('#idconfirmCancelDemandModal').modal('show');

            $('#idSpanCancelDemandModal').text(idDemand);
            $('#idCancelDemandModal').val(idDemand);


        });

        $(document).on("click", "#idOkCancelModal", function (event) {

            event.preventDefault();
            var idDemandCancel = $('#idCancelDemandModal').val();

            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('orders.change.status') }} ",
                type: "post",
                data : {
                    demand_id : idDemandCancel,
                    status_type : 5
                },
                dataType: "json",
                success : function (response) {

                    if(response.success === true){

                        alert(response.message);
                        window.location.reload();

                    } else {

                        alert(response.message);

                    }

                    console.log(response);

                }

            });

        });

    </script>
@endsection
