@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('/auth/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css')  }}
    {{ Html::style('/auth/plugins/Datepicker/datepicker3.css')  }}
    {{ Html::style('/auth/plugins/DataTables/css/dataTables.bootstrap.min.css')  }}
@endsection

@section('content')
    <section class="content-header">
        <h1> Patrullaje Integrado <small> Mantenimiento </small></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <input type="hidden" id="perfil_id" name="perfil_id" value="{{ Auth::user()->perfil_id }}">
                            <input type="hidden" id="user_actual_id" name="user_actual_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" id="turno_actual_id" name="turno_actual_id" value="{{ $turnoOrganizacion->turno_id  }}">
                            <div class="col-lg-12 col-md-6 col-sm-12">
                                <div class="col-sm-2">
                                    <label for="fecha"> FECHA: </label>
                                    <input type="text" name="fecha" id="fecha" class="form-control" autocomplete="off">
                                </div>
                                <div class="col-sm-2">
                                    <label for="turno"> TURNO: </label>
                                    <select name="turno" id="turno" class="form-control">
                                        @foreach($turnos as $turno)
                                            <option value="{{ $turno->id  }}" {{ $turnoOrganizacion->turno_id == $turno->id ? "selected" : "" }}> {{$turno->name}} </option>
                                        @endforeach
                                    </select>
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
                                        <button type="submit" class="btn btn-success col-sm-12 " id="btnNewPatrullajeIntegrado">
                                            <span class="fa fa-plus"></span>  NUEVO REGISTRO
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mt-15">
                                    <div class="col-sm-12 col-md-6 p-0">
                                        <p class="text-uppercase title">LISTADO DEL PERSONAL</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-5">
                                <table id="tablePatrullajeIntegrado" class="table table-striped table-bordered table-hover"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    {{ Html::script('/auth/views/patrullajeIntegrado/index.js')  }}

@endsection
