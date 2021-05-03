@extends('admin.layout_master.admin_design')

@section('Stylescss')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/icheck-bootstrap/icheck-bootstrap.css') }}">
    <style>
        a.disabled {
            opacity: 0.5;
            pointer-events: none;
            cursor: default;
        }
    </style>
@endsection

@section('content')
    <!-- Modal Delete -->
    <div id="idconfirmDeleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Confirma Deletar?</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 align="center" style="margin:0;">Produto ID: <span id="idspanid"></span></h4>
                    <h4 align="center" style="margin:0;">Nome: <span id="idspanname" ></span></h4>
                </div>
                <div class="modal-footer">
                    <form name="formDeleteObject" action="{{ route('products.delete') }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="Product_id" id="idDeleteObject" value="">
                        <button class="btn btn-success" type="submit" id="idOkDelete" >Sim</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Modal Delete -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-1">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><h5><a href="{{ route('products.create') }}">Cadastrar</a></h5></li>
                            <li class="breadcrumb-item active">Produtos</li>
                        </ol>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg-auto">
                        <h5>Obs: na consulta padrão são carregados até 1500 produtos se precisar use a Pesquisa por filtro.</h5>
                    </div>
                </div>
                <form action="{{ route('products.search') }}" method="post">
                    @csrf

                    @include('admin.components.filterProducts')

                    <div class="row mb-1">
                        <div class="col-lg-auto">
                            <button id="idbtnPesqByFilter" style="margin-top: 10px" class="btn btn-primary btn-block" type="submit" value="Submit">Pesquisa por filtro</button>
                        </div>
                        <div class="col-lg-auto">
                            <a  id="idbtnPesqDefault" style="margin-top: 10px" href="{{ route('products.pesq', ['pesqdefault' => 'default']) }}" class="btn btn-primary btn-block">Consulta padrão</a>
                        </div>
                    </div>
                </form>
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
                                    <div class="alert alert-success alert-block" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">✔</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @elseif ($message = Session::get('error'))
                                    <div class="alert alert-danger alert-block" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">✔</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @elseif (isset($msgErrorFilterProducts))
                                    <div class="alert alert-danger alert-block" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">✔</button>
                                        <strong>{{ $msgErrorFilterProducts }}</strong>
                                    </div>
                                @endif
                            </div>
                            <!-- /.card-header -->
                            <!-- card-body -->
                            <div class="card-body">
                                @if(isset($listProduct))
                                <table id="idtableProducts" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Habilitado</th>
                                        <th>Categoria</th>
                                        <th>Nome</th>
                                        <th>Quantidade</th>
                                        <th>Preço</th>
                                        <th width="90">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($listProduct as $Product)
                                        <tr>
                                            <td>{{ $Product->id }}</td>
                                            <td>
                                                <input id="ckbstatus" name="ckbstatus" class="ckbstatus-bootstrap-switch" type="checkbox" {{ (($Product->status == 'S') ? "checked" : "") }} data-objectid="{{$Product->id}}">
                                            </td>
                                            <td>{{ $Product::find($Product->id)->pesqCategoryProduct->name }}</td>
                                            <td>{{ $Product->name }}</td>
                                            <td class="TDamount">{{ $Product->amount }}</td>
                                            <td class="TDunitprice">{{ $Product->unit_price }}</td>
                                            <td>
                                                <div>
                                                    <a target="_blank" id='idBtnEdit' class="btn btn-large btn-warning" href="{{ route('products.edit', ['product' => $Product->id]) }}"><span class="glyphicon fas fa-edit"></span></a>
                                                    <a id='idBtnDelete' class="btn btn-large btn-danger" data-objectid="{{$Product->id}}" data-objectname="{{$Product->name}}" href="#"><span class="glyphicon fas fa-trash"></span></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Habilitado</th>
                                        <th>Categoria</th>
                                        <th>Nome</th>
                                        <th>Quantidade</th>
                                        <th>Preço</th>
                                        <th width="90">Ações</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                @endif
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
    <!-- DataTables -->
    <script src="{{ asset('admin/adminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/adminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/adminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/adminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Masks -->
    <script src="{{ asset('admin/node_modules/js/jquery.mask.min.js') }}"></script>
    <!-- Bootstrap Switch -->
    <script src="{{ asset('admin/adminLTE/plugins/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
    <!-- Options components default -->
    <script src="{{ asset('admin/node_modules/js/option-components-default.js') }}"></script>
    <script>
        $(document).ready(function () {

            <!-- DataTables -->
            $(function () {
                $("#idtableProducts").DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "ordering": false
                });
            });

            <!-- Modal Delete show modal -->
            $(document).on('click','#idBtnDelete', function () {

                var object_id = $(this).data("objectid");
                var object_name = $(this).data("objectname");

                $('#idconfirmDeleteModal').modal('show');

                $('#idDeleteObject').val(object_id);
                $('#idspanid').text(object_id);
                $('#idspanname').text(object_name);
            });

            <!-- Masks -->
            $('.TDamount').mask('000.000.000.000.000', {reverse: true});
            $('.TDunitprice').mask('00000000000000.00', {reverse: true});

            <!-- bootstrap-switch -->
            $('input[class="ckbstatus-bootstrap-switch"]').each(function(){
                var object_id = $(this).data("objectid");

                $(this).bootstrapSwitch({
                    'objectID': object_id,
                    'state' : $(this).prop('checked'),
                    'size': 'normal',
                    'inverse': true,
                    'onColor': 'success',
                    'offColor': 'danger',
                    'onText': 'SIM',
                    'offText': 'NÃO'
                });
            });

            $('.bootstrap-switch-id-ckbstatus').on('switchChange.bootstrapSwitch', function (event, state) {

                var object_id = $(this).data("objectid");
                let elementdiv = $(this);

                if(state){
                    var objStatus = 'S';
                }else{
                    var objStatus = 'N';
                }

                $.ajax({

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('products.change.status') }} ",
                    type: "post",
                    data : {
                        object_id : object_id,
                        object_status : objStatus
                    },
                    dataType: "json",
                    success : function (response) {
                        if(response.success){
                            toastr.success(response.message);
                        } else {
                            if(state){
                                elementdiv.find('input[type="checkbox"]').bootstrapSwitch('state', false, true);
                            }else{
                                elementdiv.find('input[type="checkbox"]').bootstrapSwitch('state', true, true);
                            }
                            toastr.error(response.message);
                        }
                    }
                });

            });

            <!-- Filters -->
            @if(isset($statusckbFilterObject))
            $("#idckbFilterObject").prop('checked', {{$statusckbFilterObject}});
            @endif

            if ($("#idckbFilterObject").is(':checked')){
                $('#idSelectFilterObject').prop("disabled", false);
                $('#idtxtFilterObject').prop("disabled", false);
                $('#idSelectFilterCategoria').prop("disabled", false);
                $('#idbtnPesqByFilter').prop("disabled", false);
                $('#idbtnPesqDefault').addClass('disabled');
            } else {
                $('#idSelectFilterObject').prop("disabled", true);
                $('#idtxtFilterObject').prop("disabled", true);
                $('#idSelectFilterCategoria').prop("disabled", true);
                $('#idbtnPesqByFilter').prop("disabled", true);
                $('#idbtnPesqDefault').removeClass('disabled');
            }

            $('#idckbFilterObject').click(function() {
                if(this.checked){

                    $('#idbtnPesqByFilter').prop("disabled", false);
                    $('#idbtnPesqDefault').addClass('disabled');
                    $('#idSelectFilterObject').prop("disabled", false);

                    var SelectFilterKits = $( "#idSelectFilterObject option:selected" ).val();

                    switch(SelectFilterKits) {
                        case "1":
                            $('#idLblFilterObject').text('ID');
                            $('#idtxtFilterObject').prop("disabled", false);
                            $("#idSelectFilterCategoria").val("T");
                            $('#idSelectFilterCategoria').prop("disabled", true);
                            break;
                        case "2":
                            $('#idLblFilterObject').text('ID PDV');
                            $('#idtxtFilterObject').prop("disabled", false);
                            $("#idSelectFilterCategoria").val("T");
                            $('#idSelectFilterCategoria').prop("disabled", true);
                            break;
                        case "3":
                            $('#idLblFilterObject').text('Código PDV');
                            $('#idtxtFilterObject').prop("disabled", false);
                            $("#idSelectFilterCategoria").val("T");
                            $('#idSelectFilterCategoria').prop("disabled", true);
                            break;
                        case "4":
                            $('#idLblFilterObject').text('Código de barras PDV');
                            $('#idtxtFilterObject').prop("disabled", false);
                            $("#idSelectFilterCategoria").val("T");
                            $('#idSelectFilterCategoria').prop("disabled", true);
                            break;
                        case "5":
                            $('#idLblFilterObject').text('Descrição');
                            $('#idtxtFilterObject').prop("disabled", false);
                            //  $("#idSelectFilterCategoria").val("T");
                            $('#idSelectFilterCategoria').prop("disabled", false);
                            break;
                        case "6":
                            $('#idLblFilterObject').text('Categoria apenas');
                            $('#idtxtFilterObject').val('');
                            $('#idtxtFilterObject').prop("disabled", true);
                            $('#idSelectFilterCategoria').prop("disabled", false);
                            break;
                        default:
                            $('#idLblFilterObject').text('ID');
                            $('#idtxtFilterObject').prop("disabled", false);
                            $("#idSelectFilterCategoria").val("T");
                            $('#idSelectFilterCategoria').prop("disabled", true);
                            break;
                    }

                } else {
                    $('#idSelectFilterObject').prop("disabled", true);
                    $('#idtxtFilterObject').prop("disabled", true);
                    $('#idSelectFilterCategoria').prop("disabled", true);
                    $('#idbtnPesqByFilter').prop("disabled", true);
                    $('#idbtnPesqDefault').removeClass('disabled');
                }
            });

            var SelectFilterKits = $( "#idSelectFilterObject option:selected" ).val();

            switch(SelectFilterKits) {
                case "1":
                    $('#idLblFilterObject').text('ID');
                    $("#idSelectFilterCategoria").val("T");
                    $('#idSelectFilterCategoria').prop("disabled", true);
                    break;
                case "2":
                    $('#idLblFilterObject').text('ID PDV');
                    $("#idSelectFilterCategoria").val("T");
                    $('#idSelectFilterCategoria').prop("disabled", true);
                    break;
                case "3":
                    $('#idLblFilterObject').text('Código PDV');
                    $("#idSelectFilterCategoria").val("T");
                    $('#idSelectFilterCategoria').prop("disabled", true);
                    break;
                case "4":
                    $('#idLblFilterObject').text('Código de barras PDV');
                    $("#idSelectFilterCategoria").val("T");
                    $('#idSelectFilterCategoria').prop("disabled", true);
                    break;
                case "5":
                    $('#idLblFilterObject').text('Descrição');
                    //   $("#idSelectFilterCategoria").val("T");
                    $('#idSelectFilterCategoria').prop("disabled", false);
                    break;
                case "6":
                    $('#idLblFilterObject').text('Categoria apenas');
                    $('#idtxtFilterObject').val('');
                    $('#idtxtFilterObject').prop("disabled", true);
                    $('#idSelectFilterCategoria').prop("disabled", false);
                    break;
                default:
                    $('#idLblFilterObject').text('ID');
                    $("#idSelectFilterCategoria").val("T");
                    $('#idSelectFilterCategoria').prop("disabled", true);
                    break;
            }

            $('#idSelectFilterObject').on('change', function() {
                var SelectFilterKits = this.value

                switch(SelectFilterKits) {
                    case "1":
                        $('#idLblFilterObject').text('ID');
                        $('#idtxtFilterObject').prop("disabled", false);
                        $("#idSelectFilterCategoria").val("T");
                        $('#idSelectFilterCategoria').prop("disabled", true);
                        break;
                    case "2":
                        $('#idLblFilterObject').text('ID PDV');
                        $('#idtxtFilterObject').prop("disabled", false);
                        $("#idSelectFilterCategoria").val("T");
                        $('#idSelectFilterCategoria').prop("disabled", true);
                        break;
                    case "3":
                        $('#idLblFilterObject').text('Código PDV');
                        $('#idtxtFilterObject').prop("disabled", false);
                        $("#idSelectFilterCategoria").val("T");
                        $('#idSelectFilterCategoria').prop("disabled", true);
                        break;
                    case "4":
                        $('#idLblFilterObject').text('Código de barras PDV');
                        $('#idtxtFilterObject').prop("disabled", false);
                        $("#idSelectFilterCategoria").val("T");
                        $('#idSelectFilterCategoria').prop("disabled", true);
                        break;
                    case "5":
                        $('#idLblFilterObject').text('Descrição');
                        $('#idtxtFilterObject').prop("disabled", false);
                        //     $("#idSelectFilterCategoria").val("T");
                        $('#idSelectFilterCategoria').prop("disabled", false);
                        break;
                    case "6":
                        $('#idLblFilterObject').text('Categoria apenas');
                        $('#idtxtFilterObject').val('');
                        $('#idtxtFilterObject').prop("disabled", true);
                        $('#idSelectFilterCategoria').prop("disabled", false);
                        break;
                    default:
                        $('#idLblFilterObject').text('ID');
                        $('#idtxtFilterObject').prop("disabled", false);
                        $("#idSelectFilterCategoria").val("T");
                        $('#idSelectFilterCategoria').prop("disabled", true);
                        break;
                }

            });

        });
    </script>
@endsection
