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
                        <label for="fecha_inicio"> FECHA INICIO: </label>
                        <input type="text" name="fecha_inicio" id="fecha_inicio" class="form-control" autocomplete="off" >
                    </div>
                    <div class="col-sm-2">
                        <label for="fecha_final"> FECHA FINAL: </label>
                        <input type="text" name="fecha_final" id="fecha_final" class="form-control" autocomplete="off" >
                    </div>
                    <div class="col-sm-4">
                        <label for="camioneta_id"> CAMIONETAS: </label>
                        <select name="camioneta_id[]" id="camioneta_id" style="width:100% !important"  multiple="multiple"class="form-control" required>
                            @foreach($Camionetas as $q)
                                <option value="{{ $q->id }}">{{ $q->name  }}</option>
                            @endforeach
                        </select>
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
            <div class="col-sm-12 mt-15">
                <figure class="highcharts-figure">
                    <div id="container"></div>
                </figure>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.js') }}
    {{ Html::script('/auth/plugins/Datepicker/locales/bootstrap-datepicker.es.js') }}
    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.config.min.js') }}

    {{ Html::script('/auth/plugins/Highcharts/highcharts.js') }}
    {{ Html::script('/auth/plugins/Highcharts/modules/data.js') }}
    {{ Html::script('/auth/plugins/Highcharts/modules/drilldown.js') }}
    {{ Html::script('/auth/plugins/Highcharts/modules/exporting.js') }}
    {{ Html::script('/auth/plugins/Highcharts/modules/export-data.js') }}
    {{ Html::script('/auth/plugins/Highcharts/modules/accessibility.js') }}

    {{ Html::script('/auth/plugins/select2/js/select2.js') }}
    {{ Html::script('/auth/views/reporte/recorridoPorCamioneta.js') }}
@endsection
