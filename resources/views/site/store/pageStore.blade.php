@extends('site.layout_master.site_design')

@section('content')
    <!-- Modal AddProduct -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container text-center">
                        <h5 id="modal-title-product"></h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="product-img">
                                    <img width="150" height="150" id="product-img-imagen" src=""/>
                                </div>
                                <div id="imagens-product">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <p class="text-center" id="modal-description-product"></p>
                                <div id="modal-products-cart">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-2">
                                <button id="btn-model-minus-car-qnty" type="button" class="btn btn-outline-primary"><span class="glyphicon fas fa-minus"></span></button>
                            </div>
                            <div class="col-1">
                                <div id="qntyProdCart"></div>
                            </div>
                            <div class="col-2">
                                <button id="btn-model-add-car-qnty" type="button" class="btn btn-outline-primary"><span class="glyphicon fas fa-plus"></span></button>
                            </div>
                            <div class="col-md-auto">
                                <a id="btn-model-add-car" class="btn btn-large btn-danger"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal AddProduct -->

    <!-- Modal -->
    <div class="modal fade" id="diffStoreModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container text-center">
                        <h5 id="modal-title-diffStore">
                            Você só pode adicionar itens de um estabelecimento por vez
                        </h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container text-center">
                        Deseja esvaziar o carrinho para adicionar novos itens?
                    </div>
                </div>
                <div class="modal-footer">
                    <button id='emptyCartModalProduct' type="button" class="btn btn-primary">Sim</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Section tittle -->
    <div class="row">
        <div class="col-xl-12">
            <div class="text-center">
                <h2>{{$Store->name}}</h2>
            </div>
        </div>
    </div>
    <section class="new-product-area section-padding30">
        <div class="container">
            <div class="row">
            <div class="col-lg-4">
                <div class="blog_right_sidebar">
                    <aside class="single_sidebar_widget post_category_widget">
                        <h4 class="widget_title">Categoria</h4>
                        <ul class="list cat-list">
                            <li>
                                <a href="{{ route('store.page', ['segment' => $Segment->slug, 'store' => $Store->slug] ) }}" class="d-flex">
                                    <p>Todos</p>
                                    <p>
                                        ( {{ $totalObjects }} )
                                    </p>
                                </a>
                            </li>
                            @foreach($listCategoriesProducts as $CategoryProducts)
                                <?php
                                    $Kits = $CategoryProducts::find($CategoryProducts->id)->pesqKitsByCategoriesProduct($Store, 'S', true)->get();
                                    $Products = $CategoryProducts::find($CategoryProducts->id)->pesqProductsByCategoriesProduct($Store, 'S')->get();
                                ?>
                                <li>
                                    <a href="{{ route('store.category.page', ['segment' => $Segment->slug, 'store' => $Store->slug, 'category' => $CategoryProducts->id] ) }}" class="d-flex">
                                        <p>{{ $CategoryProducts->name }} </p>
                                        <p>( {{ count($Kits) + count($Products) }} )</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                </div>
            </div>
            <div class="col-lg-8 mb-5 mb-lg-0">
                    <?php $OldCategoryKit = 'category' ?>
                    <?php $NKits = 0 ?>
                    @foreach($listKit as $Kit)
                        <?php $NKits += 1 ?>
                        @if($OldCategoryKit <> $Kit->category_product)
                            @if( ($OldCategoryKit <> $Kit->category_product) && ($OldCategoryKit <> 'category') )
                                </div>
                            @endif
                            <div class="row">
                                <h5>{{ \App\mdKits::find($Kit->id)->pesqCategoryProduct->name }}</h5>
                            </div>
                            <div class="row">
                        @endif
                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="single-new-pro mb-30 text-center">
                                <div class="row">
                                <div class="col">
                                <div class="product-img">
                                    <a class="addproduct-button d-flex" data-object="kit" data-prodid="{{$Kit->id}}">
                                        @if(\App\mdKits::find($Kit->id)->pesqFirstImageKit)
                                        <img src="{{ $pathImagens }}/kits/store_id_{{$Store->id}}/{{ $Kit->id}}/small/{{ \App\mdKits::find($Kit->id)->pesqFirstImageKit->path_image }}"/>
                                        @else
                                            <img width="170" height="170" src="{{ $pathImagens }}/../../files/images/no_photo.png" />
                                        @endif
                                    </a>
                                    <div>
                                        <button class="addproduct-button btn btn-sm btn-outline-danger" data-object="kit" data-prodid="{{$Kit->id}}">Adicionar</button>
                                    </div>
                                </div>
                                </div>
                                <div class="col">
                                <div>
                                    <h6>{{ $Kit->name}}</h6>
                                    <p>{{ $Kit->description}}</p>
                                    @if($Kit->unit_promotion_price > 0)
                                        <span>R$ {{ $Kit->unit_promotion_price }}</span><br>
                                        <span>desconto % {{ $Kit->unit_discount }}</span><br>
                                        <span style="color: #b8b4b4; text-decoration: line-through;">R$ {{ $Kit->unit_price }}</span>
                                    @else
                                        <h6>R$ {{ $Kit->unit_price }}</h6>
                                    @endif
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <?php $OldCategoryKit = $Kit->category_product ?>
                        @if( $NKits == count($listKit) )
                            </div>
                        @endif
                    @endforeach
                    <?php $OldCategoryProduct = 'category' ?>
                    <?php $NProducts = 0 ?>
                    @foreach($listProduct as $Product)
                        <?php $NProducts += 1 ?>
                        @if($OldCategoryProduct <> $Product->category_product)
                            @if( ($OldCategoryProduct <> $Product->category_product) && ($OldCategoryProduct <> 'category') )
                                </div>
                            @endif
                            <div class="row">
                                <h5>{{ $Product::find($Product->id)->pesqCategoryProduct->name }}</h5>
                            </div>
                            <div class="row">
                        @endif
                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="single-new-pro mb-30 text-center">
                                <div class="row">
                                    <div class="col">
                                        <div class="product-img">
                                            <a class="addproduct-button d-flex" data-object="product" data-prodid="{{$Product->id}}">
                                                @if($Product::find($Product->id)->pesqFirstImageProduct)
                                                    <img src="{{ $pathImagens }}/products/store_id_{{$Store->id}}/{{ $Product->id}}/small/{{ $Product::find($Product->id)->pesqFirstImageProduct->path_image }}"/>
                                                @else
                                                    <img width="170" height="170" src="{{ $pathImagens }}/../../files/images/no_photo.png" />
                                                @endif
                                            </a>
                                            <div>
                                                <button class="addproduct-button btn btn-sm btn-outline-danger" data-object="product" data-prodid="{{$Product->id}}">Adicionar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <h6>{{ $Product->name}}</h6>
                                            <p>{{ $Product->description}}</p>
                                            @if($Product->unit_promotion_price > 0)
                                                <span>R$ {{ $Product->unit_promotion_price }}</span><br>
                                                <span>desconto % {{ $Product->unit_discount }}</span><br>
                                                <span style="color: #b8b4b4; text-decoration: line-through;">R$ {{ $Product->unit_price }}</span>
                                            @else
                                                <h6>R$ {{ $Product->unit_price }}</h6>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $OldCategoryProduct = $Product->category_product ?>
                        @if( $NProducts == count($listProduct) )
                            </div>
                        @endif
                    @endforeach
            </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>

        var modalProductObject = null;
        var modalProductId = null;
        var modalProductQnty = 0;
        var modalProductPrice = 0;
        var modalProductTotalPrice = 0;
        var modalCategoryTotal = 0;
        var modalSellProducts = [];

        $(document).on("click", ".cart-kit-prod-item", function (event) {

            var div = $("div#modal-products-cart");
            var radiosBtns = div.find("input[type='radio']");
            var numbRadiosChecked = 0;
            modalSellProducts = [];

            // Get all the radios
            for (var i = 0; i < radiosBtns.length; i++) {

                var radio = $(radiosBtns[i]);

                // If this isn't checked, skip it
                if (radio.is(':checked') === true) {
                    modalSellProducts.push(radio.val());
                    numbRadiosChecked++;
                    $('#itensSelectedbykit').empty();
                    $('#itensSelectedbykit').text(numbRadiosChecked+'/'+modalCategoryTotal);
                }
            }

            if (numbRadiosChecked == modalCategoryTotal) {
                console.log(modalSellProducts);
                console.log('Obs: '+$('#idobservationtxt').val());
                $('#btn-model-add-car').prop("disabled", false);
            }

        });

        $(document).on("click", "#btn-model-add-car-qnty", function (event) {

            var ProductTotalPrice = 0;

            if(modalProductQnty >= 1){
                modalProductTotalPrice = parseFloat(modalProductTotalPrice);
                modalProductPrice = parseFloat(modalProductPrice);
                modalProductQnty++;
                modalProductTotalPrice += modalProductPrice;

                ProductTotalPrice = modalProductTotalPrice.toFixed(2);

                $('#btn-model-add-car').empty();
                $('#btn-model-add-car').text('Adicionar R$ ');
                $('#btn-model-add-car').append('<median>'+ProductTotalPrice+'<median>');
                $('#qntyProdCart').empty();
                $('#qntyProdCart').text(modalProductQnty);

                $('#btn-model-minus-car-qnty').prop("disabled",false);
            }

        });

        $(document).on("click", "#btn-model-minus-car-qnty", function (event) {

            var ProductTotalPrice = 0;

            if(modalProductQnty > 1){
                modalProductTotalPrice = parseFloat(modalProductTotalPrice);
                modalProductPrice = parseFloat(modalProductPrice);
                modalProductQnty = (modalProductQnty-1);
                modalProductTotalPrice = (modalProductTotalPrice - modalProductPrice);

                ProductTotalPrice = modalProductTotalPrice.toFixed(2);

                $('#btn-model-add-car').empty();
                $('#btn-model-add-car').text('Adicionar R$ ');
                $('#btn-model-add-car').append('<median>'+ProductTotalPrice+'<median>');
                $('#qntyProdCart').empty();
                $('#qntyProdCart').text(modalProductQnty);

                if(modalProductQnty == 1){
                    $('#btn-model-minus-car-qnty').prop("disabled",true);
                }

            }

        });

        $(document).on("click", "#btn-model-add-car", function (event) {

            event.preventDefault();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('cart.add') }} ",
                type: "post",
                data : {
                    product_object: modalProductObject,
                    product_id: modalProductId,
                    product_qnty : $('#qntyProdCart').text(),
                    product_observation : $('#idobservationtxt').val(),
                    products_sell: modalSellProducts,
                },
                dataType: "json",
                success: function (data) {
                    if(data.success === true){

                        //location.reload(true);
                        window.location.reload();

                    }else{
                        alert(data.success);
                    }

                    console.log(data);
                }
            });

        });

        function showAddProductModal(dataProduct) {

            if(dataProduct.object == 'kit') {
                modalCategoryTotal = dataProduct.products_category.length;
            }

            var modalCategoryProduct = null;
            modalProductObject = null;
            modalProductId = null;
            modalProductQnty = 0;
            modalProductPrice = 0;
            modalProductTotalPrice = 0;

            if(dataProduct.imagens[0]) {
                if (dataProduct.object == 'kit') {
                    var firstImage = '{{ $pathImagens }}/kits/store_id_{{ $Store->id }}/' + dataProduct.id + '/small/' + dataProduct.imagens[0]['path_image'];
                } else if (dataProduct.object == 'product') {
                    var firstImage = '{{ $pathImagens }}/products/store_id_{{ $Store->id }}/' + dataProduct.id + '/small/' + dataProduct.imagens[0]['path_image'];
                }
            } else{
                var firstImage = '{{ $pathImagens }}/../../files/images/no_photo.png';
            }

            $('#addProductModal').modal('show');

            modalProductObject = dataProduct.object;
            modalProductId = dataProduct.id;
            modalProductQnty = 1;
            modalProductPrice = dataProduct.unit_price;
            modalProductTotalPrice = dataProduct.unit_price;

            $('#btn-model-minus-car-qnty').prop("disabled",true);

            $('#modal-title-product').text(dataProduct.nameproduct);
            $('#modal-description-product').text(dataProduct.description);
            $('#qntyProdCart').text(1);
            $('#btn-model-add-car').empty();
            $('#btn-model-add-car').text('Adicionar R$ ');

            if(dataProduct.unit_promotion_price > 0){
                $('#btn-model-add-car').append('<median>'+dataProduct.unit_promotion_price+'<median>');
            } else {
                $('#btn-model-add-car').append('<median>'+dataProduct.unit_price+'<median>');
            }

            if(dataProduct.object == 'kit') {
                $('#btn-model-add-car').prop("disabled", true);
            }else {
                $('#btn-model-add-car').prop("disabled", false);
            }

            $('#product-img-imagen').attr("src", firstImage);

            $('#imagens-product').empty();

            $('#modal-products-cart').empty();

            if(dataProduct.imagens.length > 1){

                var numbImagens;

                for ( numbImagens = 0; numbImagens < dataProduct.imagens.length; numbImagens++) {

                    if(dataProduct.object == 'kit') {
                        var imagen = '{{ $pathImagens }}/kits/store_id_{{ $Store->id }}/' + dataProduct.id + '/small/' + dataProduct.imagens[numbImagens]['path_image'];
                    } else if (dataProduct.object == 'product'){
                        var imagen = '{{ $pathImagens }}/products/store_id_{{ $Store->id }}/' + dataProduct.id + '/small/' + dataProduct.imagens[numbImagens]['path_image'];
                    }

                    $('#imagens-product').append('<img width="70" height="70" class="imagens-product-index-' + numbImagens + '" src="' + imagen + '">');

                }
            }

            if(dataProduct.object == 'kit'){
                var numbProducts;
                var numbCategory;
                $('#modal-products-cart').append('<div id="itensSelectedbykit"> 0/'+modalCategoryTotal+' </div><br>');
                for ( numbProducts = 0; numbProducts < dataProduct.products.length; numbProducts++) {

                    if(modalCategoryProduct != dataProduct.products[numbProducts]['category_product']){
                        for ( numbCategory = 0; numbCategory < dataProduct.products_category.length; numbCategory++) {
                            if(dataProduct.products[numbProducts]['category_product'] == dataProduct.products_category[numbCategory]['products_category_id']){
                                $('#modal-products-cart').append('<strong>'+dataProduct.products_category[numbCategory]['products_category_name']+' </strong><br>');
                            }
                        }
                    }

                    $('#modal-products-cart').append('<input type="radio" class="cart-kit-prod-item" id="cart-kit-prod-' +numbProducts+ '" name="cart_kit_prod_' +dataProduct.products[numbProducts]['category_product']+ '" value="' +dataProduct.products[numbProducts]['id']+ '">');
                    $('#modal-products-cart').append('<label for="cart-kit-prod-' +numbProducts+ '"> '+dataProduct.products[numbProducts]['name']+' </label><br>');

                    modalCategoryProduct = dataProduct.products[numbProducts]['category_product'];
                }
            }

            $('#modal-products-cart').append('<div id="idobservationdiv"><label for="inputDescription">Alguma observação?</label><textarea name="observation" id="idobservationtxt" class="md-textarea form-control" rows="2"></textarea></div>');
        }

        $(document).on("click", ".addproduct-button", function (event) {

            event.preventDefault();
            var product_object = $(this).data("object");
            var product_id = $(this).data("prodid");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('product.showmodal') }}",
                type: "post",
                data : {
                    product_object: product_object,
                    product_id: product_id,
                    store_id : {{$Store->id}},
                    empty_cart: null
                },
                dataType: "json",
                success: function (data) {
                    if(data.success === true){

                        showAddProductModal(data);

                    } else if (data.message == 'diffstore'){

                            $('#diffStoreModal').modal('show');

                            $(document).on("click", "#emptyCartModalProduct", function (event) {

                                event.preventDefault();

                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: "{{ route('product.showmodal') }} ",
                                    type: "post",
                                    data : {
                                        product_object: product_object,
                                        product_id: product_id,
                                        store_id : {{$Store->id}},
                                        empty_cart: true
                                    },
                                    dataType: "json",
                                    success: function (data) {
                                        if(data.success === true){

                                            $('#diffStoreModal').modal('hide');
                                            showAddProductModal(data);

                                        }else{
                                            alert(data.message)
                                        }

                                        console.log(data);

                                    }

                                });

                            });

                    } else {
                        alert(data.message)
                    }

                    console.log(data);

                }

            });

        });

    </script>
@endsection
