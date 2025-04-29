@extends('auth.layout.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="auth/plugins/daterangepicker/daterangepicker.css" />
    <style>
        @media (max-width: 768px) {
            .sub-title-v2 {
                font-size: 18px !important;
                display: inline-block;
                margin: 0;
                padding: 0; 
            }
            #cards-list{
                padding: 20px;
            }
        }
    </style>
@endsection

@section('contenido')
<div class="content-wrapper">

    <div id="loading-clients">
        <p>Cargando...</p>
    </div>

    <section class="content-header">
        <div class="row">
            <div class="col-lg-4 col-12">
                <h1 class="sub-title-v2">
                    Inicio
                    <small>Control panel</small>
                </h1>
            </div>
            <div class="col-lg-4 col-12">
                <div class="form-group" style="padding: 10px;">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Buscar por DNI, nombres o celular" name="name" id="name">
                        <div class="input-group-append">
                            <button class="filterSearch btn-primary" type="button"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <ol class="breadcrumb">
    
                    @if (Auth::guard('web')->user()->id != 1)
                        @if(\Illuminate\Support\Facades\Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR)
                        <li class="mr-10">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <select name="reasignar_id" id="reasignar_id" class="form-input">
                                        <option value="">--REASIGNAR--</option>
                                        @foreach($Vendedores as $q)
                                            <option value="{{ $q->id }}">{{ $q->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </li>
                        @endif
                    @endif
        
                    <li class="breadcrumb-item">
                        <button id="modalFiltroBusqueda" type="button" data-toggle="modal" data-target="#modal-right" class="btn-secondary"><i class="fa fa-search"></i> Búsqueda por Filtro</button>
                        <button id="modalRegistrarInscripcion" type="button" class="btn-primary"><i class="fa fa-pencil-square-o"></i> Registrar Lead</button>
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section id="cards-list" class="content clientes endless-pagination" data-next-page></section>

    <section id="cards-detail" class="content hidden"></section>

    <div class="modal modal-right fade" id="modal-right" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Búsqueda por Filtro</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="">Fecha</label>
                                <div id="reportrange" class="text-capitalize" style="">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-angle-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="estado_id_filter">Estado</label>
                                <select name="estado_id_filter" id="estado_id_filter" class="form-input text-capitalize">
                                        <option value="">-- Todos --</option>
                                    @foreach($Estados as $q)
                                        <option value="{{ $q->id }}">{{ $q->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @if(\Illuminate\Support\Facades\Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR)
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="vendedor_id">Vendedor</label>
                                <select name="vendedor_id" id="vendedor_id" class="form-input text-capitalize">
                                    <option value="">-- Todos --</option>
                                    @foreach($Vendedores as $q)
                                        <option value="{{ $q->id }}">{{ ($q->profile_id == \easyCRM\App::$PERFIL_RESTRINGIDO ? "RE - " : "") . $q->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
                    <button type="button" class="filterSearch btn-primary float-right">Búsqueda por Filtro</button>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="auth/plugins/moment/moment.min.js"></script>
    <script type="text/javascript" src="auth/plugins/moment/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="auth/plugins/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="auth/js/home/index.js"></script>
@endsection
