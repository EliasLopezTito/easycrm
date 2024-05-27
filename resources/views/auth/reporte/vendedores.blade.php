@extends('auth.layout.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/plugins/daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/plugins/highcharts/highcharts.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Reportes
            <small>Vendedores KPI's</small>
        </h1>

        <ol class="breadcrumb top-5">
            @if(Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR)
            <li class="mr-15">
                <label for="vendedor_id">Vendedores</label>
                <select name="vendedor_id" id="vendedor_id" class="form-input">
                        <option value="">-- Todos --</option>
                    @foreach($Vendedores as $q)
                        <option value="{{ $q->id }}">{{ $q->name }}</option>
                    @endforeach
                </select>
            </li>
            @endif
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

        <div class="row">
            <div class="col-md-12">
                <div id="vision_general_estados"></div>
            </div>
        </div>

        <div class="row mt-20 mb-20">
            <div class="col-md-6">
                <div id="vision_general_pipeLine"></div>
            </div>
            <div class="col-md-6">
                <div id="acciones" class="auto-color"></div>
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

    <script type="text/javascript" src="{{ asset('auth/js/reporte/vendedores.min.js') }}"></script>

@endsection
