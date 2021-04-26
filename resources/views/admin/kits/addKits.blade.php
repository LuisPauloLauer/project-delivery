@extends('admin.layout_master.admin_design')

@section('Stylescss')
    <!-- Chosen css -->
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/chosen.min.css') }}">
    <!-- plugin croppie  -->
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/croppie.css') }}">
    <!-- plugin upload imagens  -->
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/image-uploader.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-1">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><h5><a href="{{ route('kits.pesq', ['pesqdefault' => 'index']) }}">Voltar</a></h5></li>
                            <li class="breadcrumb-item active">Cadastro de Kits</li>
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
                        <!-- card -->
                        <div class="card">
                            <!-- card-header -->
                            <div class="card-header">
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert">✔</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @elseif ($message = Session::get('error'))
                                    <div class="alert alert-danger alert-block" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">✔</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif
                            </div>
                            <!-- /.card-header -->
                            <!-- card-body -->
                            <div class="card-body">
                                <div class="card-body-content align-self-center">
                                    <form action="{{ route('kits.store') }}" method="post" enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputStore">Loja:</label><span class="text-danger col-1">{{$errors->first('store')}}</span>
                                                <input value="{{$Store->name}}" type="text" class="form-control" id="idstorename" name="store_name">
                                                <input type="hidden" name="store" id="idstore" value="{{$Store->id}}">
                                            </div>
                                            <div class="col">
                                                <label for="inputCategoryproduct">Categoria:</label><span class="text-danger col-1">{{$errors->first('category_product')}}</span>
                                                <select id="idcategoryproduct" name="category_product" data-placeholder="selecione uma categoria..." class="chosen-select" autofocus>
                                                    <option value=""></option>
                                                    @foreach($listCategoriesProduct as $categoriesproduct)
                                                        <option value="{{$categoriesproduct->id}}">{{$categoriesproduct->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputIdpdv">ID pdv:</label><span class="text-danger col-1">{{$errors->first('id_pdv_store')}}</span>
                                                <input type="text" class="form-control" id="idpdv"  name="id_pdv_store" >
                                            </div>
                                            <div class="col">
                                                <label for="inputCodigopdv">Código pdv:</label><span class="text-danger col-1">{{$errors->first('codigo_pdv_store')}}</span>
                                                <input type="text" class="form-control" id="idcodigopdv"  name="codigo_pdv_store" >
                                            </div>
                                            <div class="col">
                                                <label for="inputCodigobarraspdv">Cód. barras pdv:</label><span class="text-danger col-1">{{$errors->first('codigo_barras_pdv_store')}}</span>
                                                <input type="text" class="form-control" id="idcodigobarraspdv"  name="codigo_barras_pdv_store" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputName">Nome do Kit:</label><span class="text-danger col-1">{{$errors->first('name')}}</span>
                                                <input type="text" class="form-control" id="idname"  name="name" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputAmount">Quantidade:</label><span class="text-danger col-1">{{$errors->first('amount')}}</span>
                                                <input type="text" class="form-control" id="idamount"  name="amount">
                                            </div>
                                            <div class="col">
                                                <label for="inputUnitprice">Preço:</label><span class="text-danger col-1">{{$errors->first('unit_price')}}</span>
                                                <input type="text" class="form-control" id="idunitprice"  name="unit_price">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputUnitpromotionprice">Preço promocional:</label><span class="text-danger col-1">{{$errors->first('unit_promotion_price')}}</span>
                                                <input class="form-control" name="unit_promotion_price" type="text" id="idunitpromotionprice">
                                            </div>
                                            <div class="col">
                                                <label for="inputUnitdiscount">Percentual disconto:</label><span class="text-danger col-1">{{$errors->first('unit_discount')}}</span>
                                                <input class="form-control" name="unit_discount" type="text" id="idunitdiscount">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputDescription">Descrição do Kit:</label><span class="text-danger col-1">{{$errors->first('description')}}</span>
                                                <textarea name="description" id="iddescription" class="md-textarea form-control" rows="4"></textarea>
                                            </div>
                                        </div>
                                        <div class="input-field">
                                            <label class="active">Imagens do Kit:</label>
                                            <div class="input-images-1" style="padding-top: .5rem;"></div>
                                        </div>
                                        <hr class="mb-4">
                                        <button class="btn btn-primary btn-lg btn-block" type="submit" value="Submit">Cadastrar</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
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
    <script src="{{ asset('admin/node_modules/js/chosen.jquery.min.js') }}"></script>
    <!-- plugin croppie  -->
    <script src="{{ asset('admin/node_modules/js/croppie.js')}}"></script>
    <!-- plugin upload imagens  -->
    <script src="{{ asset('admin/node_modules/js/image-uploader.js')}}"></script>
    <script>

        $(".chosen-select").chosen({width: "100%"});

        $('#idamount').mask('000.000.000.000.000', {reverse: true});
        $('#idunitprice').mask('00000000000000.00', {reverse: true});
        $('#idunitpromotionprice').mask('00000000000000.00', {reverse: true});
        $('#idunitdiscount').prop("disabled", true);
        $('#idstorename').prop("disabled", true);

        $('.input-images-1').imageUploader({
            imagesInputName: 'imagen',
            maxSize: (5 * 1024 * 1024),
            maxFiles: 5
        });

    </script>
@endsection
