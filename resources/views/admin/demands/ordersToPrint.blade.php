<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista pedido</title>
    <style>
        table.minimalistBlack {
            border: 1px solid #000000;
            width: 100%;
            border-collapse: collapse;
        }

        table.minimalistBlack thead {
            background: #FFFFFF;
            background: -moz-linear-gradient(top, #ffffff 0%, #ffffff 66%, #FFFFFF 100%);
            background: -webkit-linear-gradient(top, #ffffff 0%, #ffffff 66%, #FFFFFF 100%);
            background: linear-gradient(to bottom, #ffffff 0%, #ffffff 66%, #FFFFFF 100%);
        }

        table.minimalistBlack th, table.minimalistBlack td {
            border: 1px solid #AAAAAA;
            padding: 3px 2px;
        }

        table.minimalistBlack thead th.thead-father {
            border: 2px solid #000000;
            font-size: 12px;
            font-weight: bold;
            color: #000000;
            text-align: left;
            background-color: #CFCFCF;
            background-color: white;
        }

        table.minimalistBlack thead th.thead-father.text-center{
            text-align: center;
        }

        table.minimalistBlack tbody td.tbody-father {
            font-size: 12px;
            color: #000000;
            text-align: left;
            background-color: #CFCFCF;
            background-color: white;
        }

        table.minimalistBlack tbody td.tbody-father.text-center{
            text-align: center;
        }

        table.minimalistBlack tbody th.tbody-item-father {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            background-color: white;
            background-color: #CFCFCF;
        }

        table.minimalistBlack tbody td.tbody-item-father-content {
            font-size: 12px;
            text-align: center;
            background-color: white;
            background-color: #CFCFCF;
        }

        table.minimalistBlack tbody td.tbody-item-observation {
            font-size: 12px;
            text-align: center;
        }

        table.minimalistBlack tbody td.tbody-sub-item-content {
            font-size: 12px;
            text-align: center;
            background-color: #CFCFCF;
            background-color: white;
        }

        table.minimalistBlack tbody td.tbody-sub-item-content-name {
            font-size: 12px;
            text-align: left;
            background-color: #CFCFCF;
            background-color: white;
        }

        table.minimalistBlack tfoot {
            font-size: 12px;
            font-weight: bold;
            color: #000000;
            border-top: 3px solid #000000;
        }

        table.minimalistBlack tfoot td {
            font-size: 12px;
        }

        table.minimalistBlack tfoot td.tfoot-item-father {
            font-size: 12px;
            font-weight: bold;
            color: #000000;
            text-align: center;
            background-color: #CFCFCF;
            background-color: white;
        }

        table.minimalistBlack tfoot td.tfoot-item-father-content {
            font-size: 12px;
            color: #000000;
            text-align: left;
            background-color: #CFCFCF;
            background-color: white;
        }

        table.minimalistBlack tfoot td.tfoot-item-father-content span {
            margin-right: 10px;
        }

    </style>
</head>
<body>
<?php $atualDemand = 'null' ?>
@foreach($listDemandsFood as $DemandFood )
    @if( $atualDemand <> $DemandFood->demand )
        <table class="minimalistBlack">
            <thead>
                <tr>
                    <th class="thead-father">Pedido</th>
                    <th class="thead-father">Data</th>
                    <th class="thead-father">Empresa</th>
                    <th class="thead-father">Prédio</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tbody-father">{{$DemandFood->demand}}</td>
                    <td class="tbody-father">{{date( "d/m/Y H:i", strtotime($DemandFood->datetime))}}</td>
                    <td class="tbody-father">{{$DemandFood->company_name}}</td>
                    <td class="tbody-father">{{$DemandFood->building_name}}</td>
                </tr>
            </tbody>
            <thead>
            <tr>
                <th class="thead-father">Cliente</th>
                <th class="thead-father">Fone</th>
                <th class="thead-father text-center">Qnt. total</th>
                <th class="thead-father text-center">Subtotal</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tbody-father">{{$DemandFood->user_site_name}}</td>
                    <td class="tbody-father user-site-fone">{{$DemandFood->user_site_fone}}</td>
                    <td class="tbody-father text-center">{{ round($DemandFood->total_amount, 4) }}</td>
                    <td class="tbody-father text-center">R$ {{ number_format($DemandFood->sub_total_price,2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th class="tbody-item-father">Item</th>
                    <th class="tbody-item-father">Código</th>
                    <th class="tbody-item-father">Quantidade</th>
                    <th class="tbody-item-father">Vlr. unitário</th>
                </tr>
    @endif
                <tr>
                    <td class="tbody-item-father-content">{{((!is_null($DemandFood->kit_id) ? \App\mdKits::find($DemandFood->kit_id)->name : \App\mdProducts::find($DemandFood->product_id)->name))}}</td>
                    @if(!is_null($DemandFood->kit_id))
                        @if(!is_null(\App\mdKits::find($DemandFood->kit_id)->codigo_pdv_store))
                            <td class="tbody-item-father-content">{{\App\mdKits::find($DemandFood->kit_id)->codigo_pdv_store}}</td>
                        @else
                            <td class="tbody-item-father-content">sem código</td>
                        @endif
                    @else
                        @if(!is_null(\App\mdProducts::find($DemandFood->product_id)->codigo_pdv_store))
                            <td class="tbody-item-father-content">{{\App\mdProducts::find($DemandFood->product_id)->codigo_pdv_store}}</td>
                        @else
                            <td class="tbody-item-father-content">sem código</td>
                        @endif
                    @endif
                    <td class="tbody-item-father-content">{{ round($DemandFood->amount, 4) }}</td>
                    <td class="tbody-item-father-content">
                        R$ {{((!is_null($DemandFood->kit_id) ? number_format(\App\mdKits::find($DemandFood->kit_id)->pesqPrice(),2, ',', '.')  : number_format(\App\mdProducts::find($DemandFood->product_id)->pesqPrice(),2, ',', '.')))}}
                    </td>
                </tr>
                @if(!is_null($DemandFood->kit_id))
                    <?php
                    $arraySubItens = \App\mdProducts::pesqSubItensOfKit($DemandFood->kit_sub_itens);
                    foreach($arraySubItens as $subIten){
                    ?>
                        <tr>
                            <td class="tbody-sub-item-content">Sub-iten</td>
                            <td class="tbody-sub-item-content">{{ $subIten['product_id'] }}</td>
                            <td class="tbody-sub-item-content-name" colspan="2">{{ $subIten['product_name'] }}</td>
                        </tr>
                    <?php
                    }
                    ?>
                @endif
                @if(!is_null($DemandFood->observation))
                    <tr>
                        <td class="tbody-item-observation">Observação</td>
                        <td class="tbody-item-observation" colspan="3">{{ $DemandFood->observation }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="tbody-item-observation">Observação</td>
                        <td class="tbody-item-observation" colspan="3">Nenhuma</td>
                    </tr>
                @endif
    @if($DemandFood->itens == $DemandFood->total_itens)
            </tbody>
            <tfoot>
            <tr>
                <td class="tfoot-item-father">Entrega</td>
                <td class="tfoot-item-father-content" colspan="3">
                    <strong>Tp. entrega: </strong><span>{{$DemandFood->type_deliver}}</span><span>--</span>
                    <strong>Tp. pagamento: </strong><span>{{$DemandFood->type_payment}}</span>
                </td>
            </tr>
            <tr>
                <td class="tfoot-item-father">Valores</td>
                <td class="tfoot-item-father-content" colspan="3">
                    <strong>Vlr. frete: </strong><span>{{ number_format($DemandFood->shipping_price,2, ',', '.') }}</span><span>--</span>
                    <strong>Vlr. total: </strong><span>{{ number_format($DemandFood->total_price,2, ',', '.') }}</span><span>--</span>
                    @if($DemandFood->type_payment == 'Dinheiro' && $DemandFood->money_change > 0)
                        <strong>Troco para: </strong><span>R$ {{ number_format($DemandFood->money_change,2, ',', '.') }}</span>
                    @else
                        <strong>Troco para: </strong><span>R$ 0,00</span>
                    @endif
                </td>
            </tr>
            </tfoot>
        </table>
        <br>
    @endif
    <?php $atualDemand = $DemandFood->demand ?>
@endforeach
    <script src="{{ asset('admin/node_modules/js/jquery.js') }}"></script>
    <script src="{{ asset('admin/node_modules/js/jquery.mask.min.js') }}"></script>
    <script>
        $('.user-site-fone').mask('(00) 00000-0000');
    </script>
</body>
</html>
