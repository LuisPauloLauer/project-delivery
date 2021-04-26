@extends('admin.layout_master.admin_design')

@section('validations_javascript')
    <script src="{{ asset('admin/node_modules/validation/validate_zipcode.js') }}"></script>
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
                            <li class="breadcrumb-item"><h5><a href="{{ route('affiliates.index') }}">Voltar</a></h5></li>
                            <li class="breadcrumb-item active">Alteração de Afiliados</li>
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
                                    <form action="{{ route('affiliates.update', ['affiliate' => $Affiliate->id]) }}" method="post">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputTpAffiliate">Tipo do Afiliado:</label><span class="text-danger col-1">{{$errors->first('tpaffiliate')}}</span>
                                                <select id="idtpaffiliate" name="tpaffiliate" autofocus class="custom-select d-block w-100">
                                                    @foreach($listTpAffiliate as $TpAffiliate)
                                                        <option value="{{ $TpAffiliate->id }}"
                                                        {{ (($Affiliate->tpaffiliate === $TpAffiliate->id) ? 'selected' : '' ) }}
                                                        >{{ $TpAffiliate->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="inputTypePerson">Tipo pessoa:</label><span class="text-danger col-1">{{$errors->first('type_person')}}</span>
                                                <input value="{{ $Affiliate->type_person }}" id="idtypeperson"  name="type_person" type="text" class="form-control">
                                            </div>
                                            <div class="col col-2">
                                                <label for="inputStatus">Habilitado:</label>
                                                <select id="idstatus" name="status" class="custom-select d-block w-100">
                                                    <option value="S"
                                                        {{(($Affiliate->status == 'S') ? "selected" : "")}}>Sim
                                                    </option>
                                                    <option value="N"
                                                        {{(($Affiliate->status == 'N') ? "selected" : "")}}>Não
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputCorporateName">Razão social:</label><span class="text-danger col-1">{{$errors->first('corporate_name')}}</span>
                                                <input value="{{ $Affiliate->corporate_name }}" id="idcorporatename"  name="corporate_name" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputFantasyName">Nome fantasia:</label><span class="text-danger col-1">{{$errors->first('fantasy_name')}}</span>
                                                <input value="{{ $Affiliate->fantasy_name }}" id="idfantasyname"  name="fantasy_name" type="text" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputZipCode">Cep:</label><span class="text-danger col-1">{{$errors->first('zip_code')}}</span>
                                                <input value="{{ $Affiliate->zip_code }}" id="idzipcode"  name="zip_code" type="text" value="" size="10" maxlength="9" onblur="pesquisacep(this.value);" class="form-control" >
                                            </div>
                                            <div class="col col-6">
                                                <label for="inputStreet">Rua:</label><span class="text-danger col-1">{{$errors->first('street')}}</span>
                                                <input value="{{ $Affiliate->street }}" id="idstreet"  name="street" type="text" class="form-control" >
                                            </div>
                                            <div class="col">
                                                <label for="inputNumber">N:</label><span class="text-danger col-1">{{$errors->first('number')}}</span>
                                                <input value="{{ $Affiliate->number }}" id="idnumber"  name="number" type="text" placeholder="Número" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputDistrict">Bairro:</label><span class="text-danger col-1">{{$errors->first('district')}}</span>
                                                <input value="{{ $Affiliate->district }}" id="iddistrict"  name="district" type="text" class="form-control" >
                                            </div>
                                            <div class="col">
                                                <label for="inputComplement">Complemento:</label><span class="text-danger col-1">{{$errors->first('complement')}}</span>
                                                <input value="{{ $Affiliate->complement }}" id="idcomplement"  name="complement" type="text" class="form-control" >
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
                                            <div class="col col-4">
                                                <label for="inputFone1">Fone(1):</label><span class="text-danger col-1">{{$errors->first('fone1')}}</span>
                                                <input value="{{ $Affiliate->fone1 }}" id="idfone1"  name="fone1" type="text" placeholder="Ex.: (51) 98888-7777" class="form-control" >
                                            </div>
                                            <div class="col col-4">
                                                <label for="inputFone2">Fone(2):</label><span class="text-danger col-1">{{$errors->first('fone2')}}</span>
                                                <input value="{{ $Affiliate->fone2 }}" id="idfone2"  name="fone2" type="text" placeholder="Ex.: (51) 98888-7777" class="form-control" >
                                            </div>
                                            <div class="col">
                                                <label for="inputCpforCnpj">CPF/CNPJ:</label><span class="text-danger col-1">{{$errors->first('cpf_or_cnpj')}}</span>
                                                @if((!empty($Affiliate->cpf)) && ($Affiliate->type_person === 'PF'))
                                                    <input value="{{ $Affiliate->cpf }}" id="idcpf_or_cnpj"  name="cpf_or_cnpj" type="text" class="form-control" >
                                                @elseif((!empty($Affiliate->cnpj)) && ($Affiliate->type_person === 'PJ'))
                                                    <input value="{{ $Affiliate->cnpj }}" id="idcpf_or_cnpj"  name="cpf_or_cnpj" type="text" class="form-control" >
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="inputEmail">Email:</label><span class="text-danger col-1">{{$errors->first('email')}}</span>
                                                <input value="{{ $Affiliate->email }}" id="idemail"  name="email" type="text" class="form-control" >
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
    <script>
        $(document).ready(function () {

            @if((!empty($Affiliate->cpf)) && ($Affiliate->type_person === 'PF'))
                $('#idcpf_or_cnpj').mask('000.000.000-00', {reverse: true});
            @elseif((!empty($Affiliate->cnpj)) && ($Affiliate->type_person === 'PJ'))
                $('#idcpf_or_cnpj').mask('00.000.000/0000-00', {reverse: true});
            @endif

            $('#idzipcode').mask('00000-000');
            $('#idnumber').mask('000.000.000', {reverse: true});
            $('#idfone1').mask('(00) 00000-0000');
            $('#idfone2').mask('(00) 00000-0000');

            pesquisacep($('#idzipcode').val(), true)

            $('#idtypeperson').prop("disabled", true);
            $('#idcity').prop("disabled", true);
            $('#iduf').prop("disabled", true);
            $('#idibgetela').prop("disabled", true);
            $('#idcpf_or_cnpj').prop("disabled", true);


        });
    </script>
@endsection
