@extends('site.layout_master.site_design')

@section('content')
    <div class="container">
        @if(isset($Kits))
            <div class="row">
                <div class="col-sm-6 col-md-6 col-md-offset-3 col-sm-offset-3">
                    <ul class="list-group">
                        @foreach($Kits as $Kit)
                                @for($i=0; $i < count( $Kit['productSellSubItems'] ); $i++)
                                    @if($Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty'] > 0 )
                                    <li class="list-group-item">
                                        <strong>{{ $Kit['item']['name'] }}</strong><br>
                                        @if(\App\mdKits::find($Kit['item']['id'])->pesqFirstImageKit)
                                            <img width="100" height="100" src="{{ $pathImagens }}/kits/store_id_{{$Kit['item']['store']}}/{{ $Kit['item']['id'] }}/small/{{ \App\mdKits::find($Kit['item']['id'])->pesqFirstImageKit->path_image }}"/>
                                        @else
                                            <img width="100" height="100" src="{{ $pathImagens }}/../../files/images/no_photo.png"/>
                                        @endif
                                        <br>
                                        @for($j=0; $j < count( $Kit['productSellSubItems'][$i][$Kit['item']['id']]['productSellItems'] ); $j++)
                                            <span class="badge">{{ \App\mdProducts::pesqNameProduct( $Kit['productSellSubItems'][$i][$Kit['item']['id']]['productSellItems'][$j]) }}</span>
                                            <br>
                                            @if($j == (count( $Kit['productSellSubItems'][$i][$Kit['item']['id']]['productSellItems'] )-1) )
                                                <span class="badge">Observação: {{ $Kit['productSellSubItems'][$i][$Kit['item']['id']]['observation']  }}</span>
                                                <br>
                                                <div class="row">
                                                    <div class="col-2">
                                                        <button id="btn-model-minus-car-qnty" type="button"
                                                                class="btn btn-outline-primary" data-object="kit"
                                                                data-prodid="{{$Kit['item']['id']}}"
                                                                data-sellitems="{{ $i }}"
                                                                data-sellitemsqnty="{{ $Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty'] }}">
                                                            <span class="glyphicon fas fa-minus"></span></button>
                                                    </div>
                                                    <div class="col-1">
                                                        <div id="qntyProdCart">{{ $Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty'] }}</div>
                                                    </div>
                                                    <div class="col-2">
                                                        <button id="btn-model-add-car-qnty" type="button"
                                                                class="btn btn-outline-primary" data-object="kit"
                                                                data-prodid="{{$Kit['item']['id']}}"
                                                                data-sellitems="{{ $i }}"><span
                                                                    class="glyphicon fas fa-plus"></span></button>
                                                    </div>
                                                    <div class="col-2">
                                                        <button id="btn-model-dell-item-car" type="button"
                                                                class="btn btn-outline-primary" data-object="kit"
                                                                data-prodid="{{$Kit['item']['id']}}"
                                                                data-sellitems="{{ $i }}"><span
                                                                    class="glyphicon fas fa-trash"></span></button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endfor
                                    </li>
                                    @if($i == (count( $Kit['productSellSubItems'] )-1) )
                                        <span class="label label-success">Quantidade total {{ $Kit['qty'] }}</span>
                                        <span class="label label-success">R$: {{ $Kit['price'] }}</span>
                                    @endif
                                    @endif
                                @endfor
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @if(isset($Products))
            <div class="row">
                <div class="col-sm-6 col-md-6 col-md-offset-3 col-sm-offset-3">
                    <ul class="list-group">
                        @foreach($Products as $Product)
                            <li class="list-group-item">
                                <strong>{{ $Product['item']['name'] }}</strong><br>
                                @if(\App\mdProducts::find($Product['item']['id'])->pesqFirstImageProduct)
                                    <img width="100" height="100" src="{{ $pathImagens }}/products/store_id_{{$Product['item']['store']}}/{{ $Product['item']['id'] }}/small/{{ \App\mdProducts::find($Product['item']['id'])->pesqFirstImageProduct->path_image }}"/>
                                @else
                                    <img width="100" height="100" src="{{ $pathImagens }}/../../files/images/no_photo.png"/>
                                @endif
                                <br>
                                <span class="badge">Observação: {{ $Product['observation']  }}</span>
                                <div class="row">
                                    <div class="col-2">
                                        <button id="btn-model-minus-car-qnty" type="button"
                                                class="btn btn-outline-primary" data-object="product"
                                                data-prodid="{{$Product['item']['id']}}"
                                                data-itemqnty="{{ $Product['qty'] }}"><span
                                                class="glyphicon fas fa-minus"></span></button>
                                    </div>
                                    <div class="col-1">
                                        <div id="qntyProdCart">{{ $Product['qty'] }}</div>
                                    </div>
                                    <div class="col-2">
                                        <button id="btn-model-add-car-qnty" type="button"
                                                class="btn btn-outline-primary" data-object="product"
                                                data-prodid="{{$Product['item']['id']}}"><span
                                                class="glyphicon fas fa-plus"></span></button>
                                    </div>
                                    <div class="col-2">
                                        <button id="btn-model-dell-item-car" type="button"
                                                class="btn btn-outline-primary" data-object="product"
                                                data-prodid="{{$Product['item']['id']}}"><span
                                                    class="glyphicon fas fa-trash"></span></button>
                                    </div>
                                </div>
                            </li>
                            <span class="label label-success">R$: {{ $Product['price'] }}</span>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @if( isset($Kits) && isset($Products) )
            <div>Quantidade total: {{ $KitsTotalQnty + $ProductsTotalQnty }}</div>
            <div>Preço total: {{ $KitsTotalPrice + $ProductsTotalPrice }}</div>
        @elseif(isset($Kits))
            <div>Quantidade total: {{ $KitsTotalQnty }}</div>
            <div>Preço total: {{ $KitsTotalPrice }}</div>
        @elseif(isset($Products))
            <div>Quantidade total: {{ $ProductsTotalQnty }}</div>
            <div>Preço total: {{ $ProductsTotalPrice }}</div>
        @endif
        <a href="{{ route('cart.empty') }}"><span>Limpar carrinho</span></a><br>
        <a href="{{ route('cart.payment') }}"><span>Escolher forma de pagamento</span></a>
    </div>
@endsection

@section('javascript')
    <script>

        $(document).on("click", "#btn-model-add-car-qnty", function (event) {

            event.preventDefault();
            var product_object = $(this).data("object");
            var product_id = $(this).data("prodid");
            var product_sub_items = $(this).data("sellitems");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('cart.edititem') }} ",
                type: "post",
                data : {
                    product_object: product_object,
                    product_id: product_id,
                    product_sub_items: product_sub_items,
                    product_operator : 'plus',
                },
                dataType: "json",
                success: function (data) {
                    if(data.success === true){

                        location.reload(true);

                    }else{
                        alert(data.success);
                    }

                    console.log(data);

                }

            });

        });

        $(document).on("click", "#btn-model-minus-car-qnty", function (event) {

            event.preventDefault();
            var product_object = $(this).data("object");
            var product_id = $(this).data("prodid");
            var product_sub_items = $(this).data("sellitems");
            var sub_items_qnty = 0;

            if(product_object == 'kit'){
                sub_items_qnty = $(this).data("sellitemsqnty");
            }else if(product_object == 'product'){
                sub_items_qnty = $(this).data("itemqnty");
            }

            if(sub_items_qnty > 1){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('cart.edititem') }} ",
                    type: "post",
                    data : {
                        product_object: product_object,
                        product_id: product_id,
                        product_sub_items: product_sub_items,
                        product_operator : 'minus',
                    },
                    dataType: "json",
                    success: function (data) {
                        if(data.success === true){

                            location.reload(true);

                        }else{
                            alert(data.success);
                        }

                        console.log(data);

                    }

                });
            }

        });

        $(document).on("click", "#btn-model-dell-item-car", function (event) {

            event.preventDefault();
            var product_object = $(this).data("object");
            var product_id = $(this).data("prodid");
            var product_sub_items = $(this).data("sellitems");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('cart.dellitem') }} ",
                type: "post",
                data : {
                    product_object: product_object,
                    product_id: product_id,
                    product_sub_items: product_sub_items,
                },
                dataType: "json",
                success: function (data) {
                    if(data.success === true){

                        location.reload(true);

                    }else{
                        alert(data.success);
                    }

                    console.log(data);

                }

            });

        });

    </script>
@endsection
