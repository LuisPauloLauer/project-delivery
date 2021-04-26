<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista pedido</title>
</head>
<body>
<?php $atualDemand = 'teste' ?>
@foreach($listDemandsFood as $DemandFood )
    @if( $atualDemand <> $DemandFood->demand )
        <table class="minimalistBlack">
            <thead>
                <tr>
                    <th class="header-father" colspan="3">Pedido: {{ $DemandFood->demand }}</th>
                    <th class="header-father">Qtd total: {{ round($DemandFood->total_amount, 4) }}</th>
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
                <tr>
                    <td align="center"><strong>Observação</strong></td>
                    <td colspan="3" align="center">{{ $DemandFood->observation }}</td>
                </tr>
    @if($DemandFood->itens == $DemandFood->total_itens)
            </tbody>
            <tfoot>
            <tr>
                <td align="center"><strong>Valores 1</strong></td>
                <td align="left" colspan="3">
                    <strong>Vlr. imposto: </strong>{{ number_format($DemandFood->tax_price,2, ',', '.') }}
                    <strong>Vlr. frete: </strong>{{ number_format($DemandFood->shipping_price,2, ',', '.') }}
                    <strong>Vlr. desc. frete: </strong>{{ number_format($DemandFood->shipping_discount_price,2, ',', '.') }}
                    <strong>Vlr. seguro: </strong>{{ number_format($DemandFood->insurance_price,2, ',', '.') }}
                    <strong>Vlr. manuseio: </strong>{{ number_format($DemandFood->handling_fee_price,2, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td align="center"><strong>Valores 2</strong></td>
                <td align="left" colspan="3">
                    <strong>Vlr. pedido: </strong>{{ number_format($DemandFood->sub_total_price,2, ',', '.') }}
                    <strong>Vlr. total: </strong>{{ number_format($DemandFood->total_price,2, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td align="center"><strong>Entrega</strong></td>
                <td align="left" colspan="3">
                    <strong>Tp. pagamento: </strong>{{$DemandFood->type_payment}}
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
