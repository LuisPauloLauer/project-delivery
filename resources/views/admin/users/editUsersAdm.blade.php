@extends('admin.layout_master.admin_design')

@section('Stylescss')
    <!-- Chosen css -->
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/chosen.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/croppie.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/dropzone-image-avatar.css') }}">
@endsection

@section('content')
    @include('admin.components.modalCroppieImage')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-1">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><h5><a href="{{ route('usersadm.index') }}">Voltar</a></h5></li>
                            <li class="breadcrumb-item active">Alteração de Usuários</li>
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
                                    <form action="{{ route('usersadm.update', ['usersadm' => $UserAdm->id]) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="d-flex justify-content-center">
                                            <div class="div-container-details-image-avatar">
                                                <div class="div-container-content-image-avatar div-content-imagefield-image-avatar">
                                                    <input type="hidden" id="imagebase64" name="imageSave">
                                                    <input type="file" id="id-input-image-avatar" class="input-image-avatar" autocomplete="off" tabindex="-1" name="imageInput" accept="image/*"/>
                                                    <div class="div-placeholder-image-avatar-fixed">
                                                        <div class="div-choose-image-avatar-add" onclick="chargeImage()">
                                                            <div class="div-choose-image-avatar-btn-content">
                                                                <div class="div-choose-image-avatar-btn-icon">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                         xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                         aria-hidden="true" focusable="false" width="26"
                                                                         height="26"
                                                                         style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);"
                                                                         preserveAspectRatio="xMidYMid meet"
                                                                         viewBox="0 0 24 24">
                                                                        <path
                                                                            d="M13 2.03c4.73.47 8.5 4.22 8.95 8.97A9.976 9.976 0 0 1 13 21.93v-2c3.64-.43 6.5-3.32 6.96-6.96A7.994 7.994 0 0 0 13 4.05V2.03m-2 .03v2c-1.43.2-2.78.78-3.9 1.68L5.67 4.26A9.827 9.827 0 0 1 11 2.06M4.26 5.67L5.69 7.1A8.017 8.017 0 0 0 4.05 11h-2c.2-1.96.95-3.81 2.21-5.33M2.06 13h2c.18 1.42.75 2.77 1.63 3.9l-1.42 1.43A10.04 10.04 0 0 1 2.06 13m5.04 5.37c1.13.88 2.48 1.45 3.9 1.63v2c-1.96-.21-3.82-1-5.33-2.26l1.43-1.37M12 7.5L7.5 12H11v4h2v-4h3.5L12 7.5z"
                                                                            fill="#ea1d2c"/>
                                                                    </svg>
                                                                </div>
                                                                Adicionar imagem
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="div-container-info-image-avatar">
                                                    <div class="div-info-image-avatar">
                                                        <span>
                                                            Formatos:
                                                            <b>JPEG, JPG, PNG e SVG</b>
                                                        </span>
                                                    </div>
                                                    <div class="div-info-image-avatar">
                                                        <span>
                                                            Resolução mínima:
                                                            <b>200x200px</b>
                                                        </span>
                                                    </div>
                                                    <div class="div-info-image-avatar">
                                                        <span>
                                                            Tamanho máximo:
                                                            <b>5MB</b>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <input value="{{ $UserAdm->id }}" type="hidden" class="form-control" id="iduseradm"  name="iduseradm">
                                                <label for="inputTpUserAdm">Tipo de usuário:</label><span class="text-danger col-1">{{$errors->first('tpuser')}}</span>
                                                <select id="idtpuseradm" name="tpuser" autofocus class="custom-select d-block w-100">
                                                    @foreach($listTpUsersAdm as $TpUsersAdm)
                                                        <option value="{{ $TpUsersAdm->id }}"
                                                        {{ (($UserAdm->tpuser === $TpUsersAdm->id) ? 'selected' : '' ) }}
                                                        >{{ $TpUsersAdm->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="inputName">Nome:</label><span class="text-danger col-1">{{$errors->first('name')}}</span>
                                                <input value="{{ $UserAdm->name }}" type="text" class="form-control" id="idname"  name="name">
                                            </div>
                                            <div class="col-2">
                                                <label for="inputStatus">Habilitado:</label>
                                                @if($UserAdm->id !== $UserAuth->id)
                                                    <select id="idstatus" name="status" class="custom-select d-block w-100">
                                                        <option value="S"
                                                        {{(($UserAdm->status == 'S') ? "selected" : "")}}>Sim
                                                        </option>
                                                        <option value="N"
                                                        {{(($UserAdm->status == 'N') ? "selected" : "")}}>Não
                                                        </option>
                                                    </select>
                                                @else
                                                    <select id="idstatus" name="status" class="custom-select d-block w-100">
                                                        <option value="{{$UserAdm->status}}">{{(($UserAdm->status == 'S') ? "SIM" : "NÃO")}}</option>
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                        <div id="idSelectStore" class="form-row">
                                            <div class="col">
                                                <?php $teste = 'affiliado' ?>
                                                <label for="inputStore">Lojas:</label>
                                                <select id="idstore" name="store[]" data-placeholder=" selecione uma loja ou mais lojas para usuário..." class="chosen-select" multiple tabindex="4">
                                                    <option value=""></option>
                                                    @foreach($listStore as $Store)
                                                        @if($teste !== $Store->affiliate)
                                                            <optgroup label="{{ $Store::find($Store->id)->pesqAffiliate->fantasy_name }}">
                                                        @endif
                                                                <option value="{{$Store->id}}"
                                                                @foreach($listRelStoresUserAdm as $RelStoreUserAdm)
                                                                    {{(($RelStoreUserAdm->store == $Store->id) ? "selected" : "")}}
                                                                @endforeach
                                                                >{{$Store->name}}</option>
                                                                {{ $teste = $Store->affiliate }}
                                                        @if($teste !== $Store->affiliate)
                                                            </optgroup>
                                                        @endif
                                                        {{ $teste = $Store->affiliate }}
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputCpf">CPF:</label><span class="text-danger col-1">{{$errors->first('cpf')}}</span>
                                                <input value="{{ $UserAdm->cpf }}" id="idcpf"  name="cpf" type="text" readonly class="form-control" >
                                            </div>
                                            <div class="col">
                                                <label for="inputBirth">Data de nascimento:</label><span class="text-danger col-1">{{$errors->first('birth')}}</span>
                                                <input value="{{ $UserAdm->birth }}" id="idbirth"  name="birth" type="date" readonly class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputSex">Sexo:</label><span class="text-danger col-1">{{$errors->first('sex')}}</span>
                                                <select id="idsex" name="sex" class="custom-select d-block w-100">
                                                    <option value="M"
                                                    {{(($UserAdm->sex == 'M') ? "selected" : "")}}>Masculino
                                                    </option>
                                                    <option value="F"
                                                    {{(($UserAdm->sex == 'F') ? "selected" : "")}}>Feminino
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="inputFone">Telefone:</label><span class="text-danger col-1">{{$errors->first('fone')}}</span>
                                                <input value="{{ $UserAdm->fone }}" id="idfone"  name="fone" type="text" placeholder="Ex.: (51) 98888-7777" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputEmail">Email:</label><span class="text-danger col-1">{{$errors->first('email')}}</span>
                                                <input value="{{ $UserAdm->email }}" id="idemail"  name="email" type="text" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputPassword">Senha:</label><span class="text-danger col-1">{{$errors->first('password')}}</span>
                                                <input id="idpassword"  name="password" type="password" class="form-control" >
                                            </div>
                                            <div class="col">
                                                <label for="inputConfirmPassword">Confirma senha:</label><span class="text-danger col-1">{{$errors->first('password_confirmation')}}</span>
                                                <input id="idconfirmpassword"  name="password_confirmation" type="password" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <hr class="mb-4">
                                                <button class="btn btn-primary btn-lg btn-block" type="submit" value="Submit">Alterar</button>
                                            </div>
                                        </div>
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
    <script src="{{ asset('admin/node_modules/js/croppie.js')}}"></script>
    <script>
        var inputImage = document.querySelector("#id-input-image-avatar"),
            divPlaceholderFixed = document.querySelector(".div-placeholder-image-avatar-fixed");

        var $uploadCrop,
            imagenInput;

        $(document).ready(function () {

            $(".chosen-select").chosen({width: "100%"});

            $('#idcpf').mask('000.000.000-00', {reverse: true});
            $('#idfone').mask('(00) 00000-0000');

            var typeUserAdmSelected = $( "#idtpuseradm option:selected" ).val();

            switch(typeUserAdmSelected) {
                case '1':
                    $("#idSelectStore").hide(820);
                    break;
                case '2':
                    //alert('Pessoa jurídica');
                    $("#idSelectStore").hide(820);
                    break;
                case '3':
                    $("#idSelectStore").show(820);
                    break;
                case '4':
                    $("#idSelectStore").show(820);
                    break;
                case '5':
                    $("#idSelectStore").show(820);
                    break;
                default:
                    alert('Tipo de usuário incorreto!!!');
                    break;
            }

            $('#idtpuseradm').on('change', function() {
                var typeUserAdmSelected = this.value

                switch(typeUserAdmSelected) {
                    case '1':
                        $("#idSelectStore").hide(820);
                        break;
                    case '2':
                        //alert('Pessoa jurídica');
                        $("#idSelectStore").hide(820);
                        break;
                    case '3':
                        $("#idSelectStore").show(820);
                        break;
                    case '4':
                        $("#idSelectStore").show(820);
                        break;
                    case '5':
                        $("#idSelectStore").show(820);
                        break;
                    default:
                        alert('Tipo de usuário incorreto!!!');
                        break;
                }

            });

            @if(!is_null($UserAdm->path_image))
                divPlaceholderFixed.classList.remove("div-placeholder-image-avatar-ishovered");
                $( ".div-placeholder-image-avatar-fixed" ).remove();
                $( ".div-container-content-image-avatar" ).append('<div class="div-imagepreview-image-avatar">\n' +
                    '                                                 <img class="img-imagepreview-image-avatar" id="id-img-imagepreview-image-avatar" />\n' +
                    '                                              </div>');
                $( ".div-container-content-image-avatar" ).append('<div class="div-placeholder-image-avatar-fixed"></div>');

                $( ".div-placeholder-image-avatar-fixed" ).append('<div class="div-choose-image-avatar-update div-choose-image-avatar-flex1x1" onclick="chargeImage()">\n' +
                    '                                                            <div class="div-choose-image-avatar-btn-content">\n' +
                    '                                                                <div class="div-choose-image-avatar-btn-icon">\n' +
                    '                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="26" height="26" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 17 16"><g fill="#ea1d2c" fill-rule="evenodd"><path d="M2.539 8.001c.828 0 1.379-.551 1.379-1.379l-.004-.435s-.205-1.233 1.44-1.233L12.015 5v1.759a.83.83 0 0 0 1.17 0l2.573-2.572a.83.83 0 0 0 0-1.17L13.185.292a.83.83 0 0 0-1.17 0v1.845c-.161-.047-6.453-.074-6.453-.074c-3.711 0-4.523 2.429-4.523 3.407v1.031a1.5 1.5 0 0 0 1.5 1.5z"/><path d="M14.5 8.042c-.828 0-1.5.584-1.5 1.412l.002.957c-.045.357-.645.59-1.525.59H4.938l-.002-1.559a.83.83 0 0 0-1.17 0l-2.572 2.572a.83.83 0 0 0 0 1.17l2.572 2.572a.826.826 0 0 0 1.17 0l.002-1.851h6.539c3.711 0 4.523-2.442 4.523-3.421V9.453c0-.827-.672-1.411-1.5-1.411z"/></g></svg>' +
                    '                                                                </div>Alterar imagem'+
                    '                                                            </div>\n' +
                    '                                                    </div>');
                $( ".div-placeholder-image-avatar-fixed" ).append('<div class="div-choose-image-avatar-separateline"></div>');
                $( ".div-placeholder-image-avatar-fixed" ).append('<div class="div-choose-image-avatar-delete div-choose-image-avatar-flex1x1" onclick="deleteImage()">\n' +
                    '                                                            <div class="div-choose-image-avatar-btn-content">\n' +
                    '                                                                <div class="div-choose-image-avatar-btn-icon">\n' +
                    '                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="26" height="26" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4z" fill="#ea1d2c"/></svg>\n' +
                    '                                                                </div>Excluir imagem'+
                    '                                                            </div>\n' +
                    '                                                    </div>');


                divPlaceholderFixed = document.querySelector(".div-placeholder-image-avatar-fixed");

                @if( ($UserAdm->tpuser == 1) || ($UserAdm->tpuser == 2) )
                    $('#id-img-imagepreview-image-avatar').attr('src', '{{ $pathImagens }}/usersAdm/administrator/{{ $UserAdm->id}}/small/{{ $UserAdm->path_image }}');
                @else
                    $('#id-img-imagepreview-image-avatar').attr('src', '{{ $pathImagens }}/usersAdm/affiliate_id_{{$AffiliateStore->affiliate}}/{{ $UserAdm->id}}/small/{{ $UserAdm->path_image }}');
                @endif

                $('#imagebase64').val('{{ $UserAdm->path_image }}');

            @endif

        });

        /*$('.input-images-1').dropzoneImageObject({
            imagesInputName: 'image',
            maxFiles: 5
        });*/

        function readFile(input) {
            //alert('teste1');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (event) {

                    imagenInput = event.target.result;

                    var image = new Image();

                    //load image to test resolution parameters
                    image.src = event.target.result;

                    image.onload = function () {

                        var imageHeight = this.height;
                        var imageWidth = this.width;

                        if (imageHeight < 200 || imageWidth < 200) {
                            alert("Resolução mínima da imagem deve ser: 200x200px");
                            inputImage.value = "";
                            return false;
                        } else {
                            $('.upload-demo').addClass('ready');
                            $('#cropImagePop').modal('show');
                            return true;
                        }
                    };
                }
                reader.readAsDataURL(input.files[0]);
            }
            else {
                alert("Sorry - you're browser doesn't support the FileReader API");
            }
        }

        function chargeImage(){
            $('.input-image-avatar').click();
        }

        function deleteImage(){
            inputImage.value = "";
            $('#imagebase64').removeAttr('value');
            $(".div-imagepreview-image-avatar").remove();
            $( ".div-placeholder-image-avatar-fixed" ).remove();
            $( ".div-container-content-image-avatar" ).append('<div class="div-placeholder-image-avatar-fixed"></div>');
            $( ".div-placeholder-image-avatar-fixed" ).append('<div class="div-choose-image-avatar-add" onclick="chargeImage()">\n' +
                '                                                            <div class="div-choose-image-avatar-btn-content">\n' +
                '                                                                <div class="div-choose-image-avatar-btn-icon">\n' +
                '                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="26" height="26" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M13 2.03c4.73.47 8.5 4.22 8.95 8.97A9.976 9.976 0 0 1 13 21.93v-2c3.64-.43 6.5-3.32 6.96-6.96A7.994 7.994 0 0 0 13 4.05V2.03m-2 .03v2c-1.43.2-2.78.78-3.9 1.68L5.67 4.26A9.827 9.827 0 0 1 11 2.06M4.26 5.67L5.69 7.1A8.017 8.017 0 0 0 4.05 11h-2c.2-1.96.95-3.81 2.21-5.33M2.06 13h2c.18 1.42.75 2.77 1.63 3.9l-1.42 1.43A10.04 10.04 0 0 1 2.06 13m5.04 5.37c1.13.88 2.48 1.45 3.9 1.63v2c-1.96-.21-3.82-1-5.33-2.26l1.43-1.37M12 7.5L7.5 12H11v4h2v-4h3.5L12 7.5z" fill="#ea1d2c"/></svg>' +
                '                                                                </div>Adicionar imagem'+
                '                                                            </div>\n' +
                '                                                    </div>');

            divPlaceholderFixed = document.querySelector(".div-placeholder-image-avatar-fixed");

        }

        $('.input-image-avatar').on('change', function () {

            var size = this.files[0].size;

            if(size > (5 * 1024 * 1024)) { //5MB
                alert('Peso máximo da imagem deve ser: 5mb');
                inputImage.value = "";
            } else {
                var ext = this.value.match(/\.(.+)$/)[1];
                switch (ext) {
                    case 'JPG':
                    case 'jpg':
                    case 'JPEG':
                    case 'jpeg':
                    case 'PNG':
                    case 'png':
                    case 'SVG':
                    case 'svg':
                        readFile(this);
                        break;
                    default:
                        alert('Formatos da imagem deve ser: JPG, JPEG, PNG e SVG');
                        inputImage.value = "";
                }
            }

        });

        $uploadCrop = $('#upload-demo').croppie({
            enableExif: false,
            enableOrientation: true,
            enforceBoundary: true,
            viewport: {
                width: 200,
                height: 200,
                type: 'circle'
            },
            boundary: {
                width: '100%',
                height: 250
            }
        });

        $('#cropImagePop').on('shown.bs.modal', function(){
            $uploadCrop.croppie('bind', {
                url: imagenInput
                //orientation: 4
                //points: [77,469,280,739]
            }).then(function(){
                console.log('jQuery bind complete');
            });
        });

        $('#cancelImageBtn').on('click', function () {
            inputImage.value = "";
        });

        $('#cropImageBtn').on('click', function (event) {
            $uploadCrop.croppie('result', {
                type: 'base64',
                format: 'jpeg',
                backgroundColor : "#ffffff",
                size:{ width:null, height:null }
            }).then(function (resp) {

                divPlaceholderFixed.classList.remove("div-placeholder-image-avatar-ishovered");
                $( ".div-placeholder-image-avatar-fixed" ).remove();
                $( ".div-container-content-image-avatar" ).append('<div class="div-imagepreview-image-avatar">\n' +
                    '                                                 <img class="img-imagepreview-image-avatar" id="id-img-imagepreview-image-avatar" />\n' +
                    '                                              </div>');
                $( ".div-container-content-image-avatar" ).append('<div class="div-placeholder-image-avatar-fixed"></div>');

                $( ".div-placeholder-image-avatar-fixed" ).append('<div class="div-choose-image-avatar-update div-choose-image-avatar-flex1x1" onclick="chargeImage()">\n' +
                    '                                                            <div class="div-choose-image-avatar-btn-content">\n' +
                    '                                                                <div class="div-choose-image-avatar-btn-icon">\n' +
                    '                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="26" height="26" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 17 16"><g fill="#ea1d2c" fill-rule="evenodd"><path d="M2.539 8.001c.828 0 1.379-.551 1.379-1.379l-.004-.435s-.205-1.233 1.44-1.233L12.015 5v1.759a.83.83 0 0 0 1.17 0l2.573-2.572a.83.83 0 0 0 0-1.17L13.185.292a.83.83 0 0 0-1.17 0v1.845c-.161-.047-6.453-.074-6.453-.074c-3.711 0-4.523 2.429-4.523 3.407v1.031a1.5 1.5 0 0 0 1.5 1.5z"/><path d="M14.5 8.042c-.828 0-1.5.584-1.5 1.412l.002.957c-.045.357-.645.59-1.525.59H4.938l-.002-1.559a.83.83 0 0 0-1.17 0l-2.572 2.572a.83.83 0 0 0 0 1.17l2.572 2.572a.826.826 0 0 0 1.17 0l.002-1.851h6.539c3.711 0 4.523-2.442 4.523-3.421V9.453c0-.827-.672-1.411-1.5-1.411z"/></g></svg>' +
                    '                                                                </div>Alterar imagem'+
                    '                                                            </div>\n' +
                    '                                                    </div>');
                $( ".div-placeholder-image-avatar-fixed" ).append('<div class="div-choose-image-avatar-separateline"></div>');
                $( ".div-placeholder-image-avatar-fixed" ).append('<div class="div-choose-image-avatar-delete div-choose-image-avatar-flex1x1" onclick="deleteImage()">\n' +
                    '                                                            <div class="div-choose-image-avatar-btn-content">\n' +
                    '                                                                <div class="div-choose-image-avatar-btn-icon">\n' +
                    '                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="26" height="26" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4z" fill="#ea1d2c"/></svg>\n' +
                    '                                                                </div>Excluir imagem'+
                    '                                                            </div>\n' +
                    '                                                    </div>');


                divPlaceholderFixed = document.querySelector(".div-placeholder-image-avatar-fixed");

                $('#id-img-imagepreview-image-avatar').attr('src', resp);

                $('#imagebase64').val(resp);

                $('#cropImagePop').modal('hide');

            });
        });
        // End upload preview image

        $( 'div.div-content-imagefield-image-avatar' ).hover(
            function() {
                divPlaceholderFixed.classList.add("div-placeholder-image-avatar-ishovered");

            }, function() {
                divPlaceholderFixed.classList.remove("div-placeholder-image-avatar-ishovered");
            }
        );

    </script>
@endsection
