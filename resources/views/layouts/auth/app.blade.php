<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> Control Incidencias</title>
    <!-- Bootstrap 3.3.7 -->
    {{ Html::style('/auth/plugins/bootstrap/dist/css/bootstrap.min.css') }}
    <!-- Font Awesome -->
    {{ Html::style('/auth/plugins/font-awesome/css/font-awesome.min.css') }}
    {{ Html::style('/auth/plugins/select2/css/select2.min.css') }}
    <!-- Theme style -->
    {{ Html::style('/auth/adminLTE/css/AdminLTE.css') }}
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    {{ Html::style('/auth/adminLTE/css/skins/skin-black.css') }}
    {{ Html::style('/auth/plugins/sweetalert/sweetalert.min.css') }}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    {{ Html::script('https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js') }}
    {{ Html::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') }}
    <![endif]-->
    <!-- Google Font -->
    {{ Html::style('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic') }}
    <!-- Layout CSS -->
    {{ Html::style('/auth/layout/css/_Layout.css') }}

    @yield('styles')
</head>

<body class="hold-transition skin-black sidebar-mini {{ explode("auth/", $_SERVER['REQUEST_URI'])[1] == "mapaIncidencia" ? "sidebar-collapse" : "" }} ">

<div id="loading">
    <i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
</div>

<div class="wrapper">
    <header class="main-header">
        <a href="#" class="logo">
            <span class="logo-mini">
              {{ Html::image('/auth/image/logo-mdjm-escudo.jpg', 'Municipalidad Jesús María', array('class' => 'img-responsive')) }}
            </span>
            <span class="logo-lg">
              {{ Html::image('/auth/image/logo-mdjm-alternative.jpg', 'Municipalidad Jesús María', array('class' => 'img-responsive')) }}
            </span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ Html::image('/uploads/users/'.Auth::user()->images->name, 'User Image', array('class' => 'user-image')) }}
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                {{ Html::image('/uploads/users/'.Auth::user()->images->name, 'User Image', array('class' => 'img-circle')) }}
                                <p>{{ Auth::user()->name }} <small>{{ Auth::user()->perfils->name }}</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a id="logout" class="btn btn-default btn-flat">
                                        {{ __('Cerrar Sesión') }}
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    {{ Html::image('/uploads/users/'.Auth::user()->images->name, 'User Image', array('class' => 'css-class')) }}
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <small>{{ Auth::user()->perfils->name }}</small>
                </div>
            </div>
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">Menu Navegación</li>

               <!--<li>
                    <a href="{{ route('home') }}">
                        <i class="fa fa-dashboard"></i> <span>Inicio</span>
                    </a>
                </li>-->

                @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_JEFEOPERACION, \Incidencias\App::$PERFIL_ADMINISTRADOR,
                \Incidencias\App::$PERFIL_MACRO1, \Incidencias\App::$PERFIL_MACRO2, \Incidencias\App::$PERFIL_MACRO3,
                \Incidencias\App::$PERFIL_RSF]))
                    <li>
                        <a href="{{ route('incidencia')  }}">
                            <i class="fa fa-book"></i> <span>Incidencias</span>
                        </a>
                    </li>
                @endif

                @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_ADMINISTRADOR,
                 \Incidencias\App::$PERFIL_JEFEGAR]))
                <li>
                    <a href="{{ route('controlGar')  }}">
                        <i class="fa fa-users"></i> <span>Control Gar</span>
                    </a>
                </li>
                @endif

                @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_ADMINISTRADOR,
                \Incidencias\App::$PERFIL_SUPERVISORCECOM1, \Incidencias\App::$PERFIL_SUPERVISORCECOM2, \Incidencias\App::$PERFIL_SUPERVISORCECOM3]))
                    <li>
                        <a href="{{ route('organizacion') }}">
                            <i class="fa fa-cubes"></i> <span>Organización Servicio</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('incidenciaRelevante') }}">
                            <i class="fa fa-map"></i> <span>Incidencia Relevante</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('mapaIncidencia')  }}">
                        <i class="fa fa-map-marker"></i> <span>Mapa Incidencias</span>
                    </a>
                </li>
                @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_GERENTE]))
                    <li>
                        <a href="{{ route('operadorServicio')  }}">
                            <i class="fa fa-users"></i> <span>Operadores Por Servicio</span>
                        </a>
                    </li>
                @endif
                @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_ADMINISTRADOR,
                \Incidencias\App::$PERFIL_SUPERVISORCECOM1, \Incidencias\App::$PERFIL_SUPERVISORCECOM2, \Incidencias\App::$PERFIL_SUPERVISORCECOM3]))
                    <li>
                        <a href="{{ route('patrullajeIntegrado') }}">
                            <i class="fa fa-truck"></i> <span>Patrullaje Integrado</span>
                        </a>
                    </li>
                @endif

                @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_ADMINISTRADOR]))
                    <li>
                        <a href="{{ route('camionetaRecorrido') }}">
                            <i class="fa fa-car"></i> <span>Recorrido Camioneta</span>
                        </a>
                    </li>
                @endif

                @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_GERENTE, \Incidencias\App::$PERFIL_JEFEOPERACION,
                \Incidencias\App::$PERFIL_SUPERVISORCECOM1, \Incidencias\App::$PERFIL_SUPERVISORCECOM2, \Incidencias\App::$PERFIL_SUPERVISORCECOM3]))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-database"></i> <span>Reportes</span>
                        <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('reporte.patrullajeIntegrados') }}"><i class="fa fa-circle-o"></i>Patrullaje Integrados</a></li>
                        <li><a href="{{ route('reporte.incidenciaRelevantes') }}"><i class="fa fa-circle-o"></i>Incidencia Relevante</a></li>
                        <li><a href="{{ route('reporte.incidenciaRelevantesFiltroMensual') }}"><i class="fa fa-circle-o"></i>Incidencia Relevante - Mes</a></li>
                        <li><a href="{{ route('reporte.incidenciaRelevantesFiltroSectores') }}"><i class="fa fa-circle-o"></i>Incidencia Relevante - Sector</a></li>
                        <li><a href="{{ route('reporte.incidenciaRelevantesFiltroTrimestre') }}"><i class="fa fa-circle-o"></i>Incidencia Relevante - Trimestre</a></li>
                        @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_GERENTE, \Incidencias\App::$PERFIL_ADMINISTRADOR]))
                            <li><a href="{{ route('reporte.incidenciaRelevantesFiltroHora') }}"><i class="fa fa-circle-o"></i>Incidencia Relevante - Hora</a></li>
                            <li><a href="{{ route('reporte.recorridoCamioneta') }}"><i class="fa fa-circle-o"></i>Recorrido Camioneta</a></li>
                            <li><a href="{{ route('reporte.recorridoPorCamioneta') }}"><i class="fa fa-circle-o"></i>Recorrido Por Camioneta</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_ADMINISTRADOR]))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cogs"></i> <span>Configuración</span>
                        <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('arma') }}"><i class="fa fa-circle-o"></i>Arma</a></li>
                        <li><a href="{{ route('categoria') }}"><i class="fa fa-circle-o"></i>Categoría</a></li>
                        <li><a href="{{ route('clasificacionIncidencia') }}"><i class="fa fa-circle-o"></i>Clasificación Incidencia</a></li>
                        <li><a href="{{ route('vehiculo') }}"><i class="fa fa-circle-o"></i>Vehículo</a></li>
                        <li><a href="{{ route('camioneta') }}"><i class="fa fa-circle-o"></i>Camioneta</a></li>
                        <li><a href="{{ route('estado') }}"><i class="fa fa-circle-o"></i>Estado Camara</a></li>
                        <li><a href="{{ route('modalidadIncidencia') }}"><i class="fa fa-circle-o"></i>Modalidad Incidencia</a></li>
                        <li><a href="{{ route('contrato') }}"><i class="fa fa-circle-o"></i>Contrato</a></li>
                        <li><a href="{{ route('cargo') }}"><i class="fa fa-circle-o"></i>Cargo</a></li>
                        <li><a href="{{ route('cargoPersonal') }}"><i class="fa fa-circle-o"></i>Cargo Personal</a></li>
                        <li><a href="{{ route('motivoPersonal') }}"><i class="fa fa-circle-o"></i>Motivo Personal</a></li>
                        <li><a href="{{ route('trabajador') }}"><i class="fa fa-circle-o"></i> Trabajadores </a></li>
                        <li><a href="{{ route('parqueAutomotor') }}"><i class="fa fa-circle-o"></i>Parque Automotor</a></li>
                        <li><a href="{{ route('delitoPnp') }}"><i class="fa fa-circle-o"></i>Delitos PNP</a></li>
                        <li><a href="{{ route('lugarIncidencia') }}"><i class="fa fa-circle-o"></i>Lugar Incidencia</a></li>
                    </ul>
                </li>
                @endif

                @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_GERENTE]))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-lock"></i> <span>Seguridad</span>
                        <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('roserenazgo') }}"><i class="fa fa-circle-o"></i>Roserenazgo</a></li>
                        <li><a href="{{ route('comisaria') }}"><i class="fa fa-circle-o"></i>Comisaria</a></li>
                        @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_ADMINISTRADOR]))
                             <li><a href="{{ route('user') }}"><i class="fa fa-circle-o"></i>Usuario</a></li>
                        @endif
                    </ul>
                </li>
                @endif

            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        @yield('content')
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; <?php echo date("Y"); ?></strong> Todos los derechos Reservados.
    </footer>
</div>

<!-- jQuery 3 -->
{{ Html::script('/auth/plugins/jquery/dist/jquery.min.js') }}
<!-- Bootstrap 3.3.7 -->
{{ Html::script('/auth/plugins/bootstrap/dist/js/bootstrap.min.js') }}
<!-- SlimScroll -->
{{ Html::script('/auth/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}
<!-- FastClick -->
{{ Html::script('/auth/plugins/fastclick/lib/fastclick.js') }}
{{ Html::script('/auth/plugins/Date.Format/date.format.min.js') }}
<!-- Moment -->
{{ Html::script('/auth/plugins/Moment/moment.min.js') }}
{{ Html::script('/auth/plugins/Moment/es.js') }}
<!--Encryptor-->
{{ Html::script('/auth/plugins/Jquery.base64/jquery.base64.min.js') }}
{{ Html::script('/auth/plugins/inputmask/dist/min/jquery.inputmask.bundle.min.js') }}
{{ Html::script('/auth/plugins/sweetalert/sweetalert.min.js') }}
<!-- AdminLTE App -->
{{ Html::script('/auth/adminLTE/js/adminlte.min.js') }}
<!-- Layout JS -->
{{ Html::script('/auth/layout/_Layout.js') }}

<script type="text/javascript">
    $(document).ready(function () {
        $("ul.sidebar-menu").find("a").each(function (i, e) {
            if ($(e).attr('href') !== "#") {
                const controller = $(e).attr("href").split('/')[4];
                if ('/auth/' + controller === $(location).attr('pathname')) {
                    $(e).parent('li').addClass("active");
                    $(e).parents('.treeview-menu').addClass("menu-open");
                    $(e).parents('.treeview').addClass("active");
                }
            }
        });
    });

    $("#logout").click(function() {
        const formData = new FormData();
        formData.append('_token', $("meta[name=csrf-token]").attr("content"));
        actionAjax("/logout", formData, "POST");
        window.location.href = "/";
    });


</script>

@yield('scripts')
