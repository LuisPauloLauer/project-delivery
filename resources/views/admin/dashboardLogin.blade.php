<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/style.css')}}">
    <title>Login-dashboard</title>
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }
        .form-signin .checkbox {
            font-weight: 400;
        }
        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body class="text-center">
    <form class="form-signin" action="{{ route('dashboard.login.do') }}" method="post" name="formLoginDashboard">
        @csrf
        <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72"
             height="72">

        <div class="alert alert-danger d-none messageBox" role="alert">

        </div>

        <h1 class="h3 mb-3 font-weight-normal">Login Dashboard</h1>

        <label for="inputEmail" class="sr-only">email</label>
        <input type="text" class="form-control" id="idemail" name="email" placeholder="email" autofocus>

        <label for="inputPassword" class="sr-only">senha</label>
        <input type="password" class="form-control" id="idpassword" name="password" placeholder="senha">

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2020</p>
    </form>
    <script src="{{ asset('admin/node_modules/js/jquery.js')}}"></script>
    <script src="{{ asset('admin/node_modules/js/bootstrap.js')}}"></script>
    <script>
        $(function () {
            $('form[name="formLoginDashboard"]').submit(function (event) {
                event.preventDefault();
                $.ajax({
                    url: "{{ route('dashboard.login.do') }}",
                    type: "post",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function (response) {
                        if(response.success === true){
                            window.location.href = "{{ route('dashboard.home') }}";
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
