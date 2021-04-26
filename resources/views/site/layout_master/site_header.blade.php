<!-- Header Start -->
<header>
    <div class="header-area">
        <div class="main-header header-sticky">
            <div class="container-fluid">
                <div class="menu-wrapper">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="index.html"><img src="" alt=""></a>
                    </div>
                    <!-- Main-menu -->
                    <div class="main-menu d-none d-lg-block">
                        <nav>
                            <ul id="navigation">
                                <li><a href="{{ route('home.index') }}">Home</a></li>
                                <li><a href="shop.html">shop</a></li>
                                <li><a href="about.html">about</a></li>
                                <li class="hot"><a href="#">Latest</a>
                                    <ul class="submenu">
                                        <li><a href="shop.html"> Product list</a></li>
                                        <li><a href="product_details.html"> Product Details</a></li>
                                    </ul>
                                </li>
                                <li><a href="blog.html">Blog</a>
                                    <ul class="submenu">
                                        <li><a href="blog.html">Blog</a></li>
                                        <li><a href="blog-details.html">Blog Details</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Pages</a>
                                    <ul class="submenu">
                                        <li><a href="login.html">Login</a></li>
                                        <li><a href="cart.html">Cart</a></li>
                                        <li><a href="elements.html">Element</a></li>
                                        <li><a href="confirmation.html">Confirmation</a></li>
                                        <li><a href="checkout.html">Product Checkout</a></li>
                                    </ul>
                                </li>
                                <li><a href="contact.html">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                    @if( Session::has('userSiteLogged') )
                        <div class="main-menu d-none d-lg-block">
                            <nav>
                                <ul id="navigation">
                                    <li><a href="#">{{ Session::get('userSiteLogged')->name }}</a>
                                        <ul class="submenu">
                                            <li><a href="#">Minha conta</a></li>
                                            <li><a href="#">Pedidos</a></li>
                                            <li><a href="#">Meios de pagamento</a></li>
                                            <li><a href="#">Cupons</a></li>
                                            <li><a href="#">Ajuda</a></li>
                                            <li><a href="{{ route('usersite.logout') }}">Sair da sessão</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                @endif
                <!-- Header Right -->
                    <div class="header-right">
                        <ul>
                            <li>
                                <div class="nav-search search-switch">
                                    <span class="flaticon-search"></span>
                                </div>
                            </li>
                            @if(! Session::has('userSiteLogged') )
                                <li><a href="{{ route('usersite.login') }}" class="btn btn-sm btn-danger"><span
                                                class="flaticon-user"></span> Entrar</a></li>
                            @endif
                            <li>
                                @if( Session::has('shopCartKit') || Session::has('shopCartProduct') )
                                    <a href="{{ route('cart.view') }}">
                                        <i class="fa fa-shopping-cart" aria-hidden="true">
                                            @if( Session::has('shopCartKit') && Session::has('shopCartProduct') )
                                                <span class="badge">{{ Session::get('shopCartKit')->totalQty + Session::get('shopCartProduct')->totalQty }}</span>
                                            @elseif( Session::has('shopCartKit') )
                                                <span class="badge">{{ Session::get('shopCartKit')->totalQty }}</span>
                                            @elseif( Session::has('shopCartProduct') )
                                                <span class="badge">{{ Session::get('shopCartProduct')->totalQty }}</span>
                                            @endif
                                        </i>
                                    </a>
                                @else
                                    <a href="#">
                                        <span class="flaticon-shopping-cart"></span>
                                    </a>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Mobile Menu -->
                <div class="col-12">
                    <div class="mobile_menu d-block d-lg-none"></div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header End -->
