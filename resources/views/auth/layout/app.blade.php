<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="description" content="">
    <meta name="author" content="WebAltoque">
    <title>easyCRM</title>
    <link rel="stylesheet" href="{{ asset('auth/plugins/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/plugins/bootstrap/css/bootstrap-extend.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/plugins/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/plugins/toastr/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/css/layout/app.min.css') }}">
    <style>
        .logo-v2{
            width: 100%;
        }
        @media (max-width: 768px) {
            .logo-v2 {
                width: 150px;
            }
        }
    </style>
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
                    <img src="{{ asset('auth/image/logo_3.png') }}" alt="logo" class="light-logo logo-v2">
                    <img src="{{ asset('auth/image/logo_3.png') }}" alt="logo" class="dark-logo logo-v2">
                </span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle d-block d-lg-none bg-primary" data-toggle="push-menu" role="button" style="border-radius: 10px;padding: 10px;width: 35px;text-align: center">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li id="notificationsFollowUp" class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="mdi mdi-bell faa-ring animated text-danger"></i>
                            </a>
                            <ul class="dropdown-menu scale-up">
                                <li class="header">Tienes <span id="counNotificacionCash"></span> notificaciones caja</li>
                                <li>
                                    <ul class="menu inner-content-div"></ul>
                                </li>
                            </ul>
                        </li>
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
                                            <a href="javascript:void(0)">
                                                <b class="text-success">●</b> En Línea
                                            </a>
                                            @if(\Illuminate\Support\Facades\Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR)
                                                <a id="ModalCambiarPassword" href="javascript:void(0)">
                                                    <i class="fa fa-key"></i> Cambiar Contraseña
                                                </a>
                                            @endif
                                            <a onclick="event.preventDefault();localStorage.setItem('cliente_id','');document.getElementById('logout-form').submit();">
                                                <i class="fa fa-power-off"></i> {{ __('Cerrar Sesión') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                                <input type="text" name="validacion" value="{{ Auth::guard('web')->user()->email }}">
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
                    @if(Auth::check() && Auth::user()->email == "useraul@gmail.com")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.reporte-admin') }}"><span class="active-item-here"></span>
                                <i class="fa fa-pie-chart mr-5"></i> <span>Reportes Administrativo</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::check() && Auth::user()->profile_id  == 1)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.edit-client') }}"><span class="active-item-here"></span>
                                <i class="fa fa-users mr-5"></i>  <span>Edición de Cliente</span>
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('seguimiento.index') }}"><span class="active-item-here"></span>
                            <i class="fa fa-book mr-5"></i> <span>Seguimiento</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR)
                        <li class="nav-item dropdown {{ in_array(Route::currentRouteName() , ['user.estado']) ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="active-item-here"></span> <i class="fa fa-cog mr-5"></i> <span>Ajustes</span></a>
                            <ul class="dropdown-menu multilevel scale-up-left">
                                <li class="nav-item"><a class="nav-link" href="{{ route('user.modalidad') }}"><i class="fa fa-angle-right"></i> Modalidades</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('user.carrera') }}"><i class="fa fa-angle-right"></i> Cursos</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('user.reportehistorial') }}"><i class="fa fa-angle-right"></i> Reporte Historial</a></li>
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
<script type="text/javascript" src="{{ asset('auth/js/_LayoutV2.min.js') }}"></script>
<script>
        const urlBringNotifications = "{{ route('user.client.notifications-tracking') }}";
        const urlSeeObservation = "{{ route('user.client.see-observation', ':id') }}";
        const urlSeeObservationAdditional = "{{ route('user.client.see-observation-additional', ':id') }}";
        const roleProfile = "{{ Auth::check() ? Auth::user()->profile_id : '' }}";
    </script>
<script type="text/javascript" src="{{ asset('auth/js/followUp.js') }}"></script>
<script type="text/javascript">
    const usuarioLoggin = {
        user_id: {{ \Illuminate\Support\Facades\Auth::guard('web')->user()->id  }},
        profile_id: {{ \Illuminate\Support\Facades\Auth::guard('web')->user()->profile_id  }}
    };
</script>

@yield('scripts')

</body>
</html>
