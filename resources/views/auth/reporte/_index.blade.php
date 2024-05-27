@extends('auth.layout.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/plugins/daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/plugins/highcharts/highcharts.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Reportes KPI'Sc
        </h1>

        <ol class="breadcrumb top-5">
            <li class="breadcrumb-item">
                <label for="">Fecha</label>
                <div id="reportrange" class="text-capitalize" style="">
                        <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-angle-down"></i>
                </div>
            </li>
        </ol>
    </section>

    <section class="content">

        <div id="kpis"></div>

        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('user.reporte.vendedores') }}">
                    <div id="vision_general_estados"></div>
                </a>
            </div>
        </div>

        <!--<div class="row mt-20">
            <div class="col-md-12">
                <div id="registroDias"></div>
            </div>
        </div>-->

        <div class="row">
            <div class="col-md-12">
                <ul class="list-button">
                    <li><button type="button" class="btn-primary lead-color" data-info="{{ \easyCRM\App::$ESTADO_NUEVO }}"> Registros </button></li>
                    <li><button type="button" class="btn-primary matricula-color" data-info="{{ \easyCRM\App::$ESTADO_CIERRE }}"> Matrículas </button></li>
                </ul>
            </div>
        </div>

        <div class="row mb-20 hidden leads-matriculados">
            <div class="col-md-12">
                <div id="usuarios"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div id="modalidades" class="auto-color"></div>
            </div>
            <div class="col-md-6">
                <div id="carreras"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-20">
                <div id="cursos"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mt-20 hidden leads-matriculados">
                <div id="turnos" class="auto-color"></div>
            </div>
            <div class="col-md-6 mt-20">
                <div id="fuentes"></div>
            </div>
            <div class="col-md-6 mt-20">
                <div id="enterados"></div>
            </div>
            <div class="col-md-12 mt-20">
                <div id="provincias"></div>
            </div>
        </div>

    </section>

</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('auth/plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/moment/moment-with-locales.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/daterangepicker/daterangepicker.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('auth/plugins/highcharts/highcharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/highcharts/modules/funnel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/highcharts/modules/drilldown.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/highcharts/modules/exporting.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/highcharts/modules/export-data.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/highcharts/modules/accessibility.js') }}"></script>

    <script type="text/javascript" src="{{ asset('auth/js/reporte/index.min.js') }}"></script>
@endsection
