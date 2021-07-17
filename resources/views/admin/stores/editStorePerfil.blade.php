@extends('admin.layout_master.admin_design')

@section('Stylescss')
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/icheck-bootstrap/icheck-bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.css') }}">
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
                            <li class="breadcrumb-item active"><h4>Dados da loja</h4></li>
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
                        <div class="card card-primary card-outline card-outline-tabs">
                            <!-- card-header 1 -->
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
                            <!-- /.card-header 1 -->
                            <!-- card-header 2 -->
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="storeDadosTab" data-toggle="pill"
                                           href="#storeDadosTarget" role="tab" aria-controls="custom-tabs-four-home"
                                           aria-selected="true">Dados</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="storeAdressTab" data-toggle="pill"
                                           href="#storeAdressTarget" role="tab"
                                           aria-controls="custom-tabs-four-profile" aria-selected="false">Endereço</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="storeDeliveryTimeTab" data-toggle="pill"
                                           href="#storeDeliveryTimeTarget" role="tab"
                                           aria-controls="custom-tabs-four-profile" aria-selected="false">Horários de entrega</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="storePaymentTab" data-toggle="pill"
                                           href="#storePaymentTarget" role="tab"
                                           aria-controls="custom-tabs-four-profile" aria-selected="false">Pagamento</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /.card-header 2 -->
                            <br>
                            <div class="container-fluid pl-3 pr-3 mb-4">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="card-body-content tab-pane fade show active" id="storeDadosTarget" role="tabpanel" aria-labelledby="storeDadosTab">
                                        <form action="{{ route('store.perfil.edit.dados', ['store' => $Store->id]) }}"
                                              method="post" enctype="multipart/form-data">
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
                                                <div class="col col-3">
                                                    <label for="inputStatus">Habilitar vendas:</label>
                                                    <input id="ckbstatus" name="ckbstatus" class="ckbstatus-bootstrap-switch" type="checkbox" {{ (($Store->active_store_site == 'S') ? "checked" : "") }} data-objectid="{{$Store->id}}">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="inputName">Nome da loja:</label>
                                                    <input value="{{ $Store->name }}" id="idname" name="name"
                                                           type="text" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <label for="inputFoneStoreSite">Telefone:</label>
                                                    <input value="{{ $Store->fone_store_site }}"
                                                           id="idfonestoresite" name="fone_store_site"
                                                           type="text" placeholder="Ex.: (51) 98888-7777"
                                                           class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col col-6">
                                                    <label for="inputDescription">Descrição:</label>
                                                    <textarea name="description" id="iddescription"
                                                              class="md-textarea form-control"
                                                              rows="4">{{ $Store->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col col-6">
                                                    <label for="inputMinimumOrder">Pedido mínimo:</label>
                                                    <input value="{{ number_format($Store->minimum_order,2) }}"
                                                           id="idminimumorder" name="minimum_order" type="text"
                                                           class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-row mt-3">
                                                <div class="col-lg-auto">
                                                    <button class="btn btn-primary btn-lg btn-block" type="submit"
                                                            value="Submit">Atualizar
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-body-content tab-pane fade" id="storeAdressTarget" role="tabpanel" aria-labelledby="storeAdressTab">
                                        <form action="{{ route('store.perfil.edit.endereco', ['store' => $Store->id]) }}" method="post">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="inputZipCode">Cep:</label>
                                                    <input value="{{ $Store->zip_code }}" id="idzipcode" name="zip_code"
                                                           type="text" value="" size="10" maxlength="9"
                                                           onblur="pesquisacep(this.value);" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <label for="inputStreet">Rua:</label>
                                                    <input value="{{ $Store->street }}" id="idstreet" name="street"
                                                           type="text" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <label for="inputNumber">N:</label>
                                                    <input value="{{ $Store->number }}" id="idnumber" name="number"
                                                           type="text" placeholder="Número" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="inputDistrict">Bairro:</label>
                                                    <input value="{{ $Store->district }}" id="iddistrict"
                                                           name="district" type="text" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <label for="inputComplement">Complemento:</label>
                                                    <input value="{{ $Store->complement }}" id="idcomplement"
                                                           name="complement" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="inputCity">Cidade:</label>
                                                    <input id="idcity" name="city" type="text"
                                                           class="form-control">
                                                </div>
                                                <div class="col">
                                                    <label for="inputUF">Estado:</label>
                                                    <input id="iduf" name="uf" type="text"
                                                           class="form-control">
                                                </div>
                                                <div class="col">
                                                    <label for="inputIBGE">IBGE:</label>
                                                    <input id="idibgetela"  name="ibgetela" type="text" class="form-control" >
                                                    <input id="idibge"  name="ibge" type="hidden" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="form-row mt-3">
                                                <div class="col-lg-auto">
                                                    <button class="btn btn-primary btn-lg btn-block" type="submit"
                                                            value="Submit">Atualizar
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="storeDeliveryTimeTarget" role="tabpanel" aria-labelledby="storeDeliveryTimeTab">
                                        <form id="frmTimeDelivery" action="{{ route('store.perfil.edit.timedelivery', ['store' => $Store->id]) }}" method="post">
                                            @csrf
                                            @method('PUT')

                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Dias da semana</th>
                                                        <th>Período 1</th>
                                                        <th>Período 2</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($listdaysOfWeek))
                                                    @foreach($listdaysOfWeek as $daysOfWeek)
                                                    <tr>
                                                        <td>
                                                            <div class="form-row">
                                                                <div class="col-lg-auto">
                                                                    <div class="icheck-success d-inline">
                                                                        <input type="checkbox" id="ckb_{{$daysOfWeek->day}}" name="ckb_{{$daysOfWeek->day}}" value="{{$daysOfWeek->day}}">
                                                                        <label for="ckb_{{$daysOfWeek->day}}">
                                                                        <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                                        {{$daysOfWeek->day}}</label>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="row justify-content-start">
                                                                <input value="0000" name="txt_p1_ini_{{$daysOfWeek->day}}" id="txt_p1_ini_{{$daysOfWeek->day}}" style="min-width:80px; max-width:80px;" type="text" class="form-control time-delivery" placeholder="00:00">
                                                                <div class="col-auto text-center" style="min-width:30px; max-width:30px;">
                                                                    às
                                                                </div>
                                                                <input value="0000" name="txt_p1_end_{{$daysOfWeek->day}}" id="txt_p1_end_{{$daysOfWeek->day}}" style="min-width:80px; max-width:80px;" type="text" class="form-control time-delivery" placeholder="00:00">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="row justify-content-start">
                                                                <a id="btn_{{$daysOfWeek->day}}_adc" href="#">ADICIONAR</a>
                                                                <div class="row" id="div_{{$daysOfWeek->day}}_adc" style="display: none;">
                                                                    <input value="0000" name="txt_p2_ini_{{$daysOfWeek->day}}" id="txt_p2_ini_{{$daysOfWeek->day}}" style="min-width:80px; max-width:80px;" type="text" class="form-control time-delivery" placeholder="00:00">
                                                                    <div class="col-auto text-center" style="min-width:30px; max-width:30px;">
                                                                        às
                                                                    </div>
                                                                    <input  value="0000" name="txt_p2_end_{{$daysOfWeek->day}}" id="txt_p2_end_{{$daysOfWeek->day}}" style="min-width:80px; max-width:80px;" type="text" class="form-control time-delivery" placeholder="00:00">
                                                                    <div class="col-lg-auto text-center">
                                                                        <a id="btn_{{$daysOfWeek->day}}_cancel" href="#">CANCELAR</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                            <div class="form-row mt-3">
                                                <div class="col-lg-auto">
                                                    <button class="btn btn-primary btn-lg btn-block" type="submit"
                                                            value="Submit">Atualizar
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="storePaymentTarget" role="tabpanel" aria-labelledby="storePaymentTab">
                                        <form action="{{ route('store.perfil.edit.payment', ['store' => $Store->id]) }}" method="post">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="text-center"><h5>Tipos de pagamentos da loja</h5></div>
                                                    <hr class="mb-2">
                                                </div>
                                            </div>
                                            <div class="form-row mt-2">
                                                <div class="col">
                                                    <h6><strong>Dinheiro</strong></h6>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="icheck-success d-inline">
                                                        <input type="checkbox" id="idmoneystore" name="moneystore" value="moneystore">
                                                        <label for="idmoneystore">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            <img src="data:image/svg+xml,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20x%3D%221%22%20y%3D%224%22%20width%3D%2222%22%20height%3D%2216%22%20rx%3D%222%22%20fill%3D%22%2397D9A2%22%20stroke%3D%22%2300912E%22%20stroke-width%3D%222%22%2F%3E%3Cmask%20id%3D%22mask0%22%20mask-type%3D%22alpha%22%20maskUnits%3D%22userSpaceOnUse%22%20x%3D%220%22%20y%3D%223%22%20width%3D%2224%22%20height%3D%2218%22%3E%3Crect%20x%3D%221%22%20y%3D%224%22%20width%3D%2222%22%20height%3D%2216%22%20rx%3D%222%22%20fill%3D%22%2397D9A2%22%20stroke%3D%22%2300912E%22%20stroke-width%3D%222%22%2F%3E%3C%2Fmask%3E%3Cg%20mask%3D%22url%28%23mask0%29%22%3E%3Ccircle%20cx%3D%222%22%20cy%3D%225%22%20r%3D%223%22%20fill%3D%22%2300912E%22%2F%3E%3Ccircle%20cx%3D%2222%22%20cy%3D%225%22%20r%3D%223%22%20fill%3D%22%2300912E%22%2F%3E%3Ccircle%20cx%3D%2222%22%20cy%3D%2219%22%20r%3D%223%22%20fill%3D%22%2300912E%22%2F%3E%3Ccircle%20cx%3D%222%22%20cy%3D%2219%22%20r%3D%223%22%20fill%3D%22%2300912E%22%2F%3E%3C%2Fg%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M12%2016C14.2091%2016%2016%2014.2091%2016%2012C16%209.79086%2014.2091%208%2012%208C9.79086%208%208%209.79086%208%2012C8%2014.2091%209.79086%2016%2012%2016Z%22%20fill%3D%22%2300912E%22%2F%3E%3C%2Fsvg%3E">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            Dinheiro</label>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row mt-2">
                                                <div class="col">
                                                    <h6><strong>Cartão</strong></h6>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="icheck-success d-inline">
                                                        <input type="checkbox" id="iddebitmastercardstore" name="debitmastercardstore" value="debitmastercardstore">
                                                        <label for="iddebitmastercardstore">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            <img src="data:image/svg+xml,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20y%3D%223%22%20width%3D%2224%22%20height%3D%2218%22%20rx%3D%223%22%20fill%3D%22%23353A48%22%2F%3E%3Ccircle%20cx%3D%229%22%20cy%3D%2212%22%20r%3D%225%22%20fill%3D%22%23ED0006%22%2F%3E%3Ccircle%20cx%3D%2215%22%20cy%3D%2212%22%20r%3D%225%22%20fill%3D%22%23F9A000%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M12%2016.0004C13.2144%2015.0882%2014%2013.6359%2014%2012C14%2010.3642%2013.2145%208.9119%2012%207.99969C10.7856%208.9119%2010%2010.3642%2010%2012.0001C10%2013.6359%2010.7855%2015.0882%2012%2016.0004Z%22%20fill%3D%22%23FF5E00%22%2F%3E%3C%2Fsvg%3E">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            Débito - Mastercard (Máquina)</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="icheck-success d-inline">
                                                        <input type="checkbox" id="iddebitvisastore" name="debitvisastore" value="debitvisastore">
                                                        <label for="iddebitvisastore">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            <img src="data:image/svg+xml,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M0%2018V19C0%2020.1046%200.895431%2021%202%2021H22C23.1046%2021%2024%2020.1046%2024%2019V18H0Z%22%20fill%3D%22%23EBAA3E%22%2F%3E%3Crect%20y%3D%226%22%20width%3D%2224%22%20height%3D%2212%22%20fill%3D%22%23F7F7F7%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M6.90288%2015.3501H5.2057L3.93302%2010.3852C3.87261%2010.1568%203.74435%209.95487%203.55568%209.85971C3.08484%209.62057%202.566%209.43024%202%209.33426V9.14311H4.73403C5.11136%209.14311%205.39436%209.43024%205.44153%209.76372L6.10187%2013.3451L7.79822%209.14311H9.44824L6.90288%2015.3501ZM10.3916%2015.3501H8.78873L10.1086%209.14311H11.7114L10.3916%2015.3501ZM13.7851%2010.8627C13.8323%2010.5284%2014.1153%2010.3372%2014.4454%2010.3372C14.9643%2010.2892%2015.5294%2010.3852%2016.0011%2010.6235L16.2841%209.28714C15.8124%209.09599%2015.2936%209%2014.8228%209C13.2671%209%2012.1351%209.85976%2012.1351%2011.053C12.1351%2011.9608%2012.9369%2012.4374%2013.5029%2012.7245C14.1153%2013.0108%2014.3511%2013.202%2014.3039%2013.4883C14.3039%2013.9178%2013.8323%2014.1089%2013.3614%2014.1089C12.7954%2014.1089%2012.2294%2013.9658%2011.7114%2013.7266L11.4284%2015.0638C11.9944%2015.3022%2012.6068%2015.3982%2013.1728%2015.3982C14.9171%2015.4453%2016.0011%2014.5864%2016.0011%2013.2972C16.0011%2011.6736%2013.7851%2011.5785%2013.7851%2010.8627ZM21.6107%2015.3501L20.338%209.14311H18.971C18.688%209.14311%2018.405%209.33426%2018.3107%209.62057L15.954%2015.3501H17.604L17.9333%2014.4432H19.9607L20.1493%2015.3501H21.6107ZM19.2068%2010.8147L19.6777%2013.154H18.3578L19.2068%2010.8147Z%22%20fill%3D%22%23575DC1%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M2%203C0.89543%203%200%203.89543%200%205V6H24V5C24%203.89543%2023.1046%203%2022%203H2Z%22%20fill%3D%22%23575DC1%22%2F%3E%3C%2Fsvg%3E">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            Débito - Visa (Máquina)</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="icheck-success d-inline">
                                                        <input type="checkbox" id="iddebitelostore" name="debitelostore" value="debitelostore">
                                                        <label for="iddebitelostore">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            <img src="data:image/svg+xml,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20y%3D%223%22%20width%3D%2224%22%20height%3D%2218%22%20rx%3D%223%22%20fill%3D%22%232D2D2D%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M8.92924%2011.3641L4.78503%2013.1267C4.7786%2013.0562%204.77504%2012.9849%204.77504%2012.9128C4.77504%2011.619%205.83771%2010.5701%207.1486%2010.5701C7.85782%2010.5701%208.49434%2010.8773%208.92924%2011.3641ZM7.14863%208.81818C9.10417%208.81818%2010.7434%2010.1536%2011.1818%2011.951L9.50973%2012.6726L9.50934%2012.6691L7.7987%2013.4109L3.69505%2015.1818C3.25605%2014.5323%203%2013.752%203%2012.9128C3%2010.6513%204.85737%208.81818%207.14863%208.81818Z%22%20fill%3D%22%23FFFFFE%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M9.36363%2015.6821C8.05946%2017.247%206.33417%2017.4005%204.81818%2016.237L5.67471%2014.5907C6.53735%2015.2425%207.40394%2015.1368%208.27455%2014.2727L9.36363%2015.6821Z%22%20fill%3D%22%23FFFFFE%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M11.1917%2014.9842L11.1818%207H12.5872V14.7686C12.5872%2014.8431%2012.5957%2014.9088%2012.6866%2014.9467L13.9091%2015.4585L13.3583%2017L11.9262%2016.3478C11.3831%2016.1005%2011.1927%2015.7421%2011.1917%2014.9842Z%22%20fill%3D%22%23FFFFFE%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M16.6364%2014.5994C16.0782%2014.189%2015.7189%2013.5484%2015.7189%2012.828C15.7189%2012.1875%2016.0032%2011.6102%2016.4583%2011.2014L15.4966%209.72729C14.5299%2010.4517%2013.9091%2011.5709%2013.9091%2012.828C13.9091%2014.1829%2014.6298%2015.3783%2015.7281%2016.0909L16.6364%2014.5994Z%22%20fill%3D%22%232191C3%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M17.3811%2010.937C17.6237%2010.839%2017.8853%2010.7853%2018.158%2010.7853C19.1411%2010.7853%2019.9795%2011.4793%2020.3069%2012.4545L22.0909%2012.2872C21.6603%2010.2966%2020.0634%208.81818%2018.158%208.81818C17.6196%208.81818%2017.1062%208.93722%2016.6364%209.15144L17.3811%2010.937Z%22%20fill%3D%22%23FAEC32%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M20.2902%2013.3636C20.2305%2014.4872%2019.1973%2015.3817%2017.9313%2015.3817C17.7329%2015.3817%2017.5403%2015.3594%2017.3562%2015.3181L16.6364%2016.8147C17.0441%2016.9343%2017.4791%2017%2017.9313%2017C20.1736%2017%2022.001%2015.4082%2022.0909%2013.4146L20.2902%2013.3636Z%22%20fill%3D%22%23D0362B%22%2F%3E%3C%2Fsvg%3E">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            Débito - Elo (Máquina)</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="icheck-success d-inline">
                                                        <input type="checkbox" id="idcreditmastercardstore" name="creditmastercardstore" value="creditmastercardstore">
                                                        <label for="idcreditmastercardstore">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            <img src="data:image/svg+xml,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20y%3D%223%22%20width%3D%2224%22%20height%3D%2218%22%20rx%3D%223%22%20fill%3D%22%23353A48%22%2F%3E%3Ccircle%20cx%3D%229%22%20cy%3D%2212%22%20r%3D%225%22%20fill%3D%22%23ED0006%22%2F%3E%3Ccircle%20cx%3D%2215%22%20cy%3D%2212%22%20r%3D%225%22%20fill%3D%22%23F9A000%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M12%2016.0004C13.2144%2015.0882%2014%2013.6359%2014%2012C14%2010.3642%2013.2145%208.9119%2012%207.99969C10.7856%208.9119%2010%2010.3642%2010%2012.0001C10%2013.6359%2010.7855%2015.0882%2012%2016.0004Z%22%20fill%3D%22%23FF5E00%22%2F%3E%3C%2Fsvg%3E">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            Crédito - Mastercard (Máquina)</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="icheck-success d-inline">
                                                        <input type="checkbox" id="idcreditvisastore" name="creditvisastore" value="creditvisastore">
                                                        <label for="idcreditvisastore">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            <img src="data:image/svg+xml,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M0%2018V19C0%2020.1046%200.895431%2021%202%2021H22C23.1046%2021%2024%2020.1046%2024%2019V18H0Z%22%20fill%3D%22%23EBAA3E%22%2F%3E%3Crect%20y%3D%226%22%20width%3D%2224%22%20height%3D%2212%22%20fill%3D%22%23F7F7F7%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M6.90288%2015.3501H5.2057L3.93302%2010.3852C3.87261%2010.1568%203.74435%209.95487%203.55568%209.85971C3.08484%209.62057%202.566%209.43024%202%209.33426V9.14311H4.73403C5.11136%209.14311%205.39436%209.43024%205.44153%209.76372L6.10187%2013.3451L7.79822%209.14311H9.44824L6.90288%2015.3501ZM10.3916%2015.3501H8.78873L10.1086%209.14311H11.7114L10.3916%2015.3501ZM13.7851%2010.8627C13.8323%2010.5284%2014.1153%2010.3372%2014.4454%2010.3372C14.9643%2010.2892%2015.5294%2010.3852%2016.0011%2010.6235L16.2841%209.28714C15.8124%209.09599%2015.2936%209%2014.8228%209C13.2671%209%2012.1351%209.85976%2012.1351%2011.053C12.1351%2011.9608%2012.9369%2012.4374%2013.5029%2012.7245C14.1153%2013.0108%2014.3511%2013.202%2014.3039%2013.4883C14.3039%2013.9178%2013.8323%2014.1089%2013.3614%2014.1089C12.7954%2014.1089%2012.2294%2013.9658%2011.7114%2013.7266L11.4284%2015.0638C11.9944%2015.3022%2012.6068%2015.3982%2013.1728%2015.3982C14.9171%2015.4453%2016.0011%2014.5864%2016.0011%2013.2972C16.0011%2011.6736%2013.7851%2011.5785%2013.7851%2010.8627ZM21.6107%2015.3501L20.338%209.14311H18.971C18.688%209.14311%2018.405%209.33426%2018.3107%209.62057L15.954%2015.3501H17.604L17.9333%2014.4432H19.9607L20.1493%2015.3501H21.6107ZM19.2068%2010.8147L19.6777%2013.154H18.3578L19.2068%2010.8147Z%22%20fill%3D%22%23575DC1%22%2F%3E%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M2%203C0.89543%203%200%203.89543%200%205V6H24V5C24%203.89543%2023.1046%203%2022%203H2Z%22%20fill%3D%22%23575DC1%22%2F%3E%3C%2Fsvg%3E">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            Crédito - Visa (Máquina)</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="text-center"><h5>Tipos de pagamentos do aplicativo</h5></div>
                                                    <hr class="mb-2">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="icheck-success d-inline">
                                                        <input type="checkbox" id="idpaypalapp" name="paypalapp" value="paypalapp">
                                                        <label for="idpaypalapp">
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 2304 1536"><path d="M745 778q0 37-25.5 61.5T657 864q-29 0-46.5-16T593 804q0-37 25-62.5t62-25.5q28 0 46.5 16.5T745 778zm785-149q0 42-22 57t-66 15l-32 1l17-107q2-11 13-11h18q22 0 35 2t25 12.5t12 30.5zm351 149q0 36-25.5 61t-61.5 25q-29 0-47-16t-18-44q0-37 25-62.5t62-25.5q28 0 46.5 16.5T1881 778zM513 607q0-59-38.5-85.5T374 495H214q-19 0-21 19l-65 408q-1 6 3 11t10 5h76q20 0 22-19l18-110q1-8 7-13t15-6.5t17-1.5t19 1t14 1q86 0 135-48.5T513 607zm309 312l41-261q1-6-3-11t-10-5h-76q-14 0-17 33q-27-40-95-40q-72 0-122.5 54T489 816q0 59 34.5 94t92.5 35q28 0 58-12t48-32q-4 12-4 21q0 16 13 16h69q19 0 22-19zm447-263q0-5-4-9.5t-9-4.5h-77q-11 0-18 10l-106 156l-44-150q-5-16-22-16h-75q-5 0-9 4.5t-4 9.5q0 2 19.5 59t42 123t23.5 70q-82 112-82 120q0 13 13 13h77q11 0 18-10l255-368q2-2 2-7zm380-49q0-59-38.5-85.5T1510 495h-159q-20 0-22 19l-65 408q-1 6 3 11t10 5h82q12 0 16-13l18-116q1-8 7-13t15-6.5t17-1.5t19 1t14 1q86 0 135-48.5t49-134.5zm309 312l41-261q1-6-3-11t-10-5h-76q-14 0-17 33q-26-40-95-40q-72 0-122.5 54T1625 816q0 59 34.5 94t92.5 35q29 0 59-12t47-32q0 1-2 9t-2 12q0 16 13 16h69q19 0 22-19zm218-409v-1q0-14-13-14h-74q-11 0-13 11l-65 416l-1 2q0 5 4 9.5t10 4.5h66q19 0 21-19zM392 644q-5 35-26 46t-60 11l-33 1l17-107q2-11 13-11h19q40 0 58 11.5t12 48.5zm1912-516v1280q0 52-38 90t-90 38H128q-52 0-90-38t-38-90V128q0-52 38-90t90-38h2048q52 0 90 38t38 90z" fill="#27346A"/></svg>
                                                            <span style='margin-right:0.2em; display:inline-block;'>&nbsp</span>
                                                            PayPal</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="inputName">Client ID:</label>
                                                    <input value="{{ $Store->name }}" id="idname" name="name"
                                                           type="text" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <label for="inputName">Client Secret:</label>
                                                    <input value="{{ $Store->name }}" id="idname" name="name"
                                                           type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-row mt-3">
                                                <div class="col-lg-auto">
                                                    <button class="btn btn-primary btn-lg btn-block" type="submit"
                                                            value="Submit">Atualizar
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
    <!-- Bootstrap Switch -->
    <script src="{{ asset('admin/adminLTE/plugins/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
    <script src="{{ asset('admin/node_modules/js/croppie.js')}}"></script>
    <script src="{{ asset('admin/node_modules/js/cropper.js')}}"></script>
    <!-- Options components default -->
    <script src="{{ asset('admin/node_modules/js/option-components-default.js') }}"></script>
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

        $( "#frmTimeDelivery" ).submit(function( event ) {

            var errorTimeDelivery = 0;

            $( ".time-delivery" ).each(function( index ) {

                var time = $( this ).val(); // if it is an input/select/textarea field
                time = time.toString().replace(/:/, '');
                var timeH = time.substring(0, 2);

                if ( timeH == '' ) {
                    errorTimeDelivery++;
                    alert('Hora: '+timeH+' sem preenchimento');
                    $( this ).focus();
                    return  false;
                }

                timeH = parseInt(timeH);

                var timeM = time.substring(2);

                if ( timeM == '') {
                    errorTimeDelivery++;
                    alert('Minuto: '+timeM+' sem preenchimento');
                    $( this ).focus();
                    return  false;
                }

                timeM = parseInt(timeM);

                if(timeH < 0){
                    errorTimeDelivery++;
                    alert('Hora: '+timeH+' menor que 0 horas');
                    $( this ).focus();
                    return  false;
                }
                if(timeH > 23){
                    errorTimeDelivery++;
                    alert('Hora: '+timeH+' maior que 23 horas');
                    $( this ).focus();
                    return  false;
                }

                if(timeM < 0){
                    errorTimeDelivery++;
                    alert('Minuto: '+timeM+' menor que 0 minutos');
                    $( this ).focus();
                    return  false;
                }
                if(timeM > 59){
                    errorTimeDelivery++;
                    alert('Minuto: '+timeM+' maior que 59 minutos');
                    $( this ).focus();
                    return  false;
                }

            });

            if(errorTimeDelivery > 0){
                return  false;
            }

        });

        $(document).ready(function () {

            $('#idzipcode').mask('00000-000');
            $('#idfonestoresite').mask('(00) 00000-0000');
            $('#idminimumorder').mask('00000000000000.00', {reverse: true});
            $('#idunitprice').mask('00000000000000.00', {reverse: true});

            $('#idcity').prop("disabled", true);
            $('#iduf').prop("disabled", true);
            $('#idibgetela').prop("disabled", true);

            var options =  {
                onComplete: function(timeD) {

                    var time = timeD.toString().replace(/:/, '');

                    var timeH = time.substring(0, 2);
                    timeH = parseInt(timeH);

                    var timeM = time.substring(2);
                    timeM = parseInt(timeM);

                    if(timeH < 0){
                        alert('Hora: '+timeH+' menor que 0 horas');
                    }
                    if(timeH > 23){
                        alert('Hora: '+timeH+' maior que 23 horas');
                    }

                    if(timeM < 0){
                        alert('Minuto: '+timeM+' menor que 0 minutos');
                    }
                    if(timeM > 59){
                        alert('Minuto: '+timeM+' maior que 59 minutos');
                    }
                }
            };

            $('.time-delivery').mask('00:00', options);

            pesquisacep($('#idzipcode').val(), true)

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

            @if(isset($listStorePayment))
                @foreach($listStorePayment as $storePayment)
                    <?php if($storePayment->type_payment_system == 'money') { ?>
                        <?php if($storePayment->status == 'S') { ?>
                            $('#idmoneystore').prop("checked", true);
                        <?php } else { ?>
                            $('#idmoneystore').prop("checked", false);
                        <?php } ?>
                    <?php } ?>
                    <?php if( ($storePayment->type_payment_local == 'store') && ($storePayment->type_payment_name == 'debit') && ($storePayment->type_payment_flag == 'mastercard') ) { ?>
                        <?php if($storePayment->status == 'S') { ?>
                            $('#iddebitmastercardstore').prop("checked", true);
                        <?php } else { ?>
                            $('#iddebitmastercardstore').prop("checked", false);
                        <?php } ?>
                    <?php } ?>
                    <?php if( ($storePayment->type_payment_local == 'store') && ($storePayment->type_payment_name == 'debit') && ($storePayment->type_payment_flag == 'visa') ) { ?>
                        <?php if($storePayment->status == 'S') { ?>
                            $('#iddebitvisastore').prop("checked", true);
                        <?php } else { ?>
                            $('#iddebitvisastore').prop("checked", false);
                        <?php } ?>
                    <?php } ?>
                    <?php if( ($storePayment->type_payment_local == 'store') && ($storePayment->type_payment_name == 'debit') && ($storePayment->type_payment_flag == 'elo') ) { ?>
                        <?php if($storePayment->status == 'S') { ?>
                            $('#iddebitelostore').prop("checked", true);
                        <?php } else { ?>
                            $('#iddebitelostore').prop("checked", false);
                        <?php } ?>
                    <?php } ?>

                    <?php if( ($storePayment->type_payment_local == 'store') && ($storePayment->type_payment_name == 'credit') && ($storePayment->type_payment_flag == 'mastercard') ) { ?>
                        <?php if($storePayment->status == 'S') { ?>
                            $('#idcreditmastercardstore').prop("checked", true);
                        <?php } else { ?>
                            $('#idcreditmastercardstore').prop("checked", false);
                        <?php } ?>
                    <?php } ?>
                    <?php if( ($storePayment->type_payment_local == 'store') && ($storePayment->type_payment_name == 'credit') && ($storePayment->type_payment_flag == 'visa') ) { ?>
                        <?php if($storePayment->status == 'S') { ?>
                            $('#idcreditvisastore').prop("checked", true);
                        <?php } else { ?>
                            $('#idcreditvisastore').prop("checked", false);
                        <?php } ?>
                    <?php } ?>
                    <?php if( ($storePayment->type_payment_system == 'paypal') ) { ?>
                        <?php if($storePayment->status == 'S') { ?>
                            $('#idpaypalapp').prop("checked", true);
                        <?php } else { ?>
                            $('#idpaypalapp').prop("checked", false);
                        <?php } ?>
                    <?php } ?>

                @endforeach
            @endif

            @if(isset($listdaysOfWeek))
                @foreach($listdaysOfWeek as $daysOfWeek)

                        @if(isset($listStoretime))
                            @foreach($listStoretime as $storeTime)
                               @if($daysOfWeek->id == $storeTime->day)

                                    var p1Ini = '{{$storeTime->periodo1_ini}}';
                                    var p1End = '{{$storeTime->periodo1_end}}';
                                    var p2Ini = '{{$storeTime->periodo2_ini}}';
                                    var p2End = '{{$storeTime->periodo2_end}}';

                                    p1Ini = p1Ini.substring(0, 5);
                                    p1End = p1End.substring(0, 5);
                                    p2Ini = p2Ini.substring(0, 5);
                                    p2End = p2End.substring(0, 5);

                                    $('#txt_p1_ini_{{$daysOfWeek->day}}').val(p1Ini);
                                    $('#txt_p1_end_{{$daysOfWeek->day}}').val(p1End);
                                    $('#txt_p2_ini_{{$daysOfWeek->day}}').val(p2Ini);
                                    $('#txt_p2_end_{{$daysOfWeek->day}}').val(p2End);

                                    @if($storeTime->status == 'S')
                                        $("#ckb_{{$daysOfWeek->day}}").prop('checked', true);
                                    @else
                                        $("#ckb_{{$daysOfWeek->day}}").prop('checked', false);
                                    @endif

                                    if ($("#ckb_{{$daysOfWeek->day}}").is(':checked')){

                                        var timep2Ini = p2Ini.toString().replace(/:/, '');
                                        var timep2End = p2End.toString().replace(/:/, '');

                                        if( timep2Ini != '0000' || timep2End != '0000' ){
                                            $("#div_{{$daysOfWeek->day}}_adc").toggle();
                                            $("#btn_{{$daysOfWeek->day}}_adc").hide();
                                        }
                                    }

                               @endif
                            @endforeach
                        @endif


                        if ($("#ckb_{{$daysOfWeek->day}}").is(':checked')){
                            $('#txt_p1_ini_{{$daysOfWeek->day}}').prop("disabled", false);
                            $('#txt_p1_end_{{$daysOfWeek->day}}').prop("disabled", false);
                            $('#txt_p2_ini_{{$daysOfWeek->day}}').prop("disabled", false);
                            $('#txt_p2_end_{{$daysOfWeek->day}}').prop("disabled", false);
                        } else {
                            $('#txt_p1_ini_{{$daysOfWeek->day}}').prop("disabled", true);
                            $('#txt_p1_end_{{$daysOfWeek->day}}').prop("disabled", true);
                            $('#txt_p2_ini_{{$daysOfWeek->day}}').prop("disabled", true);
                            $('#txt_p2_end_{{$daysOfWeek->day}}').prop("disabled", true);
                        }

                        $("#ckb_{{$daysOfWeek->day}}").click(function(){

                            if(this.checked){
                                $('#txt_p1_ini_{{$daysOfWeek->day}}').prop("disabled", false);
                                $('#txt_p1_end_{{$daysOfWeek->day}}').prop("disabled", false);
                                $('#txt_p2_ini_{{$daysOfWeek->day}}').prop("disabled", false);
                                $('#txt_p2_end_{{$daysOfWeek->day}}').prop("disabled", false);
                            } else {
                                $('#txt_p1_ini_{{$daysOfWeek->day}}').prop("disabled", true);
                                $('#txt_p1_end_{{$daysOfWeek->day}}').prop("disabled", true);
                                $('#txt_p2_ini_{{$daysOfWeek->day}}').prop("disabled", true);
                                $('#txt_p2_end_{{$daysOfWeek->day}}').prop("disabled", true);
                            }

                        });

                        $("#btn_{{$daysOfWeek->day}}_adc").click(function(){

                            $("#div_{{$daysOfWeek->day}}_adc").toggle();
                            $("#btn_{{$daysOfWeek->day}}_adc").hide();
                        });
                        $("#btn_{{$daysOfWeek->day}}_cancel").click(function(){

                            $("#txt_p2_ini_{{$daysOfWeek->day}}").val('00:00');
                            $("#txt_p2_end_{{$daysOfWeek->day}}").val('00:00');

                            $("#div_{{$daysOfWeek->day}}_adc").hide();
                            $("#btn_{{$daysOfWeek->day}}_adc").show();
                        });
                @endforeach
            @endif

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
                    url: "{{ route('store.perfil.change.activestore') }} ",
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
