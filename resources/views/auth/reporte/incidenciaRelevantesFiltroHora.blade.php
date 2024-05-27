@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('/auth/plugins/Datepicker/datepicker3.css')  }}
    {{ Html::style('/auth/views/reporte/index.css')  }}
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="row mt-15">
                    <div class="col-sm-2">
                        <label for="fechaInicio"> FECHA INICIO: </label>
                        <input type="text" name="fechaInicio" id="fechaInicio" class="form-control" autocomplete="off" >
                    </div>
                    <div class="col-sm-2">
                        <label for="fechaFinal"> FECHA FINAL: </label>
                        <input type="text" name="fechaFinal" id="fechaFinal" class="form-control" autocomplete="off" >
                    </div>
                    <div class="col-sm-2">
                        <div class="mt-25">
                            <button type="button" class="btn btn-primary col-sm-12" id="btnBuscar">
                                <span class="fa fa-search"></span>  BUSCAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-15">
            <div class="col-sm-12 text-center">
                <div class="mb-15">
                    <h3><b>Incidencia Relevantes Delicitivas Por Hora</b></h3>
                </div>
                <figure class="highcharts-figure">
                    <div id="container"></div>
                </figure>
                <div class="mt-15">
                    <figure class="highcharts-figure">
                        <div id="containerReporte"></div>
                    </figure>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.js') }}
    {{ Html::script('/auth/plugins/Datepicker/locales/bootstrap-datepicker.es.js') }}
    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.config.min.js') }}

    {{ Html::script('/auth/plugins/amcharts/core.js') }}
    {{ Html::script('/auth/plugins/amcharts/charts.js') }}
    {{ Html::script('/auth/plugins/amcharts/themes/animated.js') }}

    {{ Html::script('/auth/views/reporte/incidenciaRelevantesFiltroHora.js') }}
@endsection
