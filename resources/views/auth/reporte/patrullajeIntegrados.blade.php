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
                            <button type="button" class="btn btn-danger col-sm-12" id="btnExportPdf">
                                <i class="fa fa-file-pdf-o"></i> Exportar PDF
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="mt-25">
                            <button type="button" class="btn btn-success col-sm-12" id="btnExportExcel">
                                <i class="fa fa-file-excel-o"></i> Exportar Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.js') }}
    {{ Html::script('/auth/plugins/Datepicker/locales/bootstrap-datepicker.es.js') }}
    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.config.min.js') }}

    {{ Html::script('/auth/views/reporte/patrullajeIntegrados.js') }}
@endsection
