@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('/auth/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css')  }}
    {{ Html::style('/auth/plugins/Datepicker/datepicker3.css')  }}

    {{ Html::style('/auth/views/mapaIncidencia/index.css') }}
@endsection

@section('content')
            <div class="row">
                <div class="col-sm-3 list-info-modalidad">
                    <div class="col-sm-6">
                        <label for="fecha_inicio"> FECHA INICIO: </label>
                        <input type="text" name="fecha_inicio" id="fecha_inicio" class="form-control" autocomplete="off" >
                    </div>
                    <div class="col-sm-6">
                        <label for="fecha_final"> FECHA FINAL: </label>
                        <input type="text" name="fecha_final" id="fecha_final" class="form-control" autocomplete="off" >
                    </div>
                    <div class="col-sm-12 mt-5">
                        <h5>MODALIDADES:</h5>
                        <div id="frmModalidades" class="list-checkbox-modalidad">
                            <ul></ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 view-map p-0">
                    <div id="map"></div>
                </div>
             </div>
@endsection

@section('scripts')
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4WVUuCpUmlDT4vDU5CUUsT8LxdvbtGlc"></script>

    {{ Html::script('/auth/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.js') }}
    {{ Html::script('/auth/plugins/Datepicker/locales/bootstrap-datepicker.es.js') }}
    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.config.min.js') }}

    {{ Html::script('/auth/views/mapaIncidencia/index.js')  }}
@endsection
