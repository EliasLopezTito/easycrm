<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="NavegaP">
    <title>easyCRM</title>
    <link rel="stylesheet" href="{{ asset('auth/plugins/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/plugins/bootstrap/css/bootstrap-extend.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/plugins/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/plugins/toastr/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/css/layout/app.min.css') }}">
    @yield('styles')
</head>

<body>

<div class="wrapper">

    <div id="loading">
        <i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
    </div>

    <header class="main-header">
        <div class="inside-header">
            <a href="/" class="logo">
               <span class="logo-lg">
                  <img src="{{ asset('auth/image/logo.png') }}" alt="logo" class="light-logo">
                  <img src="{{ asset('auth/image/logo.png') }}" alt="logo" class="dark-logo">
              </span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle d-block d-lg-none" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li id="notifications" class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="mdi mdi-bell faa-ring animated"></i>
                            </a>
                            <ul class="dropdown-menu scale-up">
                                <li class="header">Tienes <span id="counNotificacion"></span> notificaciones</li>
                                <li>
                                    <ul class="menu inner-content-div"></ul>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ asset('auth/image/icon/usuario.jpg') }}" class="user-image" alt="User Image">
                            </a>
                            <ul class="dropdown-menu scale-up">
                                <li class="user-header">
                                    <img src="{{ asset('auth/image/icon/usuario.jpg') }}" class="float-left" alt="User Image">
                                    <p>
                                        {{ Auth::guard('web')->user()->name }}
                                        <small class="mb-5">{{ Auth::guard('web')->user()->email }}</small>
                                        <a href="#" class="btn btn-danger btn-sm btn-rounded">{{ Auth::guard('web')->user()->profiles->name }}</a>
                                    </p>
                                </li>
                                <li class="user-body">
                                    <div class="row no-gutters">
                                        <div class="col-12 text-left">
                                            <a id="ModalCambiarPassword" href="javascript:void(0)">
                                                <i class="fa fa-key"></i> Cambiar Contraseña
                                            </a>
                                            <a onclick="event.preventDefault();localStorage.setItem('cliente_id','');document.getElementById('logout-form').submit();">
                                                <i class="fa fa-power-off"></i> {{ __('Cerrar Sesión') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="main-nav">
        <nav class="navbar navbar-expand-lg">
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item {{ Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_CALL ? 'active' : (Route::currentRouteName() == 'user.home' ? 'active' : '') }}">
                        <a class="nav-link" href="/"><span class="active-item-here"></span>
                            <i class="fa fa-home mr-5"></i> <span>Inicio</span>
                        </a>
                    </li>
                    @if(Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR)
                        <li class="nav-item {{ Route::currentRouteName() == 'user.user' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('user.user') }}"><span class="active-item-here"></span>
                                <i class="fa fa-users mr-5"></i> <span>Usuarios</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array(Auth::guard('web')->user()->profile_id, [\easyCRM\App::$PERFIL_ADMINISTRADOR, \easyCRM\App::$PERFIL_VENDEDOR, \easyCRM\App::$PERFIL_PERDIDOS]))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.reporte') }}"><span class="active-item-here"></span>
                            <i class="fa fa-pie-chart mr-5"></i> <span>Reportes</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR)
                        <li class="nav-item">
                            <a id="importExcel" class="nav-link" href="javascript:void(0)"><span class="active-item-here"></span>
                                <i class="fa fa-upload mr-5"></i> <span>Importar</span>
                            </a>
                        </li>
                    @endif
                    @if(in_array(Auth::guard('web')->user()->profile_id, [\easyCRM\App::$PERFIL_ADMINISTRADOR, \easyCRM\App::$PERFIL_VENDEDOR, \easyCRM\App::$PERFIL_PERDIDOS, \easyCRM\App::$PERFIL_RESTRINGIDO, \easyCRM\App::$PERFIL_CAJERO]))
                    <li class="nav-item">
                        <a id="exportExcel" class="nav-link" href="javascript:void(0)"><span class="active-item-here"></span>
                            <i class="fa fa-download mr-5"></i> <span>Exportar </span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(Auth::guard('web')->user()->profile_id, [\easyCRM\App::$PERFIL_ADMINISTRADOR]))
                        <li class="nav-item">
                            <a id="exportExcel" class="nav-link" href="{{ route('user.client.resumenDiario') }}" target="_blank"><span class="active-item-here"></span>
                                <i class="fa fa-download mr-5"></i> <span>Resumen diario</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR)
                        <li class="nav-item dropdown {{ in_array(Route::currentRouteName() , ['user.estado']) ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="active-item-here"></span> <i class="fa fa-cog mr-5"></i> <span>Ajustes</span></a>
                            <ul class="dropdown-menu multilevel scale-up-left">
                                <li class="nav-item"><a class="nav-link" href="{{ route('user.modalidad') }}">Modalidades</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('user.carrera') }}">Cursos</a></li>
                            </ul>
                        </li>
                    @endif

                </ul>
            </div>
        </nav>
    </div>

    @yield('contenido')

    <footer class="main-footer">
        &copy; <?php echo date('Y') ?> Powered by <a href="http://www.navegap.com" target="_blank">NavegaP</a>. Todos los derechos reservados.
    </footer>

</div>

<script type="text/javascript" src="{{ asset('auth/plugins/jquery-3.3.1/jquery-3.3.1.js') }}"></script>
<script type="text/javascript" src="{{ asset('auth/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('auth/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('auth/plugins/toggle-sidebar/index.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('auth/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('auth/plugins/toastr/js/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('auth/js/_Layout.min.js') }}"></script>
<script type="text/javascript">
    const usuarioLoggin = {
        user_id: {{ \Illuminate\Support\Facades\Auth::guard('web')->user()->id  }},
        profile_id: {{ \Illuminate\Support\Facades\Auth::guard('web')->user()->profile_id  }}
    }
</script>

@yield('scripts')

</body>
</html>
