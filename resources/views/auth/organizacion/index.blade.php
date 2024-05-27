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
        <h1> Organización Servicio <small> Control </small></h1>
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
                                    <div class="col-sm-3">
                                        <div class="mt-25">
                                            <button type="button" class="btn btn-danger col-sm-12" id="btnEditar">
                                                <span class="fa fa-pencil"></span>  EDITAR
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-15 ">
                                    <div class="col-md-6 col-sm-12">
                                        <p class="text-uppercase title">DISTRIBUCIÓN DEL PERSONAL</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12 p-0">
                                        @if(Auth::user()->perfil_id == \Incidencias\App::$PERFIL_ADMINISTRADOR)
                                            <button type="submit" class="btn btn-success pull-right col-sm-3" id="btnActualizar">
                                                <span class="fa fa-save"></span>  ACTUALIZAR
                                            </button>
                                        @elseif(in_array(Auth::user()->perfil_id ,[\Incidencias\App::$PERFIL_SUPERVISORCECOM1, \Incidencias\App::$PERFIL_SUPERVISORCECOM2,  \Incidencias\App::$PERFIL_SUPERVISORCECOM3]))
                                            <button type="submit" class="btn btn-success pull-right col-sm-3 {{ in_array($organizacion->estado_id, [\Incidencias\App::$ESTADO_ENVIADA , \Incidencias\App::$ESTADO_FINALIZADO] ) ? "hidden" : ""  }}" id="btnCerrarTurno">
                                                <span class="fa fa-close"></span>  CERRAR TURNO
                                            </button>
                                            <button type="submit" class="btn btn-primary pull-right col-sm-3" id="btnGuardar">
                                                <span class="fa fa-save"></span>  GUARDAR
                                            </button>
                                            <button type="submit" class="btn btn-success pull-right col-sm-3 {{ in_array($organizacion->estado_id, [\Incidencias\App::$ESTADO_ENVIADA , \Incidencias\App::$ESTADO_FINALIZADO] ) ? "" : "hidden"  }}" id="btnActualizar">
                                                <span class="fa fa-save"></span>  ACTUALIZAR
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-15">
                                <div class="col-sm-12 table-responsive">
                                    <table id="tablaOrganizacion" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                        <tr>
                                            <th>TURNO</th>
                                            @foreach($servicios as $servicio)
                                                <th>{{ $servicio->name  }}</th>
                                            @endforeach
                                            <th>TOTAL</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $organizacion->turnos->name }}</td>
                                                @foreach($servicios as $servicio)
                                                    <td>
                                                        <input type="hidden" name="OrganizacionService_Serviceid[]" value="{{ $servicio->id }}" class="form-control">
                                                        <input type="text" name="OrganizacionService_Cantidad[]" class="form-control change-services numeric" readonly="readonly"
                                                        value="{{ ($organizacionServicios->where('servicio_id', $servicio->id)->first() != null &&
                                                         $organizacionServicios->where('servicio_id', $servicio->id)->first()->cantidad != 0 ) ? $organizacionServicios->where('servicio_id', $servicio->id)->first()->cantidad : ""}}">
                                                    </td>
                                                @endforeach
                                                <td><input type="text" class="form-control change-services-total numeric" readonly="readonly"></td>
                                            </tr>
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
                                        <div class="col-sm-12 mt-25">
                                            <button id="btnExportPdf" type="button" class="btn btn-danger" style="width: 100%"><i class="fa fa-file-pdf-o"></i> Exportar PDF</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="mt-15">
                                        <div class="col-sm-12 col-md-6 p-0">
                                            <p class="text-uppercase title">PERSONAL POR SECTOR</p>
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-sm-12 table-responsive">
                                            <table id="tablaOrganizacionSector" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                <thead>
                                                    <tr>
                                                        @foreach($sectores as $sector)
                                                            <th>{{ $sector->name }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        @foreach($sectores as $sector)
                                                            <td>
                                                            <input type="hidden" name="OrganizacionSector_Sectorid[]" value="{{ $sector->id }}" class="form-control">
                                                            <input type="text" name="OrganizacionSector_Cantidad[]" class="form-control change-sectors numeric" value="{{ ($organizacionSectors->where('sector_id', $sector->id)->first() != null &&
                                                             $organizacionSectors->where('sector_id', $sector->id)->first()->cantidad != 0 ) ? $organizacionSectors->where('sector_id', $sector->id)->first()->cantidad : ""}}">
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="mt-15">
                                        <div class="col-sm-12 col-md-6 p-0">
                                            <p class="text-uppercase title">PERSONAL POR SERVICIO</p>
                                        </div>
                                        <div class="col-sm-12 col-md-6 p-0">
                                            <button id="btnAgregarPersonalServicio" type="button" class="btn btn-success pull-right mb-15"> <i class="fa fa-plus"></i></button>
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
                                                    <th></th>
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
                                                            <td class="text-center"><button type="button" class="btn btn-danger btn-xs btn-delete mt-5" data-toggle="tooltip"><i class="fa fa-trash"></i></button></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                    <div class="mt-15">
                                        <div class="col-sm-12 col-md-6 p-0">
                                            <p class="text-uppercase title">PERSONAL AUSENTE</p>
                                        </div>
                                        <div class="col-sm-12 col-md-6 p-0">
                                            <button id="btnAgregarPersonalMotivo" type="button" class="btn btn-success pull-right mb-15"> <i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <table id="tablaOrganizacionPersonalMotivo" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th class="hidden"></th>
                                                <th>NOMBRES</th>
                                                <th>MOTIVO</th>
                                                <th>SERVICIO</th>
                                                <th>RÉGIMEN LABORAL</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        @if($organizacionPersonals != null && count($organizacionPersonals) > 0)
                                        <tbody>
                                            @foreach($organizacionPersonals as $op)
                                            <tr>
                                                <td class="hidden"><input type="hidden" class="form-control" value="{{ $op->id  }}"> </td>
                                                <td><select class="form-control"><option value="{{ $op->trabajador_id }}">{{ $op->trabajadores->name  }}</option></select></td>
                                                <td><select class="form-control"><option value="{{ $op->motivo_personal_id }}">{{ $op->motivosPersonal->name  }}</option></select></td>
                                                <td><select class="form-control"><option value="{{ $op->servicio_id }}">{{ $op->servicios->name  }}</option></select></td>
                                                <td><input type="text" value="{{ $op->regimen  }}" class="form-control" readonly="readonly"></td>
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
    {{ Html::script('/auth/views/organizacion/index.js')  }}

    {{ Html::script('/auth/views/organizacion/indexMaintenance.js')  }}

@endsection
