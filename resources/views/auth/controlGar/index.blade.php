@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('/auth/plugins/Datepicker/datepicker3.css')  }}
    {{ Html::style('/auth/plugins/DataTables/css/dataTables.bootstrap.min.css')  }}
@endsection

@section('content')
    <section class="content-header">
        <h1> Control Gar <small> Personal </small></h1>
    </section>
    <section class="content">
        <div class="row">
            <form>
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <div class="row">
                                <div class="col-lg-12 col-md-6 col-sm-12">
                                    <input type="hidden" id="id" name="id" value="{{ $controlGar->id }}">
                                    <input type="hidden" id="perfil_id" value="{{ Auth::user()->perfil_id }}">
                                    <input type="hidden" id="estado_id" value="{{ $controlGar->estado_id  }}">
                                @if(in_array(Auth::user()->perfil_id, [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEGAR]))
                                    <div class="col-sm-2">
                                        <label for="fecha"> FECHA: </label>
                                        <input type="text" name="fecha" id="fecha" class="form-control" autocomplete="off" >
                                    </div>
                                    @if(Auth::user()->perfil_id == \Incidencias\App::$PERFIL_ADMINISTRADOR)
                                        <div class="col-sm-2">
                                            <label for="grupo"> GRUPO: </label>
                                            <select name="grupo" id="grupo" class="form-control">
                                                @foreach($grupos as $grupo)
                                                    <option value="{{ $grupo->id  }}"> {{$grupo->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-sm-2">
                                        <div class="mt-25">
                                            <button type="button" class="btn btn-warning col-sm-12" id="btnBuscar">
                                                <span class="fa fa-search"></span>  BUSCAR
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 hidden">
                                        <div class="mt-25">
                                            <button type="button" class="btn btn-danger col-sm-12" id="btnEditar">
                                                <span class="fa fa-pencil"></span>  EDITAR
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="mt-25">
                                            <button type="submit" class="btn btn-success col-sm-12 {{ $controlGar->estado_id == \Incidencias\App::$ESTADO_FINALIZADO ? "" : "hidden"  }}" id="btnActualizar">
                                                <span class="fa fa-save"></span>  ACTUALIZAR
                                            </button>
                                            <button type="submit" class="btn btn-primary col-sm-12 {{ $controlGar->estado_id == \Incidencias\App::$ESTADO_FINALIZADO ? "hidden" : ""  }}" id="btnGuardar">
                                                <span class="fa fa-save"></span>  GUARDAR
                                            </button>
                                        </div>
                                    </div>

                                    @if($controlGar->estado_id != \Incidencias\App::$ESTADO_FINALIZADO)
                                        <div class="col-sm-2">
                                            <div class="mt-25">
                                                <button type="submit" class="btn btn-success col-sm-12" id="btnEnviar">
                                                    <span class="fa fa-send"></span>  Enviar
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mt-15">
                                        <div class="col-sm-12 col-md-6 p-0">
                                            <p class="text-uppercase title">LISTADO DEL PERSONAL <span id="textGrupo"> DEL {{ Auth::user()->grupos->name  }} </span></p>
                                        </div>
                                        <div class="col-sm-12 col-md-6 p-0">
                                            <button id="btnAgregarPersonalGar" type="button" class="btn btn-success pull-right mb-15"> <i class="fa fa-plus"></i> </button>
                                        </div>
                                    </div>
                                    <table id="tablaIncidenciaPersonalGar" class="table table-striped  table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th class="hidden"></th>
                                                <th>NOMBRES</th>
                                                <th>MOTIVO</th>
                                                <th>RADIO</th>
                                                <th>RÉGIMEN LABORAL</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        @if($controlGarPersonals != null && count($controlGarPersonals) > 0)
                                            <tbody>
                                            @foreach($controlGarPersonals as $cp)
                                                <tr>
                                                    <td class="hidden"><input type="hidden" class="form-control" value="{{ $cp->id  }}"> </td>
                                                    <td><select class="form-control"><option value="{{ $cp->trabajador_id }}">{{ $cp->trabajadores->name  }}</option></select></td>
                                                    <td><select class="form-control"><option value="{{ $cp->motivo_personal_id }}">{{ $cp->motivosPersonal->name  }}</option></select></td>
                                                    <td><input type="text" value="{{ $cp->radio != 0 ? $cp->radio : ""   }}" class="form-control text-right number"></td>
                                                    <td><input type="text" value="{{ $cp->regimen }}" class="form-control" readonly="readonly"></td>
                                                    <td class="text-center"><button type="button" class="btn btn-danger btn-xs btn-delete mt-5" data-toggle="tooltip"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')

    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.js') }}
    {{ Html::script('/auth/plugins/Datepicker/locales/bootstrap-datepicker.es.js') }}
    {{ Html::script('/auth/plugins/Datepicker/bootstrap-datepicker.config.min.js') }}

    {{ Html::script('/auth/plugins/DataTables/js/jquery.dataTables.min.js')  }}

    {{ Html::script('/auth/plugins/select2/js/select2.js') }}
    {{ Html::script('/auth/views/controlGar/index.js')  }}
    {{ Html::script('/auth/views/controlGar/indexMaintenance.js')  }}

@endsection
