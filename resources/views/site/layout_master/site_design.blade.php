<!doctype html>
<html lang="pt-br">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delivery</title>
    <!-- CSS Files -->
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('admin/adminLTE/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('site/node_modules/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('site/node_modules/css/master-style.css') }}">
        <!-- Stylescss yield -->
        @yield('Stylescss')
        <!-- Stylescss yield -->
    <!-- CSS Files -->
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- validations_javascript yield -->
    @yield('validations_javascript')
    <!-- validations_javascript yield -->

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('site/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('site/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('site/assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('site/assets/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('site/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('site/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('site/assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('site/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('site/assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('site/assets/css/nice-select.css') }}">

</head>
<body>
    @include('site.layout_master.site_header')

    @yield('sidebar')

    @yield('content')

    @include('site.layout_master.site_footer')

    <!-- JS Files -->
        <!-- jquery -->
        <script src="{{ asset('site/node_modules/js/jquery.js') }}"></script>
        <!-- bootstrap -->
        <script src="{{ asset('site/node_modules/js/bootstrap.js') }}"></script>
        <!-- bootstrap.bundle -->
        <script src="{{ asset('site/node_modules/js/bootstrap.bundle.js') }}"></script>
        <!-- popper -->
        <script src="{{ asset('site/node_modules/js/popper.js') }}"></script>
    <!-- JS Files -->
    <!-- javascript yield -->
    @yield('javascript')
    <!-- javascript yield -->

    <!-- JS here -->
    <script src="{{ asset('site/assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <!-- Jquery Mobile Menu -->
    <script src="{{ asset('site/assets/js/jquery.slicknav.min.js') }}"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="{{ asset('site/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('site/assets/js/slick.min.js') }}"></script>

    <!-- One Page, Animated-HeadLin -->
    <script src="{{ asset('site/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('site/assets/js/animated.headline.js') }}"></script>
    <script src="{{ asset('site/assets/js/jquery.magnific-popup.js') }}"></script>

    <!-- Scrollup, nice-select, sticky -->
    <script src="{{ asset('site/assets/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('site/assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('site/assets/js/jquery.sticky.js') }}"></script>

    <!-- contact js -->
    <script src="{{ asset('site/assets/js/contact.js') }}"></script>
    <script src="{{ asset('site/assets/js/jquery.form.js') }}"></script>
    <script src="{{ asset('site/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('site/assets/js/mail-script.js') }}"></script>
    <script src="{{ asset('site/assets/js/jquery.ajaxchimp.min.js') }}"></script>

    <!-- Jquery Plugins, main Jquery -->
    <script src="{{ asset('site/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('site/assets/js/main.js') }}"></script>
</body>
</html>