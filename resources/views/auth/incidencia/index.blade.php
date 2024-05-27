@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('/auth/plugins/Datepicker/datepicker3.css')  }}
    {{ Html::style('/auth/plugins/DataTables/css/dataTables.bootstrap.min.css')  }}
    <style type="text/css">
        table#tablaIncidencia td:first-child, table#tablaIncidenciaParqueAutomotor td:first-child, table#tablaIncidenciaPersonalFijo td:first-child{width: 150px; text-align: left;padding-top: 5px !important;padding-left: 25px!important; font-weight: 600;color: #222222;}
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1> Incidencias <small> Control </small></h1>
    </section>
    <section class="content">
        <div class="row">
            <form>
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <div class="row">
                                @if(Auth::user()->perfil_id == \Incidencias\App::$PERFIL_ADMINISTRADOR)
                                <div class="col-md-12 text-left">
                                    <p style="padding: 5px 15px;" class="text-uppercase title"> <span style="color: #000">CIRCULANDO AHORA</span>
                                        : GRUPO : <span style="color: #000"> {{ $incidencia->grupos->name }}</span> - TUNO : <span style="color: #000">{{ $incidencia->turnos->name }}</span>  </p>
                                </div>
                                @endif
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="hidden" id="id" name="id" value="{{ $incidencia->id }}">
                                    <input type="hidden" id="perfil_id" value="{{ Auth::user()->perfil_id }}">
                                    <input type="hidden" id="estado_id" value="{{ $incidencia->estado_id }}">
                                    @if(!in_array(Auth::user()->perfil_id, [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEOPERACION]))
                                        <input type="hidden" id="estado_perfil_id" value="{{ $estadoPerfil_id }}">
                                    @endif
                                    @if(in_array(Auth::user()->perfil_id, [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEOPERACION]))
                                    <div class="{{ Auth::user()->perfil_id == \Incidencias\App::$PERFIL_ADMINISTRADOR ? "col-sm-3" : "col-sm-4" }}">
                                        <label for="fecha"> FECHA: </label>
                                        <input type="text" name="fecha" id="fecha" class="form-control" autocomplete="off" >
                                    </div>
                                    @if(Auth::user()->perfil_id == \Incidencias\App::$PERFIL_ADMINISTRADOR)
                                        <div class="{{ Auth::user()->perfil_id == \Incidencias\App::$PERFIL_ADMINISTRADOR ? "col-sm-3" : "col-sm-4" }}">
                                            <label for="grupo"> GRUPO: </label>
                                            <select name="grupo" id="grupo" class="form-control">
                                                @foreach($grupos as $grupo)
                                                    <option value="{{ $grupo->id  }}"> {{$grupo->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="{{ Auth::user()->perfil_id == \Incidencias\App::$PERFIL_ADMINISTRADOR ? "col-sm-3" : "col-sm-4" }}">
                                        <div class="mt-25">
                                            <button type="button" class="btn btn-warning col-sm-12" id="btnBuscar">
                                                <span class="fa fa-search"></span>  BUSCAR
                                            </button>
                                        </div>
                                    </div>
                                    <div class="{{ Auth::user()->perfil_id == \Incidencias\App::$PERFIL_ADMINISTRADOR ? "col-sm-3" : "col-sm-4" }}">
                                        <div class="mt-25">
                                            <button type="button" class="btn btn-danger col-sm-12" id="btnEditar">
                                                <span class="fa fa-pencil"></span>  EDITAR
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @if(in_array(Auth::user()->perfil_id, [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEOPERACION]))
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <table id="tablaHistoralPersonal" class="table table-striped table-bordered table-hover dataTable no-footer">
                                            <thead>
                                            <tr>
                                                @foreach($incidencia->historiales as $history)
                                                    <th>{{ $history->perfils->name }}</th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody class="text-center">
                                            <tr>
                                                @foreach($incidencia->historiales as $history)
                                                    <td><b>{{ $history->estados->name }}</b> <br>
                                                        {{ $history->user_name  }}</td>
                                                @endforeach
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <div class="col-sm-12 mt-15 ">
                                    <div class="col-md-6 col-sm-12">
                                        <p class="text-uppercase title">DISTRIBUCIÓN DEL PERSONAL DE {{ in_array(Auth::user()->perfil_id,
                                            [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEOPERACION]) ? "TODAS LAS MACROS" : "LA ". Auth::user()->macros->name }}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12 p-0">
                                        @if(Auth::user()->perfil_id == \Incidencias\App::$PERFIL_ADMINISTRADOR)
                                            <button type="submit" class="btn btn-success pull-right col-sm-3" id="btnActualizarMacro">
                                                <span class="fa fa-save"></span>  ACTUALIZAR
                                            </button>
                                        @elseif(Auth::user()->perfil_id == \Incidencias\App::$PERFIL_JEFEOPERACION)
                                            <button type="submit" class="btn btn-success pull-right col-sm-3 {{ in_array($incidencia->estado_id, [\Incidencias\App::$ESTADO_ENVIADA , \Incidencias\App::$ESTADO_FINALIZADO] ) ? "hidden" : ""  }}" id="btnCerrarTurno">
                                                <span class="fa fa-close"></span>  CERRAR TURNO
                                            </button>
                                            <button type="submit" class="btn {{ in_array($incidencia->estado_id, [\Incidencias\App::$ESTADO_ENVIADA , \Incidencias\App::$ESTADO_FINALIZADO] ) ? "btn-success" : "btn-primary"  }} pull-right col-sm-3 " id="btnActualizarMacro">
                                                <span class="fa fa-save"></span>  ACTUALIZAR
                                            </button>
                                        @elseif(!in_array(Auth::user()->perfil_id, [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEOPERACION]))
                                            <button type="submit" class="btn btn-success pull-right col-sm-3 {{ in_array($estadoPerfil_id, [\Incidencias\App::$ESTADO_ENVIADA , \Incidencias\App::$ESTADO_FINALIZADO] ) ? "hidden" : ""  }}" id="btnEnviarMacro">
                                                <span class="fa fa-send"></span>  Enviar
                                            </button>
                                            <button type="submit" class="btn btn-primary pull-right col-sm-3 {{ in_array($estadoPerfil_id, [\Incidencias\App::$ESTADO_ENVIADA , \Incidencias\App::$ESTADO_FINALIZADO] ) ? "hidden" : ""  }}" id="btnGuardarMacro">
                                                <span class="fa fa-save"></span>  GUARDAR
                                            </button>
                                        @endif
                                            <button type="button" class="btn btn-warning pull-right col-sm-3" id="btnVisualizarCecom">
                                                <span class="fa fa-eye"></span>  VISUALIZAR CECOM
                                            </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-15">
                                <div class="col-sm-12 table-responsive">
                                    <table id="tablaIncidencia" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                        <tr>
                                            <th>CARGOS</th>
                                            @foreach($sectores as $sector)
                                                <th class="hidden">SECTOR ID</th>
                                                <th class="hidden">CARGO ID</th>
                                                <th>{{ $sector->name  }}</th>
                                            @endforeach
                                            <th>TOTAL</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($cargos as $cargo)
                                            <tr>
                                                <td>{{ $cargo->name }}</td>
                                                @foreach($sectores as $sector)
                                                    <td class="hidden"><input type="text" name="IncidenciaSector_id[]" class="form-control" value="{{$sector->id}}"></td>
                                                    <td class="hidden"><input type="text" name="IncidenciaCargo_id[]" class="form-control" value="{{$cargo->id}}"></td>
                                                    <td><input type="text"  name="IncidenciaCantidad[]" class="form-control change-sectors numeric"
                                                        value="{{ ($incidenciaSectors->where('sector_id', $sector->id)->where('cargo_id', $cargo->id)->first() != null &&
                                                         $incidenciaSectors->where('sector_id', $sector->id)->where('cargo_id', $cargo->id)->first()->cantidad != 0 ) ?
                                                          $incidenciaSectors->where('sector_id', $sector->id)->where('cargo_id', $cargo->id)->first()->cantidad : ""}}"></td>
                                                @endforeach
                                                <td><input type="text" name="IncidenciaSubtotal[]" class="form-control change-sectors-total numeric" readonly="readonly"></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="col-sm-12 mt-15">
                                        <p class="text-uppercase title">RESUMEN GENERAL</p>
                                    </div>
                                    <div class="form-group mt-15">
                                        <div class="col-sm-12">
                                            <label for="efectivo"> EFECTIVOS: </label>
                                            <input type="text" name="efectivo" id="efectivo" class="form-control numeric" readonly="readonly" >
                                        </div>
                                        <div class="col-sm-12">
                                            <label for="descuento"> DESCUENTOS: </label>
                                            <input type="text" name="descuento" id="descuento" class="form-control numeric" readonly="readonly" >
                                        </div>
                                        <div class="col-sm-12">
                                            <label for="total"> TOTAL: </label>
                                            <input type="text" name="total" id="total" class="form-control numeric" readonly="readonly" >
                                        </div>
                                    </div>
                                    @if(in_array(Auth::user()->perfil_id, [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEOPERACION]))
                                    <div class="col-sm-12 mt-15">
                                        <p class="text-uppercase title">AUSENTES POR MACRO</p>
                                    </div>
                                    <div class="form-group mt-15">
                                        <div class="col-sm-6">
                                            <label for="macro1_ausente"> MACRO 1: </label>
                                            <input type="text" name="macro1_ausente" id="macro1_ausente" class="form-control numeric" readonly="readonly"
                                            value="{{ $incidenciaPersonals->whereIn('sector_id', [\Incidencias\App::$SECTOR_1,\Incidencias\App::$SECTOR_2,\Incidencias\App::$SECTOR_3])->count() }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="macro2_ausente"> MACRO 2: </label>
                                            <input type="text" name="macro2_ausente" id="macro2_ausente" class="form-control numeric" readonly="readonly"
                                             value="{{ $incidenciaPersonals->whereIn('sector_id', [\Incidencias\App::$SECTOR_4,\Incidencias\App::$SECTOR_5,\Incidencias\App::$SECTOR_6])->count() }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="macro3_ausente"> MACRO 3: </label>
                                            <input type="text" name="macro3_ausente" id="macro3_ausente" class="form-control numeric" readonly="readonly"
                                             value="{{ $incidenciaPersonals->whereIn('sector_id', [\Incidencias\App::$SECTOR_7,\Incidencias\App::$SECTOR_8])->count() }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="macro4_ausente"> MACRO 4: </label>
                                            <input type="text" name="macro4_ausente" id="macro4_ausente" class="form-control numeric" readonly="readonly"
                                             value="{{ $incidenciaPersonals->whereIn('sector_id', [\Incidencias\App::$SECTOR_9])->count() }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-15">
                                        <p class="text-uppercase title">PERSONAL GAR</p>
                                    </div>
                                    <div class="form-group mt-15">
                                        <div class="col-sm-6">
                                            <label for="personalGar_ausente"> AUSENTES: </label>
                                            <input type="text" name="personalGar_ausente" id="personalGar_ausente" class="form-control numeric" readonly="readonly"
                                                   value="{{ $controlGarPersonals->where('motivo_personal_id', '!=' ,\Incidencias\App::$MOTIVO_ASISTIO)->count() }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="personalGar_asistido"> ASISTIDOS: </label>
                                            <input type="text" name="personalGar_asistido" id="personalGar_asistido" class="form-control numeric" readonly="readonly"
                                                   value="{{ $controlGarPersonals->whereIn('motivo_personal_id', [\Incidencias\App::$MOTIVO_ASISTIO])->count() }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-15">
                                        <p class="text-uppercase title">PERSONAL FIJO</p>
                                    </div>
                                    <div class="form-group mt-15">
                                        <div class="col-sm-12">
                                            <table id="tablaIncidenciaPersonalFijo" class="table table-striped  table-bordered table-hover dataTable no-footer">
                                                <thead>
                                                <tr>
                                                    <th>PERSONAL FIJO</th>
                                                    <th>TOTAL</th>
                                                </tr>
                                                </thead>
                                                    <tbody>
                                                    @foreach($personalFijos as $pf)
                                                        <tr>
                                                            <td> {{ $pf->name }} <input type="hidden" name="IncidenciaPersonalFijo_id[]" class="form-control" value="{{ $pf->id  }}"></td>
                                                            <td><input type="text" name="IncidenciaPersonalFijo_total[]" class="form-control change-fijos numeric" {{ in_array($pf->id, [\Incidencias\App::$PERSONALFIJO_OPTELFBASEMYT, \Incidencias\App::$PERSONALFIJO_PER276SUP]) ? "readonly" : ""  }}
                                                                   value="{{ $incidenciaPersonalFijos->where('personalFijo_id', $pf->id)->first()->total != 0 ?  $incidenciaPersonalFijos->where('personalFijo_id', $pf->id)->first()->total : "" }}"></td>
                                                        </tr>
                                                    @endforeach
                                                        <tr>
                                                            <td>SUB TOTAL</td>
                                                            <td><input type="text" class="form-control change-fijo-subtotal" value="" readonly="readonly"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>


                                <div class="col-sm-9">
                                    <div class="mt-15">
                                        <div class="col-sm-12 col-md-6 p-0">
                                            <p class="text-uppercase title">PERSONAL AUSENTE DE {{ in_array(Auth::user()->perfil_id,
                                        [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEOPERACION]) ? "TODAS LAS MACROS" : "LA ". Auth::user()->macros->name }}</p>
                                        </div>
                                        <div class="col-sm-12 col-md-6 p-0">
                                            <button id="btnAgregarPersonal" type="button" class="btn btn-success pull-right mb-15"> <i class="fa fa-plus"></i> </button>
                                        </div>
                                    </div>
                                    <table id="tablaIncidenciaPersonal" class="table table-striped  table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th class="hidden"></th>
                                                <th>NOMBRES</th>
                                                <th>MOTIVO</th>
                                                <th>SECTOR</th>
                                                <th>RÉGIMEN LABORAL</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        @if($incidenciaPersonals != null && count($incidenciaPersonals) > 0)
                                        <tbody>
                                            @foreach($incidenciaPersonals as $ip)
                                            <tr>
                                                <td class="hidden"><input type="hidden" class="form-control" value="{{ $ip->id  }}"> </td>
                                                <td><select class="form-control"><option value="{{ $ip->trabajador_id }}">{{ $ip->trabajadores->name  }}</option></select></td>
                                                <td><select class="form-control"><option value="{{ $ip->motivo_personal_id }}">{{ $ip->motivosPersonal->name  }}</option></select></td>
                                                <td><select class="form-control"><option value="{{ $ip->sector_id }}">{{ $ip->sectores->name  }}</option></select></td>
                                                <td><input type="text" value="{{ $ip->regimen  }}" class="form-control"></td>
                                                <td class="text-center"><button type="button" class="btn btn-danger btn-xs btn-delete mt-5" data-toggle="tooltip"><i class="fa fa-trash"></i></button></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        @endif
                                    </table>
                                    <div class="mt-15">
                                        <div class="col-sm-12 col-md-6 p-0">
                                            <p class="text-uppercase title">LISTA PARQUE AUTOMOTORES</p>
                                        </div>
                                        <table id="tablaIncidenciaParqueAutomotor" class="table table-striped table-bordered table-hover dataTable no-footer">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">PARQUE AUTOMOTOR</th>
                                                    @foreach($macros as $m)
                                                        @if(!in_array(Auth::user()->perfil_id, [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEOPERACION]))
                                                            @if($m->id == Auth::user()->macro_id)
                                                                <th colspan="2" rowspan="1">{{ $m->name  }}</th>
                                                            @endif
                                                        @else
                                                         <th colspan="2" rowspan="1">{{ $m->name  }}</th>
                                                        @endif
                                                    @endforeach
                                                    <th rowspan="2">TOTAL</th>
                                                </tr>
                                                <tr>
                                                    @foreach($macros as $m)
                                                        @if(!in_array(Auth::user()->perfil_id, [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEOPERACION]))
                                                            @if($m->id == Auth::user()->macro_id)
                                                                <th colspan="1" rowspan="1" class="hidden">USER</th>
                                                                <th colspan="1" rowspan="1" class="hidden">PARQUE</th>
                                                                <th colspan="1" rowspan="1">OP.</th>
                                                                <th colspan="1" rowspan="1">INOP.</th>
                                                            @endif
                                                        @else
                                                        <th colspan="1" rowspan="1" class="hidden">USER</th>
                                                        <th colspan="1" rowspan="1" class="hidden">PARQUE</th>
                                                        <th colspan="1" rowspan="1">OP.</th>
                                                        <th colspan="1" rowspan="1">INOP.</th>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($parqueAutomotors as $parque)
                                                <tr>
                                                    <td>{{ $parque->name }}</td>
                                                    @foreach($macros as $m)
                                                        @if(!in_array(Auth::user()->perfil_id, [\Incidencias\App::$PERFIL_ADMINISTRADOR, \Incidencias\App::$PERFIL_JEFEOPERACION]))
                                                            <?php $ipt = ($incidenciaParqueAutomotors != null && count($incidenciaParqueAutomotors) > 0) ?  $incidenciaParqueAutomotors->where('parque_automotor_id', $parque->id)->first() : null ?>
                                                            @if($m->id == Auth::user()->macro_id)
                                                                <td class="hidden"><input type="hidden" name="IncidenciaParqueAutomotor_userid[]" value="{{ $users->where('macro_id', $m->id)->first()->id  }}"></td>
                                                                <td class="hidden"><input type="hidden" name="IncidenciaParqueAutomotor_id[]" class="form-control" value="{{ $parque->id }}"></td>
                                                                <td><input type="text" name="IncidenciaParqueAutomotor_operativo[]" class="form-control change-parques numeric" value="{{ (($incidenciaParqueAutomotors != null && count($incidenciaParqueAutomotors) > 0)  && ($ipt != null && $ipt->operativo != 0)) ?  $ipt->operativo : "" }}"></td>
                                                                <td><input type="text" name="IncidenciaParqueAutomotor_inoperativo[]" class="form-control change-parques numeric"  value="{{ (($incidenciaParqueAutomotors != null && count($incidenciaParqueAutomotors) > 0) && ($ipt != null && $ipt->inoperativo != 0)) ?  $ipt->inoperativo : "" }}"></td>
                                                            @endif
                                                        @else
                                                            <?php $ipt = ($incidenciaParqueAutomotors != null && count($incidenciaParqueAutomotors) > 0) ? $incidenciaParqueAutomotors->where('parque_automotor_id', $parque->id)->where('users.macro_id', $m->id)->first() : null ?>
                                                            <td class="hidden"><input type="hidden" name="IncidenciaParqueAutomotor_userid[]" value="{{  $users->where('macro_id', $m->id)->first()->id  }}"></td>
                                                            <td class="hidden"><input type="hidden" name="IncidenciaParqueAutomotor_id[]" class="form-control" value="{{ $parque->id }}"></td>
                                                            <td><input type="text" name="IncidenciaParqueAutomotor_operativo[]" class="form-control change-parques numeric" value="{{ ( ($incidenciaParqueAutomotors != null && count($incidenciaParqueAutomotors) > 0) && ($ipt != null && $ipt->users->macro_id == $m->id && $ipt->operativo != 0) ) ?  $ipt->operativo : "" }}"></td>
                                                            <td><input type="text" name="IncidenciaParqueAutomotor_inoperativo[]" class="form-control change-parques numeric"  value="{{ (($incidenciaParqueAutomotors != null && count($incidenciaParqueAutomotors) > 0) && ($ipt != null && $ipt->users->macro_id == $m->id && $ipt->inoperativo != 0)) ?  $ipt->inoperativo : "" }}"></td>
                                                        @endif
                                                    @endforeach
                                                    <td class="not-hidden"><input type="text" name="IncidenciaParqueAutomotor_total[]" class="form-control change-parques-total numeric" readonly="readonly" ></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
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
    {{ Html::script('/auth/views/incidencia/index.js')  }}
    {{ Html::script('/auth/views/incidencia/indexMaintenance.js')  }}

@endsection
