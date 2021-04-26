<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cad-Usuário</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition register-page">

<div class="register-box">
    <div class="register-logo">
        <a href="{{ route('home.index') }}">Home</a>
    </div>
    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Olá {{ ((isset($userSiteData) && !empty($userSiteData['name'])) ? $userSiteData['name'] : '') }}</p>

            <form class="form-signin" action="{{ route('usersite.store') }}" method="post" name="formUserSiteCad">
                @csrf

                <div class="alert alert-danger d-none messageBox" role="alert">

                </div>

                <div class="input-group mb-3">
                    <input value="{{ ((isset($userSiteData) && !empty($userSiteData['name'])) ? $userSiteData['name'] : '') }}" type="text" class="form-control" placeholder="Full name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input id="idfone" name="fone" type="text" class="form-control" placeholder="Celular">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-phone"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                            <label for="agreeTerms">
                                Concordo com os <a href="#">termos</a>
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jquery -->
<script src="{{ asset('admin/node_modules/js/jquery.js') }}"></script>
<!-- bootstrap.bundle -->
<script src="{{ asset('admin/node_modules/js/bootstrap.bundle.js') }}"></script>
<!-- jquery adminlte -->
<script src="{{ asset('admin/node_modules/js/adminlte.js') }}"></script>

<script src="{{ asset('admin/node_modules/js/jquery.mask.min.js') }}"></script>

<script>
    $(document).ready(function () {

        $('#idfone').mask('(00) 00000-0000');

    });
</script>

<script>
    $(function () {
        $('form[name="formUserSiteCad"]').submit(function (event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('usersite.store') }}",
                type: "post",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    if(response.success === true){
                        window.location.href = "{{ ( (Session::has('returnurlcallback')) ? url(Session::get('returnurlcallback')) : route('home.index')) }}";
                    }else{
                        $('.messageBox').removeClass('d-none').html(response.message);
                    }
                    console.log(response);
                }
            });
        })
    })
</script>

</body>
</html>