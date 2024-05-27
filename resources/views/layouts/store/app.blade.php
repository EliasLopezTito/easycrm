<!DOCTYPE html>
<html lang="en">
<head>
    <title>ComprandoPe, el MarketPlace del Perú</title>
    <meta charSet="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="HandheldFriendly" content="True"/>
    <meta http-equiv="cleartype" content="on"/>
    <meta name="browser-support" content="samesite=true"/>
    <meta name="description" content="La plataforma de internet creada para conectar vendedores y compradores de forma rápida, fácil y segura."/>
    <meta property="og:title" content="La plataforma de internet creada para conectar vendedores y compradores de forma rápida, fácil y segura.">
    <meta property="og:description" content="La plataforma de internet creada para conectar vendedores y compradores de forma rápida, fácil y segura.">
    <meta property="og:image" content="https://www.comprando.pe/img/oglogo.png">
    <meta property="og:url" content="https://www.comprando.pe/">
    <meta property="og:site_name" content="ComprandoPe">
    <meta property="og:type" content="website">
    <meta name="author" content="NavegaP" />
    <meta name="Resource-type" content="Document" />
    <meta http-equiv="X-UA-Compatible" content="IE=5; IE=6; IE=7; IE=8; IE=9; IE=10">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS CUSTOM -->
    {{ Html::style('css/_Layout.css') }}
    <!-- FONT AWESOME -->
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css') }}
    <!-- MATERIAL INCON -->
    {{ Html::style('https://fonts.googleapis.com/icon?family=Material+Icons') }}
    <!-- CAROUSEL -->
    {{ Html::style('plugins/owl-carousel/owl.carousel.min.css') }}
    {{ Html::style('plugins/owl-carousel/owl.theme.default.min.css') }}
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="https://www.comprando.pe/favicon.png">
    <!-- SELEC2 -->
    {{ Html::style('/auth/plugins/select2/css/select2.min.css') }}

</head>
<body>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-D0V0SD0V12"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-D0V0SD0V12');
</script>
<script type="text/javascript">
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '222798889038163',
            cookie     : true,
            xfbml      : true,
            version    : 'v8.0'
        });
        FB.AppEvents.logPageView();
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

@yield('viewComment')

<div class="loading">
    <p>Cargando...</p>
</div>

<!-- SIDE MENU MOBILE -->
<section class="side-menu">
    <div class="side-logo">
        <a href="{{ route('index') }}">{{ Html::image('img/logo.png', 'Comprando') }}</a>
        <div class="btn-out">X</div>
    </div>
    <div class="side-options">
        @if (Auth::guard('client')->check())
            <p class="side-userlogin">Hola, {{ Auth::guard('client')->user()->name }}</p>
        @endif
        <ul class="menu-rwd">
            <br>
            @if (Auth::guard('client')->check())
                <li><a href="{{ route('client.shopping') }}">Órdenes</a></li>
                <li><a href="{{ route('client.favorite') }}">Favoritos</a></li>
                <li><a href="{{ route('client.storeFavorite') }}">Tiendas</a></li>
                <li><a href="{{ route('client.account') }}">Perfil</a></li>
            @endif
            <li><a href="{{ route('cart') }}">Mi carrito (<span class="CartCount">0</span>) </a></li>
            <li><hr></li>
            <li><a href="{{ route('categories') }}">Categorías</a></li>
            <li><a href="{{ route('stores') }}">Negocios</a></li>
            <li><a href="{{ route('offers') }}">Ofertas</a></li>
            <li><a href="https://api.whatsapp.com/send?phone=51956470738&text=%C2%A1Hola%20Quiero%20vender." target="_blank">Quiero vender</a></li>
            <li><a href="javascript:void(0)">¿Necesitas ayuda?</a></li>
            <br>
        </ul>
    </div>
    @if (!Auth::guard('client')->check())
        <div class="session">
            <a href="{{ route('client.register') }}" class="botones sesion-crear">Crea tu cuenta</a>
            <a href="{{ route('client.login') }}" class="botones sesion-login">Iniciar sesión</a>
            <p class="o">- O -</p>
            <div class="boton-fbk">
            <a href="{{ route('client.provider', 'facebook') }}" class="botones btnface">Iniciar con Facebook</a>
            </div>
        </div>
    @else
        <div class="session">
            <a href="{{ route('client.logout') }}" class="botones sesion-login">Cerrar sesión</a>
        </div>
    @endif
</section>
<!-- SIDE MENU MOBILE -->

<header>
    <section class="header-wrapp">
        <div class="header-div">
            <a href="{{ route('index') }}">
                {{ Html::image('/img/logo.png', '', array('class' => 'logoimg logow')) }}
                {{ Html::image('/img/isotipo.png', '', array('class' => 'logoimg logom')) }}
            </a>
        </div>
        <div class="header-div">
            <form action="{{ route('product.search') }}" method="get" class="input-group search-bar " role="search">
                <input type="search" name="q"  placeholder="Buscar productos" id="txtsearch">
                <input type="submit" value="" id="search">
            </form>
            <ul class="main-menu">
                <li><a href="{{ route('categories') }}">Categorias</a></li>
                <li><a href="{{ route('stores') }}">Negocios</a></li>
                <li><a href="{{ route('offers') }}">Ofertas</a></li>
                <li><a href="https://api.whatsapp.com/send?phone=51956470738&text=%C2%A1Hola%20Quiero%20vender." target="_blank">Quiero vender</a></li>
                <li><a href="">¿Necesitas ayuda?</a></li>
            </ul>
        </div>
        <div class="header-div">
            <div class="header-cuenta">
                <div class="num-compras"><span class="CartCount">0</span></div>
                <a href="{{ route('cart') }}"><i class="material-icons">shopping_cart</i></a>
                @if (Auth::guard('client')->check())
                    <a href="{{ route('client.logout') }}"><i class="material-icons">login</i></a>
                @else
                    <a href="{{ route('client.account') }}"><i class="material-icons">person</i></a>
                @endif
            </div>
            <ul class="main-menu nav-cta">
                @if (Auth::guard('client')->check())
                    <li><a href="{{ route('client.account') }}">Hola, {{ Auth::guard('client')->user()->name }}</a></li>
                    <li><a href="{{ route('client.shopping') }}">Mis órdenes</a></li>
                @else
                    <li><a href="{{ route('client.login') }}">Ingresar</a></li>
                    <li><a href="{{ route('client.register') }}">Soy nuevo</a></li>
                @endif
            </ul>
            <a href="{{ route('cart') }}">
                <div class="cart-mobile">
                    <div class="num-compras"><span class="CartCount">0</span></div>
                    <i class="material-icons">shopping_cart</i>
                </div>
            </a>
            <div class="btn-menu">
                <i class="fas fa-bars fa-2x"></i>
            </div>
        </div>
    </section>
</header>

@yield('main')


<!-- OPTIONS -->
<section class="footer-opt">
    <a href="https://api.whatsapp.com/send?phone=51956470738&text=%C2%A1Hola%20Quiero%20vender." target="_blank" class="botones ContactWsp">Quiero vender</a>
    <a href="https://api.whatsapp.com/send?phone=51956470738&text=%C2%A1Hola%20Quiero%20anunciar." target="_blank" class="botones ContactWsp">Quiero anunciar</a>
</section>

<!-- FOOTER -->
<footer>
    <div>
        Términos y condiciones <span class="separa">|</span> Políticas de privacidad<br><br>
        Copyright © <?php echo date("Y"); ?> ComprandoPe by NavegaP
    </div>
    <div>
        <i class="fab fa-facebook"></i>&nbsp;&nbsp; <i class="fab fa-instagram"></i>
    </div>
</footer>

<!-- JQUERY -->
{{ Html::script('https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js') }}
<!-- CARRUSEL JS -->
{{ Html::script('plugins/owl-carousel/owl.carousel.js') }}
<!-- INPUTMASK JS-->
{{ Html::script('/auth/plugins/inputmask/dist/min/jquery.inputmask.bundle.min.js') }}
<!-- CUSTOM JS -->
{{ Html::script('js/_Layout.js') }}
<!-- CUSTOM JS -->
{{ Html::script('js/_ShoppingCart.js') }}
<!-- CUSTOM JS -->
{{ Html::script('js/_ShoppingFavorite.js') }}
<!-- CUSTOM JS -->
{{ Html::script('js/_StoreFavorite.js') }}

@yield('scripts')

</body>
</html>