@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('/auth/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css')  }}
    {{ Html::style('/auth/plugins/Datepicker/datepicker3.css')  }}
    {{ Html::style('/auth/views/reporte/index.css')  }}
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="row mt-15">
                    <div class="col-sm-2">
                        <label for="fecha"> FECHA : </label>
                        <input type="text" name="fecha" id="fecha" class="form-control" autocomplete="off" >
                    </div>
                    <div class="col-sm-2 hidden">
                        <label for="hora_inicio"> DESDE: </label>
                        <input type="text" name="hora_inicio" id="hora_inicio" class="form-control" autocomplete="off" >
                    </div>
                    <div class="col-sm-2 hidden">
                        <label for="hora_final"> HASTA: </label>
                        <input type="text" name="hora_final" id="hora_final" class="form-control" autocomplete="off" >
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

    {{ Html::script('/auth/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.js') }}
    {{ Html::script('/auth/plugins/Datepicker/locales/bootstrap-datepicker.es.js') }}
    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.config.min.js') }}

    {{ Html::script('/auth/plugins/Highcharts/highcharts.js') }}
    {{ Html::script('/auth/plugins/Highcharts/modules/data.js') }}
    {{ Html::script('/auth/plugins/Highcharts/modules/drilldown.js') }}
    {{ Html::script('/auth/plugins/Highcharts/modules/exporting.js') }}
    {{ Html::script('/auth/plugins/Highcharts/modules/export-data.js') }}
    {{ Html::script('/auth/plugins/Highcharts/modules/accessibility.js') }}

    {{ Html::script('/auth/views/reporte/recorridoCamioneta.js') }}
@endsection
