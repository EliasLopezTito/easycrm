@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('/auth/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css')  }}
    {{ Html::style('/auth/plugins/Datepicker/datepicker3.css')  }}
    {{ Html::style('/auth/plugins/DataTables/css/dataTables.bootstrap.min.css')  }}
    <style type="text/css">
        table td{ padding:0 !important; margin: 0 !important;}
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1> Camioneta Recorrido <small> Mantenimiento </small></h1>
    </section>
    <section class="content">
        <form>
        <div class="row">
            <div class="col-sm-12">
                <input type="hidden" id="hidden_id" name="hidden_id" value="{{ $Recorrido->id }}">
                <input type="hidden" id="id" name="id" value="0">
                <input type="hidden" id="estado_id" name="estado_id" value="3">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-lg-12 col-md-6 col-sm-12">
                                <div class="col-sm-2">
                                    <label for="fecha"> FECHA: </label>
                                    <input type="text" name="fecha" id="fecha" class="form-control" autocomplete="off">
                                </div>
                                <div class="col-sm-2">
                                    <div class="mt-25">
                                        <button type="button" class="btn btn-warning col-sm-12" id="btnBuscar">
                                            <span class="fa fa-search"></span>  BUSCAR
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="mt-25">
                                        <button type="submit" class="btn btn-primary col-sm-12 " id="btnGuardarCamionetaRecorrido">
                                              GUARDAR
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="mt-25">
                                        <button type="submit" class="btn btn-success col-sm-12 " id="btnCerrarCamionetaRecorrido">
                                             CERRAR FECHA
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-15">
                            <div class="col-sm-12 mt-5">
                                <table id="tableCamionetaRecorrido" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ITEM</th>
                                            <th>CAMIONETA</th>
                                            <th>PLACA</th>
                                            <th>VINCULADO</th>
                                            <th>RECORRIDO KM</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
@endsection

@section('scripts')

    {{ Html::script('/auth/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.js') }}
    {{ Html::script('/auth/plugins/Datepicker/locales/bootstrap-datepicker.es.js') }}
    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.config.min.js') }}

    {{ Html::script('/auth/plugins/DataTables/js/jquery.dataTables.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/js/dataTables.bootstrap.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/plugins/buttons/dataTables.buttons.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/plugins/buttons/buttons.flash.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/plugins/buttons/jszip.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/plugins/buttons/buttons.html5.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/js/dataTables.config.min.js')  }}

    {{ Html::script('/auth/views/camionetaRecorrido/index.js')  }}
    {{ Html::script('/auth/views/camionetaRecorrido/indexMaintenance.js')  }}

@endsection
