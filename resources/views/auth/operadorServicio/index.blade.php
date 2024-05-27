    @extends('layouts.auth.app')

@section('styles')
    {{ Html::style('/auth/plugins/Datepicker/datepicker3.css')  }}
    {{ Html::style('/auth/plugins/DataTables/css/dataTables.bootstrap.min.css')  }}
    <style type="text/css">
        table#tablaOrganizacion td:first-child{width: 50px; text-align: left;padding: 5px 15px !important; font-weight: 600;color: #222222;}
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1> Operadores Por Servicio <small> Control </small></h1>
    </section>
    <section class="content">
        <div class="row">
            <form>
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <div class="row">
                                @if(in_array(Auth::user()->perfil_id,[\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_GERENTE]))
                                <div class="col-md-12 text-left">
                                    <p style="padding: 5px 15px;" class="text-uppercase title"> <span style="color: #000">CIRCULANDO AHORA</span>
                                        : TUNO : <span style="color: #000">{{ $organizacion->turnos->name }}</span>  </p>
                                </div>
                                @endif
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="hidden" id="id" name="id" value="{{ $organizacion->id }}">
                                    <input type="hidden" id="turno_id" value="{{ $organizacion->turno_id }}">
                                    <input type="hidden" id="perfil_id" value="{{ Auth::user()->perfil_id }}">
                                    <input type="hidden" id="estado_id" value="{{ $organizacion->estado_id }}">
                                    <div class="col-sm-3">
                                        <label for="fecha"> FECHA: </label>
                                        <input type="text" name="fecha" id="fecha" class="form-control" autocomplete="off" >
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="turno"> TURNO: </label>
                                        <select name="turno" id="turno" class="form-control">
                                            @foreach($turnos as $turno)
                                                <option value="{{ $turno->id  }}"> {{$turno->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mt-25">
                                            <button type="button" class="btn btn-warning col-sm-12" id="btnBuscar">
                                                <span class="fa fa-search"></span>  BUSCAR
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mt-15">
                                        <div class="col-sm-12 col-md-6 p-0">
                                            <p class="text-uppercase title">OPERADORES POR SERVICIO</p>
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-sm-12 table-responsive">
                                            <table id="tablaOrganizacionPersonalServicio" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                <thead>
                                                <tr>
                                                    <th class="hidden"></th>
                                                    <th>NOMBRES</th>
                                                    <th>SERVICIO</th>
                                                    <th>RÉGIMEN LABORAL</th>
                                                </tr>
                                                </thead>
                                                @if($organizacionPersonalsServicio != null && count($organizacionPersonalsServicio) > 0)
                                                    <tbody>
                                                    @foreach($organizacionPersonalsServicio as $op)
                                                        <tr>
                                                            <td class="hidden"><input type="hidden" class="form-control" value="{{ $op->id  }}"> </td>
                                                            <td><select class="form-control"><option value="{{ $op->trabajador_id }}">{{ $op->trabajadores->name  }}</option></select></td>
                                                            <td><select class="form-control"><option value="{{ $op->servicio_id }}">{{ $op->servicios->name  }}</option></select></td>
                                                            <td><input type="text" value="{{ $op->regimen  }}" class="form-control" readonly="readonly"></td>
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
    {{ Html::script('/auth/views/operadorServicio/index.js')  }}

@endsection
