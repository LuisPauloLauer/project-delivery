@extends('admin.layout_master.admin_design')

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
                                                        <h4 class="card-title">
                                                            <div class="row">
                                                            <div class="col-md-auto">
                                                                <span>Pedido código: {{$DemandFood->demand}} </span>
                                                            </div>
                                                            <div class="col">
                                                                <a data-toggle="collapse" data-parent="#accordion" href="#pedido{{$DemandFood->demand}}">ver detalhes</a>
                                                            </div>
                                                            </div>
                                                        </h4>
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
                                                                    <br>
                                                                    <strong> Observações: </strong>
                                                                    <div class="form-row">
                                                                        <div class="col-6">
                                                                            <textarea name="observation" id="idobservation" class="txt-observation md-textarea form-control" rows="2">{{$DemandFood->observation}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <hr class="mb-4">

                                            @if( $DemandFood->itens == $DemandFood->total_itens )
                                                            </ul>
                                                            <div class="form-row">
                                                                <span>Valor do pedido: R$ {{ number_format($DemandFood->sub_total_price,2, ',', '.') }}</span>
                                                                <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                <span>Valor do imposto: R$ {{ number_format($DemandFood->tax_price,2, ',', '.') }}</span>
                                                                <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                <span>Valor do frete: R$ {{ number_format($DemandFood->shipping_price,2, ',', '.') }}</span>
                                                                <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                <span>Valor desconto frete: R$ {{ number_format($DemandFood->shipping_discount_price,2, ',', '.') }}</span>
                                                                <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                <span>Valor seguro: R$ {{ number_format($DemandFood->insurance_price,2, ',', '.') }}</span>
                                                                <span style='color:red;margin-right:1.25em; display:inline-block;'>&nbsp;</span>
                                                                <span>Valor manuseio: R$ {{ number_format($DemandFood->handling_fee_price,2, ',', '.') }}</span>
                                                            </div>
                                                            <div class="form-row">
                                                                Valor total do pedido: R$ {{ number_format($DemandFood->total_price,2, ',', '.') }}
                                                            </div>
                                                            <div class="form-row">
                                                                {{$DemandFood->type_deliver}}
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
    <script>

        $(document).ready(function () {

            $('.txt-observation').prop("disabled",true);

        });

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
