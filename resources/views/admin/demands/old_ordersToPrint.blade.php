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

        table.minimalistBlack thead th.header-father {
            border: 2px solid #000000;
            font-size: 15px;
            font-weight: bold;
            color: #000000;
            text-align: left;
            background-color: #CFCFCF;
            background-color: white;
        }

        table.minimalistBlack tbody th.item-father {
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            background-color: white;
            background-color: #CFCFCF;
        }

        table.minimalistBlack tbody td.item-father-content {
            font-size: 15px;
            text-align: center;
            background-color: white;
            background-color: #CFCFCF;
        }

        table.minimalistBlack tbody td.sub-item-content {
            font-size: 13px;
            text-align: center;
            background-color: #CFCFCF;
            background-color: white;
        }

        table.minimalistBlack tbody td.sub-item-content-name {
            font-size: 13px;
            text-align: left;
            background-color: #CFCFCF;
            background-color: white;
        }

        table.minimalistBlack tfoot {
            font-size: 13px;
            font-weight: bold;
            color: #000000;
            border-top: 3px solid #000000;
        }

        table.minimalistBlack tfoot td {
            font-size: 13px;
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
                    <th class="header-father" colspan="3">Pedido: {{ $DemandFood->demand }} Empresa: {{ $DemandFood->company_name }} Prédio: {{ $DemandFood->building_name }}</th>
                    <th class="header-father">Qtd total: {{ round($DemandFood->total_amount, 4) }}</th>
                </tr>
                <tr>
                    <th class="header-father" colspan="4">Cliente: {{ $DemandFood->user_site_name }} Telefone: {{ $DemandFood->user_site_fone }}</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <th class="item-father">Iten</th>
                <th class="item-father">Código</th>
                <th class="item-father">Nome</th>
                <th class="item-father">Quantidade</th>
            </tr>
    @endif

                <tr>
                    <td class="item-father-content">{{ $DemandFood->itens }}</td>
                    @if(!is_null($DemandFood->kit_id))
                        <td class="item-father-content">{{ $DemandFood->kit_id }}</td>
                        <td class="item-father-content">{{ \App\mdKits::find($DemandFood->kit_id)->name }}</td>
                    @else
                        <td class="item-father-content">{{ $DemandFood->product_id }}</td>
                        <td class="item-father-content">{{ \App\mdProducts::find($DemandFood->product_id)->name }}</td>
                    @endif
                    <td class="item-father-content">{{ round($DemandFood->amount, 4) }}</td>
                </tr>
                @if(!is_null($DemandFood->kit_id))
                    <?php
                    $arraySubItens = \App\mdProducts::pesqSubItensOfKit($DemandFood->kit_sub_itens);
                    foreach($arraySubItens as $subIten){
                    ?>
                        <tr>
                            <td class="sub-item-content">Sub-iten</td>
                            <td class="sub-item-content">{{ $subIten['product_id'] }}</td>
                            <td class="sub-item-content-name" colspan="2">{{ $subIten['product_name'] }}</td>
                        </tr>
                    <?php
                    }
                    ?>
                @endif
                @if(!is_null($DemandFood->observation))
                    <tr>
                        <td align="center"><strong>Observação</strong></td>
                        <td colspan="3" align="center">{{ $DemandFood->observation }}</td>
                    </tr>
                @else
                    <tr>
                        <td align="center"><strong>Observação</strong></td>
                        <td colspan="3" align="center">Nenhuma</td>
                    </tr>
                @endif
    @if($DemandFood->itens == $DemandFood->total_itens)
            </tbody>
            <tfoot>
            <tr>
                <td align="center"><strong>Valores</strong></td>
                <td align="left" colspan="3">
                    <strong>Vlr. pedido: </strong>{{ number_format($DemandFood->total_price,2, ',', '.') }}
                    <strong>Tp. pagamento: </strong>{{$DemandFood->type_payment}}
                    @if($DemandFood->type_payment == 'Dinheiro' && $DemandFood->money_change > 0)
                        <strong>Troco para: </strong>R$ {{ number_format($DemandFood->money_change,2, ',', '.') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td align="center"><strong>Entrega</strong></td>
                <td align="left" colspan="3">
                    <strong>Tp. entrega: </strong>{{$DemandFood->type_deliver}}
                </td>
            </tr>
            </tfoot>
        </table>
        <br>
    @endif
    <?php $atualDemand = $DemandFood->demand ?>
@endforeach
</body>
</html>
