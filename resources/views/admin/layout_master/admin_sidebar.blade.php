<?php
$UserAuth = \Illuminate\Support\Facades\Auth::user();
if (($UserAuth->tpuser <> 1) && ($UserAuth->tpuser <> 2)) {
    $StoreUserAdm = \App\mdRelStoresUsersAdm::where('useradm', $UserAuth->id)->first();
    $AffiliateStore = \App\mdStores::where('id', $StoreUserAdm->store)->first();
}
$pathImagens = \App\Library\FilesControl::getPathImages();
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand Logo -->
    <a href="{{ route('dashboard.home') }}" class="brand-link">
        <img src="{{ url('admin/adminLTE/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(!is_null($UserAuth->path_image))
                    @if( ($UserAuth->tpuser == 1) || ($UserAuth->tpuser == 2) )
                        <img
                            src="{{ $pathImagens }}/usersAdm/administrator/{{ $UserAuth->id}}/small/{{ $UserAuth->path_image }}"
                            class="img-circle elevation-2" alt="User Image">
                    @else
                        <img
                            src="{{ $pathImagens }}/usersAdm/affiliate_id_{{$AffiliateStore->affiliate}}/{{ $UserAuth->id}}/small/{{ $UserAuth->path_image }}"
                            class="img-circle elevation-2" alt="User Image">
                    @endif
                @else
                    <img src="{{ $pathImagens }}/../../files/images/no_photo.png" class="img-circle elevation-2"
                         alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ $UserAuth->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview
                    {{ (
                        (Route::current()->getName() === 'tpaffiliates.index') || (Route::current()->getName() === 'tpaffiliates.create') || (Route::current()->getName() === 'tpaffiliates.edit')
                        || (Route::current()->getName() === 'affiliates.index') || (Route::current()->getName() === 'affiliates.create') || (Route::current()->getName() === 'affiliates.edit')
                        || (Route::current()->getName() === 'segments.index') || (Route::current()->getName() === 'segments.create') || (Route::current()->getName() === 'segments.edit')
                        || (Route::current()->getName() === 'categoriesstore.index') || (Route::current()->getName() === 'categoriesstore.create') || (Route::current()->getName() === 'categoriesstore.edit')
                        || (Route::current()->getName() === 'stores.index') || (Route::current()->getName() === 'stores.create') || (Route::current()->getName() === 'stores.edit')
                        || (Route::current()->getName() === 'usersadm.index') || (Route::current()->getName() === 'usersadm.create') || (Route::current()->getName() === 'usersadm.edit')
                        || (Route::current()->getName() === 'categoriesproduct.index') || (Route::current()->getName() === 'categoriesproduct.create') || (Route::current()->getName() === 'categoriesproduct.edit')
                        || (Route::current()->getName() === 'kits.pesq') || (Route::current()->getName() === 'kits.search') || (Route::current()->getName() === 'kits.create') || (Route::current()->getName() === 'kits.edit')
                        || (Route::current()->getName() === 'products.pesq') || (Route::current()->getName() === 'products.search') || (Route::current()->getName() === 'products.create') || (Route::current()->getName() === 'products.edit')
                        || (Route::current()->getName() === 'store.perfil')
                        || (Route::current()->getName() === 'store.mapregions')
                        ? 'menu-open' : ''
                        ) }}
                    ">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-pen"></i>
                        <p>
                            Cadastros
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('managerAdministrator')
                            <li class="nav-item">
                                <a href="{{ route('tpaffiliates.index') }}"
                                   class="nav-link {{ ((Route::current()->getName() === 'tpaffiliates.index') || (Route::current()->getName() === 'tpaffiliates.create') || (Route::current()->getName() === 'tpaffiliates.edit') ? 'active' : '') }}">
                                    <i class="fas fa-font nav-icon"></i>
                                    <p>Tipo Afiliados</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('affiliates.index') }}"
                                   class="nav-link {{ ((Route::current()->getName() === 'affiliates.index') || (Route::current()->getName() === 'affiliates.create') || (Route::current()->getName() === 'affiliates.edit') ? 'active' : '') }}">
                                    <i class="fab fa-amilia nav-icon"></i>
                                    <p>Afiliados</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('segments.index') }}"
                                   class="nav-link {{ ((Route::current()->getName() === 'segments.index') || (Route::current()->getName() === 'segments.create') || (Route::current()->getName() === 'segments.edit') ? 'active' : '') }}">
                                    <i class="fab fa-stripe-s nav-icon"></i>
                                    <p>Segmentos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('categoriesstore.index') }}"
                                   class="nav-link {{ ((Route::current()->getName() === 'categoriesstore.index') || (Route::current()->getName() === 'categoriesstore.create') || (Route::current()->getName() === 'categoriesstore.edit') ? 'active' : '') }}">
                                    <i class="fab fa-cuttlefish	nav-icon"></i>
                                    <p>Categoria Lojas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('stores.index') }}"
                                   class="nav-link {{ ((Route::current()->getName() === 'stores.index') || (Route::current()->getName() === 'stores.create') || (Route::current()->getName() === 'stores.edit') ? 'active' : '') }}">
                                    <i class="fas fa-store nav-icon"></i>
                                    <p>Lojas</p>
                                </a>
                            </li>
                        @endcan
                        @can('managerUsersAdm')
                            <li class="nav-item">
                                <a href="{{ route('usersadm.index') }}"
                                   class="nav-link {{ ((Route::current()->getName() === 'usersadm.index') || (Route::current()->getName() === 'usersadm.create') || (Route::current()->getName() === 'usersadm.edit') ? 'active' : '') }}">
                                    <i class="far fa-user nav-icon"></i>
                                    <p>Usuários</p>
                                </a>
                            </li>
                        @endcan
                        @can('managerProducts')
                            <li class="nav-item">
                                <a href="{{ route('categoriesproduct.index') }}"
                                   class="nav-link {{ ((Route::current()->getName() === 'categoriesproduct.index') || (Route::current()->getName() === 'categoriesproduct.create') || (Route::current()->getName() === 'categoriesproduct.edit') ? 'active' : '') }}">
                                    <i class="far nav-icon">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-journal-text"
                                             fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                                            <path
                                                d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                                            <path fill-rule="evenodd"
                                                  d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                                        </svg>
                                    </i>
                                    <p>Categoria Kits/Produtos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('kits.pesq', ['pesqdefault' => 'index']) }}"
                                   class="nav-link {{ ((Route::current()->getName() === 'kits.pesq') || (Route::current()->getName() === 'kits.search') || (Route::current()->getName() === 'kits.create') || (Route::current()->getName() === 'kits.edit') ? 'active' : '') }}">
                                    <i class="far nav-icon">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-journal-x"
                                             fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                                            <path
                                                d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                                            <path fill-rule="evenodd"
                                                  d="M6.146 6.146a.5.5 0 0 1 .708 0L8 7.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 8l1.147 1.146a.5.5 0 0 1-.708.708L8 8.707 6.854 9.854a.5.5 0 0 1-.708-.708L7.293 8 6.146 6.854a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </i>
                                    <p>Kits</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('products.pesq', ['pesqdefault' => 'index']) }}"
                                   class="nav-link {{ ((Route::current()->getName() === 'products.pesq') || (Route::current()->getName() === 'products.search') || (Route::current()->getName() === 'products.create') || (Route::current()->getName() === 'products.edit') ? 'active' : '') }}">
                                    <i class="far nav-icon">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-journal"
                                             fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                                            <path
                                                d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                                        </svg>
                                    </i>
                                    <p>Produtos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('store.perfil') }}"
                                   class="nav-link {{ ((Route::current()->getName() === 'store.perfil') ? 'active' : '') }}">
                                    <i class="fas fa-store nav-icon"></i>
                                    <p>Dados Loja</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('store.mapregions') }}"
                                   class="nav-link {{ ((Route::current()->getName() === 'store.mapregions') ? 'active' : '') }}">
                                    <i class="fas fa-store nav-icon"></i>
                                    <p>Regiões de entrega</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @can('managerProducts')
                    <li class="nav-item has-treeview
                        {{ (
                            (url()->current() === env('APP_URL').'/dashboard/orders/included' ) ||
                            (url()->current() === env('APP_URL').'/dashboard/orders/confirmed' ) ||
                            (url()->current() === env('APP_URL').'/dashboard/orders/togodelivery' ) ||
                            (url()->current() === env('APP_URL').'/dashboard/orders/delivered' )
                            ? 'menu-open' : ''
                            ) }}
                        ">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                Pedidos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('view.orders', ['status' => 'included' ]) }}"
                                   class="nav-link {{ ((url()->current() === env('APP_URL').'/dashboard/orders/included' ) ? 'active' : '') }}">
                                    <i class="fas fa-notes-medical nav-icon"></i>
                                    <p>Incluídos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('view.orders', ['status' => 'confirmed' ]) }}"
                                   class="nav-link {{ ((url()->current() === env('APP_URL').'/dashboard/orders/confirmed' ) ? 'active' : '') }}">
                                    <i class="fas fa-spinner nav-icon"></i>
                                    <p>Preparando</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('view.orders', ['status' => 'togodelivery' ]) }}"
                                   class="nav-link {{ ((url()->current() === env('APP_URL').'/dashboard/orders/togodelivery' ) ? 'active' : '') }}">
                                    <i class="fa fa-truck nav-icon"></i>
                                    <p>Entregando</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('view.orders', ['status' => 'delivered' ]) }}"
                                   class="nav-link {{ ((url()->current() === env('APP_URL').'/dashboard/orders/delivered' ) ? 'active' : '') }}">
                                    <i class="fas fa-clipboard-check nav-icon"></i>
                                    <p>Prontos</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<!-- Main Sidebar Container -->
