@extends('admin.layout_master.admin_design')

@section('Stylescss')
    <!-- Chosen css -->
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/chosen.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/croppie.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/cropper.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/dropzone-image-logostore.css') }}">
@endsection

@section('validations_javascript')
    <script src="{{ asset('admin/node_modules/validation/validate_zipcode.js') }}"></script>
@endsection

@section('content')
    <div id="cropImagePopCapa" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Editor de imagem</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="modal-image-capa" src="https://avatars0.githubusercontent.com/u/3456749">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelImageCapaBtn" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="cropImageCapaBtn" class="btn btn-success">Redimensionar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="cropImagePopLogo" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Editor de imagem</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="id-upload-demo-logo" class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelImageLogoBtn" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="cropImageLogoBtn" class="btn btn-success">Redimensionar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-1">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><h5><a href="{{ route('stores.index') }}">Voltar</a></h5></li>
                            <li class="breadcrumb-item active">Cadastro de Lojas</li>
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
                                    <form action="{{ route('stores.update', ['store' => $Store->id]) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="profile-restaurant-edit-form__item profile-restaurant-edit-form__item--cover-logo-container">
                                            <div class="tour-box tour-box--event-setup-profile-tour-cover profile-restaurant-edit-form__cover-image">
                                                <div class="profile-restaurant-edit-form__field profile-restaurant-edit-form__field--cover input-group">
                                                    <div class="dropzone dropzone--inline dropzone--squared">
                                                        <div class="dropzone__area">
                                                            <div class="dropzone-preview dropzone__preview">
                                                                @if(!is_null($Store->path_image_capa))
                                                                    <img src="{{ $pathImagens }}/stores/capa/{{ $Store->id}}/large/{{ $Store->path_image_capa }}" alt id="id-img-imagepreview-image-capastore" class="dropzone-preview__image">
                                                                @else
                                                                    <img src="" alt id="id-img-imagepreview-image-capastore" class="dropzone-preview__image">
                                                                @endif
                                                                <div class="dropzone-preview__overlay" onclick="chargeImage('capa')">
                                                                    <div class="dropzone-overlay-cover">
                                                                        <div class="dropzone-overlay-cover__content">
                                                                            <img src="{{$pathFilesImages}}/no_capastore.svg"
                                                                                 alt="cam-placeholder" class="dropzone-overlay-cover__image">
                                                                            <div class="dropzone-overlay-cover__text">
                                                                                Adicionar capa
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" id="imagebase64capa" name="imageCapaSave">
                                                            <input type="file" id="id-input-image-capastore" class="input-image-logostore" autocomplete="off" tabindex="-1" name="imageCapaInput" accept="image/*"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="div-container-content-image-logostore div-content-imagefield-image-logostore">
                                                <input type="hidden" id="imagebase64logo" name="imageLogoSave">
                                                <input type="file" id="id-input-image-logostore" class="input-image-logostore" autocomplete="off" tabindex="-1" name="imageLogoInput" accept="image/*"/>
                                                <div class="div-placeholder-image-logostore-fixed">
                                                    <div class="div-choose-image-logostore-add" onclick="chargeImage('logo')">
                                                        <div class="div-choose-image-logostore-btn-content">
                                                            <div class="div-choose-image-logostore-btn-icon">
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
                                                            Adicionar logo
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputAffiliate">Afiliado:</label>
                                                <input value="{{ $Affiliate->fantasy_name }}" id="idaffiliate"  name="affiliate" type="text" class="form-control">
                                            </div>
                                            <div class="col">
                                                <label for="inputSegment">Segmento:</label>
                                                <input value="{{ $Segment->name }}" id="idsegment"  name="segment" type="text" class="form-control">
                                            </div>
                                            <div class="col col-2">
                                                <label for="inputStatus">Habilitado:</label>
                                                <select id="idstatus" name="status" autofocus class="custom-select d-block w-100">
                                                    <option value="S"
                                                            {{(($Store->status == 'S') ? "selected" : "")}}>Sim
                                                    </option>
                                                    <option value="N"
                                                            {{(($Store->status == 'N') ? "selected" : "")}}>Não
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputCategoryStore">Categoria:</label><span class="text-danger col-1">{{$errors->first('categorystore')}}</span>
                                                <select id="idcategorystore" name="categorystore[]" data-placeholder=" selecione uma categoria ou mais categorias para loja..." class="chosen-select" multiple tabindex="4">
                                                    <option value=""></option>
                                                    @foreach($listCategoryStore as $CategoryStore)
                                                            <option value="{{$CategoryStore->id}}"
                                                            @foreach($listRelCategoryStore as $RelCategoryStore)
                                                                    {{(($RelCategoryStore->category == $CategoryStore->id) ? "selected" : "")}}
                                                            @endforeach
                                                            >{{$CategoryStore->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputName">Nome da loja:</label><span class="text-danger col-1">{{$errors->first('name')}}</span>
                                                <input value="{{ $Store->name }}" id="idname"  name="name" type="text" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputZipCode">Cep:</label><span class="text-danger col-1">{{$errors->first('zip_code')}}</span>
                                                <input value="{{ $Store->zip_code }}" id="idzipcode"  name="zip_code" type="text" value="" size="10" maxlength="9" onblur="pesquisacep(this.value);" class="form-control" >
                                            </div>
                                            <div class="col col-6">
                                                <label for="inputStreet">Rua:</label><span class="text-danger col-1">{{$errors->first('street')}}</span>
                                                <input value="{{ $Store->street }}" id="idstreet"  name="street" type="text" class="form-control" >
                                            </div>
                                            <div class="col">
                                                <label for="inputNumber">N:</label><span class="text-danger col-1">{{$errors->first('number')}}</span>
                                                <input value="{{ $Store->number }}" id="idnumber"  name="number" type="text" placeholder="Número" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputDistrict">Bairro:</label><span class="text-danger col-1">{{$errors->first('district')}}</span>
                                                <input value="{{ $Store->district }}" id="iddistrict"  name="district" type="text" class="form-control" >
                                            </div>
                                            <div class="col">
                                                <label for="inputComplement">Complemento:</label><span class="text-danger col-1">{{$errors->first('complement')}}</span>
                                                <input value="{{ $Store->complement }}" id="idcomplement"  name="complement" type="text" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col col-6">
                                                <label for="inputCity">Cidade:</label>
                                                <input id="idcity"  name="city" type="text" class="form-control" >
                                            </div>
                                            <div class="col">
                                                <label for="inputUF">Estado:</label>
                                                <input id="iduf"  name="uf" type="text" class="form-control" >
                                            </div>
                                            <div class="col">
                                                <label for="inputIBGE">IBGE:</label>
                                                <input id="idibgetela"  name="ibgetela" type="text" class="form-control" >
                                                <input id="idibge"  name="ibge" type="hidden" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputFone1">Fone(1):</label><span class="text-danger col-1">{{$errors->first('fone1')}}</span>
                                                <input value="{{ $Store->fone1 }}" id="idfone1"  name="fone1" type="text" placeholder="Ex.: (51) 98888-7777" class="form-control" >
                                            </div>
                                            <div class="col">
                                                <label for="inputFone2">Fone(2):</label><span class="text-danger col-1">{{$errors->first('fone2')}}</span>
                                                <input value="{{ $Store->fone2 }}" id="idfone2"  name="fone2" type="text" placeholder="Ex.: (51) 98888-7777" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputEmail">Email:</label><span class="text-danger col-1">{{$errors->first('email')}}</span>
                                                <input value="{{ $Store->email }}" id="idemail"  name="email" type="text" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputDescription">Descrição:</label><span class="text-danger col-1">{{$errors->first('description')}}</span>
                                                <textarea name="description" id="iddescription" class="md-textarea form-control" rows="4">{{ $Store->description }}</textarea>
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
    <script src="{{ asset('admin/node_modules/js/cropper.js')}}"></script>
    <script>

        //Capa
        var imagepreviewCapa = document.getElementById('id-img-imagepreview-image-capastore');
        var inputImageCapa = document.getElementById('id-input-image-capastore');
        var imageModalCapa = document.getElementById('modal-image-capa');
        var modalCapa = $('#cropImagePopCapa');
        var cropperCapa;
        //Logo
        var divPlaceholderFixed = document.querySelector(".div-placeholder-image-logostore-fixed");
        var inputImageLogo = document.getElementById('id-input-image-logostore');
        var modalLogo = $('#cropImagePopLogo');
        var imageModalLogo;
        var uploadCropLogo;

        $(document).ready(function () {

            $(".chosen-select").chosen({width: "100%"});

            $('#idzipcode').mask('00000-000');
            $('#idnumber').mask('000.000.000', {reverse: true});
            $('#idfone1').mask('(00) 00000-0000');
            $('#idfone2').mask('(00) 00000-0000');

            pesquisacep($('#idzipcode').val(), true);

            $('#idcity').prop("disabled", true);
            $('#iduf').prop("disabled", true);
            $('#idibgetela').prop("disabled", true);
            $('#idaffiliate').prop("disabled", true);
            $('#idsegment').prop("disabled", true);


            @if(!is_null($Store->path_image_logo))
                divPlaceholderFixed.classList.remove("div-placeholder-image-logostore-ishovered");
                $( ".div-placeholder-image-logostore-fixed" ).remove();
                $( ".div-container-content-image-logostore" ).append('<div class="div-imagepreview-image-logostore">\n' +
                    '                                                 <img class="img-imagepreview-image-logostore" id="id-img-imagepreview-image-logostore" />\n' +
                    '                                              </div>');
                $( ".div-container-content-image-logostore" ).append('<div class="div-placeholder-image-logostore-fixed"></div>');
                $( ".div-placeholder-image-logostore-fixed" ).append('<div class="div-choose-image-logostore-add" onclick="chargeImage()">\n' +
                    '                                                            <div class="div-choose-image-logostore-btn-content">\n' +
                    '                                                                <div class="div-choose-image-logostore-btn-icon">\n' +
                    '                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="26" height="26" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 17 16"><g fill="#ea1d2c" fill-rule="evenodd"><path d="M2.539 8.001c.828 0 1.379-.551 1.379-1.379l-.004-.435s-.205-1.233 1.44-1.233L12.015 5v1.759a.83.83 0 0 0 1.17 0l2.573-2.572a.83.83 0 0 0 0-1.17L13.185.292a.83.83 0 0 0-1.17 0v1.845c-.161-.047-6.453-.074-6.453-.074c-3.711 0-4.523 2.429-4.523 3.407v1.031a1.5 1.5 0 0 0 1.5 1.5z"/><path d="M14.5 8.042c-.828 0-1.5.584-1.5 1.412l.002.957c-.045.357-.645.59-1.525.59H4.938l-.002-1.559a.83.83 0 0 0-1.17 0l-2.572 2.572a.83.83 0 0 0 0 1.17l2.572 2.572a.826.826 0 0 0 1.17 0l.002-1.851h6.539c3.711 0 4.523-2.442 4.523-3.421V9.453c0-.827-.672-1.411-1.5-1.411z"/></g></svg>' +
                    '                                                                </div>Alterar logo'+
                    '                                                            </div>\n' +
                    '                                                    </div>');

                divPlaceholderFixed = document.querySelector(".div-placeholder-image-logostore-fixed");

                $('#id-img-imagepreview-image-logostore').attr('src', '{{ $pathImagens }}/stores/logo/{{ $Store->id}}/small/{{ $Store->path_image_logo }}');
            @endif

        });

        /*$('.input-images-1').dropzoneImageObject({
            imagesInputName: 'image',
            maxFiles: 5
        }); */

        function readFile(input,pOpcImage) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (event) {

                    imageModalLogo = event.target.result;
                    imageModalCapa.src = event.target.result;

                    var imageInfo = new Image();

                    //load imageModalCapa to test resolution parameters
                    imageInfo.src = event.target.result;

                    imageInfo.onload = function () {

                        var imageHeight = this.height;
                        var imageWidth = this.width;

                        if(pOpcImage === 'capa'){
                            if (imageHeight < 200 || imageWidth < 800) {
                                alert("Resolução mínima da imagem deve ser: 200x800px");
                                inputImageCapa.value = "";
                                return false;
                            } else {
                                modalCapa.modal('show');
                                return true;
                            }
                        } else if (pOpcImage === 'logo'){
                            if (imageHeight < 200 || imageWidth < 200) {
                                alert("Resolução mínima da imagem deve ser: 200x200px");
                                inputImageLogo.value = "";
                                return false;
                            } else {
                                $('#id-upload-demo-logo').addClass('ready');
                                modalLogo.modal('show');
                                return true;
                            }
                        }
                    };
                }
                reader.readAsDataURL(input.files[0]);
            }
            else {
                alert("Sorry - you're browser doesn't support the FileReader API");
            }
        }

        function chargeImage(pOpcImage){
            if(pOpcImage === 'capa'){
                $('#id-input-image-capastore').click();
            } else {
                $('#id-input-image-logostore').click();
            }
        }

        $('#id-input-image-capastore').on('change', function () {

            var size = this.files[0].size;

            if(size > (5 * 1024 * 1024)) { //5MB
                alert('Peso máximo da imagem deve ser: 5mb');
                inputImageCapa.value = "";
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
                        readFile(this,'capa');
                        break;
                    default:
                        alert('Formatos da imagem deve ser: JPG, JPEG, PNG e SVG');
                        inputImageCapa.value = "";
                }
            }

        });

        $('#id-input-image-logostore').on('change', function () {

            var size = this.files[0].size;

            if(size > (5 * 1024 * 1024)) { //5MB
                alert('Peso máximo da imagem deve ser: 5mb');
                inputImageLogo.value = "";
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
                        readFile(this,'logo');
                        break;
                    default:
                        alert('Formatos da imagem deve ser: JPG, JPEG, PNG e SVG');
                        inputImageLogo.value = "";
                }
            }

        });

        $('#cancelImageCapaBtn').on('click', function () {
            inputImageCapa.value = "";
        });

        $('#cancelImageLogoBtn').on('click', function () {
            inputImageLogo.value = "";
        });

        modalCapa.on('shown.bs.modal', function () {

            cropperCapa = new Cropper(imageModalCapa, {
                viewMode: 3,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                modal: false,
                guides: false,
                highlight: false,
                cropBoxMovable: false,
                cropBoxResizable: false,
                toggleDragModeOnDblclick: false,
            });

        }).on('hidden.bs.modal', function () {
            cropperCapa.destroy();
            cropperCapa = null;
        });

        document.getElementById('cropImageCapaBtn').addEventListener('click', function () {
            var canvas;

            if (cropperCapa) {
                canvas = cropperCapa.getCroppedCanvas({
                    width: 1600,
                    height: 500,
                });
                imagepreviewCapa.src = canvas.toDataURL();
                $('#imagebase64capa').val(canvas.toDataURL());
            }
            modalCapa.modal('hide');

        });

        uploadCropLogo = $('#id-upload-demo-logo').croppie({
            enableExif: false,
            enableOrientation: true,
            enforceBoundary: false,
            viewport: {
                width: 200,
                height: 200,
                type: 'square'
            },
            boundary: {
                width: '100%',
                height: 250
            }
        });

        modalLogo.on('shown.bs.modal', function(){
            uploadCropLogo.croppie('bind', {
                url: imageModalLogo
                //orientation: 4
                //points: [77,469,280,739]
            }).then(function(){
                console.log('jQuery bind complete');
            });
        });

        $('#cropImageLogoBtn').on('click', function (event) {
            uploadCropLogo.croppie('result', {
                type: 'base64',
                format: 'jpeg',
                backgroundColor : "#ffffff",
                size:{ width:null, height:null }
            }).then(function (resp) {

                divPlaceholderFixed.classList.remove("div-placeholder-image-logostore-ishovered");
                $( ".div-placeholder-image-logostore-fixed" ).remove();
                $( ".div-container-content-image-logostore" ).append('<div class="div-imagepreview-image-logostore">\n' +
                    '                                                 <img class="img-imagepreview-image-logostore" id="id-img-imagepreview-image-logostore" />\n' +
                    '                                              </div>');
                $( ".div-container-content-image-logostore" ).append('<div class="div-placeholder-image-logostore-fixed"></div>');


                $( ".div-placeholder-image-logostore-fixed" ).append('<div class="div-choose-image-logostore-add" onclick="chargeImage()">\n' +
                    '                                                            <div class="div-choose-image-logostore-btn-content">\n' +
                    '                                                                <div class="div-choose-image-logostore-btn-icon">\n' +
                    '                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="26" height="26" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 17 16"><g fill="#ea1d2c" fill-rule="evenodd"><path d="M2.539 8.001c.828 0 1.379-.551 1.379-1.379l-.004-.435s-.205-1.233 1.44-1.233L12.015 5v1.759a.83.83 0 0 0 1.17 0l2.573-2.572a.83.83 0 0 0 0-1.17L13.185.292a.83.83 0 0 0-1.17 0v1.845c-.161-.047-6.453-.074-6.453-.074c-3.711 0-4.523 2.429-4.523 3.407v1.031a1.5 1.5 0 0 0 1.5 1.5z"/><path d="M14.5 8.042c-.828 0-1.5.584-1.5 1.412l.002.957c-.045.357-.645.59-1.525.59H4.938l-.002-1.559a.83.83 0 0 0-1.17 0l-2.572 2.572a.83.83 0 0 0 0 1.17l2.572 2.572a.826.826 0 0 0 1.17 0l.002-1.851h6.539c3.711 0 4.523-2.442 4.523-3.421V9.453c0-.827-.672-1.411-1.5-1.411z"/></g></svg>' +
                    '                                                                </div>Alterar logo'+
                    '                                                            </div>\n' +
                    '                                                    </div>');

                divPlaceholderFixed = document.querySelector(".div-placeholder-image-logostore-fixed");

                $('#id-img-imagepreview-image-logostore').attr('src', resp);

                $('#imagebase64logo').val(resp);

                modalLogo.modal('hide');

            });
        });
        // End upload preview images

        $( 'div.div-content-imagefield-image-logostore' ).hover(
            function() {
                divPlaceholderFixed.classList.add("div-placeholder-image-logostore-ishovered");

            }, function() {
                divPlaceholderFixed.classList.remove("div-placeholder-image-logostore-ishovered");
            }
        );

    </script>
@endsection
