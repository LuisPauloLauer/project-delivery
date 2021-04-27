@extends('admin.layout_master.admin_design')

@section('Stylescss')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.css') }}">
    <style>
        .btn-delete-disabled {
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
                    <h4 align="center" style="margin:0;">User ID: <span id="idspanid"></span></h4>
                    <h4 align="center" style="margin:0;">Nome: <span id="idspanname" ></span></h4>
                </div>
                <div class="modal-footer">
                    <form name="formDeleteObject" action="{{ route('usersadm.delete') }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="UserAdm_id" id="idDeleteObject" value="">
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
                    <div class="col-sm-3">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><h5><a href="{{ route('usersadm.create') }}">Cadastrar</a></h5></li>
                            <li class="breadcrumb-item active">Usuários</li>
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
                        @if(! Session::has('StoresUserAdm') )
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
                                <table id="idtableUsersAdm" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Habilitado</th>
                                        <th>Tipo</th>
                                        <th>Nome</th>
                                        <th>Fone</th>
                                        <th>Aniversário</th>
                                        <th width="90">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($listUserAdm as $UserAdm)
                                        <tr>
                                            <td>{{ $UserAdm->id }}</td>
                                            <td>
                                                @if($UserAdm->id !== $UserAuth->id)
                                                    <input id="ckbstatus" name="ckbstatus" class="ckbstatus-bootstrap-switch" type="checkbox" {{ (($UserAdm->status == 'S') ? "checked" : "") }} data-objectid="{{$UserAdm->id}}">
                                                @else
                                                    <h4><span class="badge {{ (($UserAdm->status === 'S') ? "badge-success" : "badge-danger") }}">{{ (($UserAdm->status === 'S') ? "SIM" : "NÃO") }}</span></h4>
                                                @endif
                                            </td>
                                            <td>{{ $UserAdm::find($UserAdm->id)->pesqTypeUserAdm->name }}</td>
                                            <td>{{ $UserAdm->name }}</td>
                                            <td class="TDfone">{{ $UserAdm->fone }}</td>
                                            <td>{{ date('d/m/Y',strtotime($UserAdm->birth)) }}</td>
                                            <td>
                                                <div>
                                                    <a id='idBtnEdit' class="btn btn-large btn-warning" href="{{ route('usersadm.edit', ['usersadm' => $UserAdm->id]) }}"><span class="glyphicon fas fa-edit"></span></a>
                                                    @if($UserAdm->id !== $UserAuth->id)
                                                        <a id='idBtnDelete' class="btn btn-large btn-danger" data-objectid="{{$UserAdm->id}}" data-objectname="{{$UserAdm->name}}" href="#"><span class="glyphicon fas fa-trash"></span></a>
                                                    @else
                                                        <a class="btn btn-large btn-danger btn-delete-disabled" href="#"><span class="glyphicon fas fa-trash"></span></a>
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
                                        <th>Tipo</th>
                                        <th>Nome</th>
                                        <th>Fone</th>
                                        <th>Aniversário</th>
                                        <th width="90">Ações</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        @endif
                        <!-- /.card -->

                        <!-- card -->
                        @if( Session::has('StoresUserAdm') )
                            <div class="card">
                            <div class="card-header d-flex p-0">
                                <ul class="nav nav-pills p-2 col-sm-6">
                                    <?php $numStoresNav = 1 ?>
                                    @if( Session::has('StoresUserAdm') )
                                        @foreach(Session::get('StoresUserAdm') as $store)
                                            @if($numStoresNav == 1)
                                                <li class="nav-item"><a class="nav-link active" href="#tab_{{$numStoresNav}}" data-toggle="tab" onclick="loadDataTable('#idtableUsersAdm_{{$numStoresNav}}A')">{{$store['name']}}</a></li>
                                            @else
                                                <li class="nav-item"><a class="nav-link" href="#tab_{{$numStoresNav}}" data-toggle="tab" onclick="loadDataTable('#idtableUsersAdm_{{$numStoresNav}}A')">{{$store['name']}}</a></li>
                                            @endif
                                            <?php $numStoresNav++ ?>
                                        @endforeach
                                    @endif
                                </ul>
                                @if ($message = Session::get('success'))
                                    <ul class="nav nav-pills p-2">
                                        <div class="alert alert-success alert-block p-2" role="alert">
                                            <button type="button" class="close" data-dismiss="alert">✔</button>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    </ul>
                                @elseif ($message = Session::get('error'))
                                    <ul class="nav nav-pills p-2">
                                        <div class="alert alert-danger alert-block p-2" role="alert">
                                            <button type="button" class="close" data-dismiss="alert">✔</button>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    </ul>
                                @endif
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <?php $numStoresDiv = 1 ?>
                                    @if( Session::has('StoresUserAdm') )
                                        @foreach(Session::get('StoresUserAdm') as $store)
                                            <?php $relUserAdmStore = \App\User::pesqUserAdmByStore($store['id']); ?>
                                            @if($numStoresDiv == 1)
                                                <div class="tab-pane active" id="tab_{{$numStoresDiv}}">
                                                    <table id="idtableUsersAdm_{{$numStoresDiv}}A" class="table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Habilitado</th>
                                                            <th>Tipo</th>
                                                            <th>Nome</th>
                                                            <th>Fone</th>
                                                            <th>Aniversário</th>
                                                            <th width="90">Ações</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($listUserAdm as $UserAdm)
                                                            @foreach ($relUserAdmStore as $userAdmStore)
                                                                @if($UserAdm->id == $userAdmStore->useradm)
                                                                    <tr>
                                                                        <td>{{ $UserAdm->id }}</td>
                                                                        <td>
                                                                            @if($UserAdm->id !== $UserAuth->id)
                                                                                <input id="ckbstatus" name="ckbstatus" class="ckbstatus-bootstrap-switch" type="checkbox" {{ (($UserAdm->status == 'S') ? "checked" : "") }} data-objectid="{{$UserAdm->id}}">
                                                                            @else
                                                                                <h4><span class="badge {{ (($UserAdm->status === 'S') ? "badge-success" : "badge-danger") }}">{{ (($UserAdm->status === 'S') ? "SIM" : "NÃO") }}</span></h4>
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $UserAdm::find($UserAdm->id)->pesqTypeUserAdm->name }}</td>
                                                                        <td>{{ $UserAdm->name }}</td>
                                                                        <td class="TDfone">{{ $UserAdm->fone }}</td>
                                                                        <td>{{ date('d/m/Y',strtotime($UserAdm->birth)) }}</td>
                                                                        <td>
                                                                            <div>
                                                                                <a id='idBtnEdit' class="btn btn-large btn-warning" href="{{ route('usersadm.edit', ['usersadm' => $UserAdm->id]) }}"><span class="glyphicon fas fa-edit"></span></a>
                                                                                @if($UserAdm->id !== $UserAuth->id)
                                                                                    <a id='idBtnDelete' class="btn btn-large btn-danger" data-objectid="{{$UserAdm->id}}" data-objectname="{{$UserAdm->name}}" href="#"><span class="glyphicon fas fa-trash"></span></a>
                                                                                @else
                                                                                    <a class="btn btn-large btn-danger btn-delete-disabled" href="#"><span class="glyphicon fas fa-trash"></span></a>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Habilitado</th>
                                                            <th>Tipo</th>
                                                            <th>Nome</th>
                                                            <th>Fone</th>
                                                            <th>Aniversário</th>
                                                            <th width="90">Ações</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="tab-pane" id="tab_{{$numStoresDiv}}">
                                                    <table id="idtableUsersAdm_{{$numStoresDiv}}A" class="table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Habilitado</th>
                                                            <th>Tipo</th>
                                                            <th>Nome</th>
                                                            <th>Fone</th>
                                                            <th>Aniversário</th>
                                                            <th width="90">Ações</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($listUserAdm as $UserAdm)
                                                            @foreach ($relUserAdmStore as $userAdmStore)
                                                                @if($UserAdm->id == $userAdmStore->useradm)
                                                                    <tr>
                                                                        <td>{{ $UserAdm->id }}</td>
                                                                        <td>
                                                                            @if($UserAdm->id !== $UserAuth->id)
                                                                                <input id="ckbstatus" name="ckbstatus" class="ckbstatus-bootstrap-switch" type="checkbox" {{ (($UserAdm->status == 'S') ? "checked" : "") }} data-objectid="{{$UserAdm->id}}">
                                                                            @else
                                                                                <h4><span class="badge {{ (($UserAdm->status === 'S') ? "badge-success" : "badge-danger") }}">{{ (($UserAdm->status === 'S') ? "SIM" : "NÃO") }}</span></h4>
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $UserAdm::find($UserAdm->id)->pesqTypeUserAdm->name }}</td>
                                                                        <td>{{ $UserAdm->name }}</td>
                                                                        <td class="TDfone">{{ $UserAdm->fone }}</td>
                                                                        <td>{{ date('d/m/Y',strtotime($UserAdm->birth)) }}</td>
                                                                        <td>
                                                                            <div>
                                                                                <a id='idBtnEdit' class="btn btn-large btn-warning" href="{{ route('usersadm.edit', ['usersadm' => $UserAdm->id]) }}"><span class="glyphicon fas fa-edit"></span></a>
                                                                                @if($UserAdm->id !== $UserAuth->id)
                                                                                    <a id='idBtnDelete' class="btn btn-large btn-danger" data-objectid="{{$UserAdm->id}}" data-objectname="{{$UserAdm->name}}" href="#"><span class="glyphicon fas fa-trash"></span></a>
                                                                                @else
                                                                                    <a class="btn btn-large btn-danger btn-delete-disabled" href="#"><span class="glyphicon fas fa-trash"></span></a>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Habilitado</th>
                                                            <th>Tipo</th>
                                                            <th>Nome</th>
                                                            <th>Fone</th>
                                                            <th>Aniversário</th>
                                                            <th width="90">Ações</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            @endif
                                            <?php $numStoresDiv++ ?>
                                        @endforeach
                                    @endif
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        @endif
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
    <!-- Mask -->
    <script src="{{ asset('admin/node_modules/js/jquery.mask.min.js') }}"></script>
    <!-- Bootstrap Switch -->
    <script src="{{ asset('admin/adminLTE/plugins/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
    <!-- Options components default -->
    <script src="{{ asset('admin/node_modules/js/option-components-default.js') }}"></script>
    <script>

        function loadDataTable(dataTable) {
            //alert(dataTable);
        }

        $(document).ready(function () {

            @if(! Session::has('StoresUserAdm') )
                <!-- DataTables -->
                $(function () {
                    $("#idtableUsersAdm").DataTable({
                        "responsive": true,
                        "autoWidth": false,
                    });
                });
            @endif

            @if( Session::has('StoresUserAdm') )
                @for($i = 1; $i <= ($numStoresDiv-1); $i++)
                    <!-- DataTables -->
                    $(function () {
                        $("#idtableUsersAdm_{{$i}}A").DataTable({
                            "responsive": true,
                            "autoWidth": false,
                        });
                    });
                @endfor
            @endif

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
            $('#idcpf_or_cnpj').prop("disabled", true);

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
                    url: "{{ route('usersadm.change.status') }} ",
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
