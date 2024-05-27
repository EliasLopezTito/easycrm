<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Control Incidencias</title>

    <link rel="icon" href="https://www.munijesusmaria.gob.pe/wp-content/uploads/2019/02/favicon-munijesusmaria-100x100.png" sizes="32x32">
    <!-- Bootstrap 3.3.7 -->
    {{ Html::style('/auth/plugins/bootstrap/dist/css/bootstrap.min.css') }}
    <!-- Font Awesome -->
    {{ Html::style('/auth/plugins/font-awesome/css/font-awesome.min.css') }}
    <!-- Theme style -->
    {{ Html::style('/auth/adminLTE/css/AdminLTE.css') }}
    <!-- iCheck -->
    {{ Html::style('/auth/plugins/iCheck/square/blue.css') }}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    {{ Html::script('https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js') }}
    {{ Html::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') }}
    <![endif]-->
    <!-- Google Font -->
    {{ Html::style('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic') }}
</head>
<body class="hold-transition login-page">
<div class="login-box">

    <div class="login-logo">
        {{ Html::image('/auth/image/logo-mdjm.png', 'Municipalidad de Jesús María', array('class' => 'img-responsive')) }}
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Inicie Sesion para Gestionar</p>

        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
            @csrf
            <div class="form-group has-feedback">
                <input id="email" type="text" placeholder="Username" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                       value="{{ old('email') }}" required autofocus>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group has-feedback">
                <input id="password" type="password" placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>

            <div class="row">
                <div class="col-xs-7">
                    <div class="checkbox icheck">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">
                            {{ __('Recordarme') }}
                        </label>
                    </div>
                </div>
                <div class="col-xs-5">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        {{ __('Login') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- jQuery 3 -->
{{ Html::script('/auth/plugins/jquery/dist/jquery.min.js') }}
<!-- Bootstrap 3.3.7 -->
{{ Html::script('/auth/plugins/bootstrap/dist/js/bootstrap.min.js') }}
<!-- iCheck -->
{{ Html::script('/auth/plugins/iCheck/icheck.min.js') }}
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });
    });
</script>
</body>
</html>
