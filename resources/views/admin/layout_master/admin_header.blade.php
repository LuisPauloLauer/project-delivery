<?php
$pathImagens = \App\Library\FilesControl::getPathImages();
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="{{ route('dashboard.home') }}" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">Contato</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('dashboard.logout') }}" class="nav-link">Sair</a>
        </li>
    </ul>

    <!-- SEARCH FORM
    <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @if( Session::has('StoresUserAdm') )
            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="">
                    Lojas
                    <i class="fas fa-store"></i>
                    <span class="badge badge-danger navbar-badge">{{ count(Session::get('StoresUserAdm')) }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                    @foreach(Session::get('StoresUserAdm') as $store)
                        <a href="{{ route('usersadm.select.store', ['store' => $store['id']]) }}" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{ $pathImagens }}/stores/logo/{{ $store['id'] }}/small/{{ $store['image_logo'] }}" alt="User Avatar"
                                     class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        {{ $store['name'] }}
                                        @if($store['selected'])
                                            <span class="float-right text-sm text-green"><i class="fas fa-circle"></i></span>
                                        @endif
                                    </h3>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                    @endforeach
                </div>
            </li>
            <!-- Notifications Dropdown Menu -->
        @endif
        <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
            </li> -->
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
