@extends('admin.layout_master.admin_design')

@section('Stylescss')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.css') }}">
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
                    <h4 align="center" style="margin:0;">Loja ID: <span id="idspanid"></span></h4>
                    <h4 align="center" style="margin:0;">Nome: <span id="idspanname" ></span></h4>
                </div>
                <div class="modal-footer">
                    <form name="formDeleteObject" action="{{ route('stores.delete') }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="Store_id" id="idDeleteObject" value="">
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
                            <li class="breadcrumb-item"><h5><a href="{{ route('stores.create') }}">Cadastrar</a></h5></li>
                            <li class="breadcrumb-item active">Lojas</li>
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
                                    <div class="alert alert-success alert-block" role="alert">
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
                                <table id="idtableStores" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Habilitado</th>
                                        <th>Afiliado</th>
                                        <th>Segmento</th>
                                        <th>Nome</th>
                                        <th>email</th>
                                        <th>Telefone</th>
                                        <th width="90">Ações</th>
                                        <th>Imagen</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($listStore as $Store)
                                        <tr>
                                            <td>{{ $Store->id }}</td>
                                            <td>
                                                <input id="ckbstatus" name="ckbstatus" class="ckbstatus-bootstrap-switch" type="checkbox" {{ (($Store->status == 'S') ? "checked" : "") }} data-objectid="{{$Store->id}}">
                                            </td>
                                            <td>{{ $Store::find($Store->id)->pesqAffiliate->fantasy_name }}</td>
                                            <td>{{ $Store::find($Store->id)->pesqSegment->name }}</td>
                                            <td>{{ $Store->name }}</td>
                                            <td>{{ $Store->email }}</td>
                                            <td class="TDfone">{{ $Store->fone1 }}</td>
                                            <td>
                                                <div>
                                                    <a id='idBtnEdit' class="btn btn-large btn-warning" href="{{ route('stores.edit', ['store' => $Store->id]) }}"><span class="glyphicon fas fa-edit"></span></a>
                                                    <a id='idBtnDelete' class="btn btn-large btn-danger" data-objectid="{{$Store->id}}" data-objectname="{{$Store->name}}" href="#"><span class="glyphicon fas fa-trash"></span></a>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    @if(!empty($Store->path_image_logo))
                                                        <a href="{{ $pathImagens }}/stores/logo/{{ $Store->id}}/small/{{ $Store->path_image_logo }}">
                                                            <img src="{{ $pathImagens }}/stores/logo/{{ $Store->id}}/small/{{ $Store->path_image_logo }}"/>
                                                        </a>
                                                    @else
                                                        <h5><span class="badge badge-info">sem imagen</span></h5>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Habilitado</th>
                                        <th>Afiliado</th>
                                        <th>Segmento</th>
                                        <th>Nome</th>
                                        <th>email</th>
                                        <th>Telefone</th>
                                        <th width="90">Ações</th>
                                        <th>Imagen</th>
                                    </tr>
                                    </tfoot>
                                </table>
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
    <script src="{{ asset('admin/node_modules/js/jquery.mask.min.js') }}"></script>
    <!-- Bootstrap Switch -->
    <script src="{{ asset('admin/adminLTE/plugins/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
    <!-- Options components default -->
    <script src="{{ asset('admin/node_modules/js/option-components-default.js') }}"></script>
    <script>
        $(document).ready(function () {

            <!-- DataTables -->
            $(function () {
                $("#idtableStores").DataTable({
                    "responsive": true,
                    "autoWidth": false,
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

            $('.TDfone').mask('(00) 00000-0000');

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
                    url: "{{ route('stores.change.status') }} ",
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

        });
    </script>
@endsection
