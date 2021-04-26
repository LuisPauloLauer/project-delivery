@extends('site.layout_master.site_design')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-1">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><h5><a href="{{ route('home.index') }}">Home</a></h5></li>
                            <li class="breadcrumb-item active">Cadastro de Usuários</li>
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
                                <div class="div-btn-facebook">
                                    <div class="form-row">
                                        <div class="section-action-container">
                                            <a href="{{ route('usersite.login.facebook') }}" class="a-fb">
                                                <div class="fb-button-container">
                                                    Login com FaceBook
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="div-btn-facebook">
                                    <div class="form-row">
                                        <div class="section-action-container">
                                            <a href="{{ route('usersite.login.google') }}" class="a-fb">
                                                <div class="fb-button-container">
                                                    Login com google
                                                </div>
                                            </a>
                                        </div>
                                    </div>
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
