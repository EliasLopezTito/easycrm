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
    <title>Bienvenidos a Comprando | 2020</title>
    <!-- CSS CUSTOM -->
    {{ Html::style('css/_Layout.css') }}
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="https://www.navegap.com/comprando/favicon.png">
</head>
<body class="bg-green">

<section>
    <div class="index-logo">
        {{ Html::image('img/logoblanco.png', 'Comprando') }}
        <p>Selecciona tu ciudad</p>
    </div>
    <div class="dptos">
        @foreach($Departaments as $q)
            <div>
                <p>{{ $q->name }}</p>
                <ul>
                    @foreach($q->cities as $c)
                        <li><a href="javascript:void(0)" onclick="citySelected('{{ $c->id }}')"> {{ $c->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <form action="{{ route('city') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" id="city" name="city">
    </form>

</section>

<!-- JQUERY -->
{{ Html::script('https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js') }}
<script>
    function citySelected($id){
        $("#city").val($id);$("form").submit();
    }
</script>

</body>
</html>


