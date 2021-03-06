<!doctype html>
<html lang="pt-br">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lietoo</title>
    <!-- CSS AdminLTE -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- CSS AdminLTE -->
    <!-- CSS Files -->
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/style.css') }}">
    <!-- Theme adminlte -->
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/adminlte.min.css') }}">
    <!-- Stylescss yield -->
    @yield('Stylescss')
    <!-- Stylescss yield -->
    <!-- CSS Files -->
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- validations_javascript yield -->
    @yield('validations_javascript')
    <!-- validations_javascript yield -->
</head>
<body class="hold-transition lockscreen">

    @yield('content')

<!-- JS Files -->
<!-- jquery -->
<script src="{{ asset('admin/node_modules/js/jquery.js') }}"></script>
<!-- bootstrap -->
<script src="{{ asset('admin/node_modules/js/bootstrap.js') }}"></script>
<!-- bootstrap.bundle -->
<script src="{{ asset('admin/node_modules/js/bootstrap.bundle.js') }}"></script>
<!-- popper -->
<script src="{{ asset('admin/node_modules/js/popper.js') }}"></script>
<!-- jquery adminlte -->
<script src="{{ asset('admin/node_modules/js/adminlte.js') }}"></script>
<!-- JS Files -->
<!-- javascript yield -->
@yield('javascript')
<!-- javascript yield -->
</body>
</html>
