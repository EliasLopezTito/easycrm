<div class="modal fade" id="modalPatrullajeIntegradoMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $PatrullajeIntegrado != null ? "Editar" : "Registrar" }} Patrullaje Integrado</h4>
            </div>

            <form>
                <input type="hidden" id="id" name="id" value="{{ $PatrullajeIntegrado != null ? $PatrullajeIntegrado->id : 0 }}">
                <input type="hidden" id="user_id" name="user_id" value="{{ $PatrullajeIntegrado != null ? $PatrullajeIntegrado->user_id : Auth::user()->id }}">
                <input type="hidden" id="perfil_actual_id" name="perfil_actual_id" value="{{ Auth::user()->perfil_id  }}">
                <input type="hidden" id="turno_id" name="turno_id" value="{{ $PatrullajeIntegrado != null ? $PatrullajeIntegrado->turno_id : $TurnoOrganizacion->turno_id }}">

                <div class="modal-body">
                    @if(\Incidencias\App::$PERFIL_ADMINISTRADOR == Auth::user()->perfil_id)
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="fecha_registro"> Fecha Registro: </label>
                            <input type="text" name="fecha_registro" id="fecha_registro" class="form-control"
                                   value="{{ $PatrullajeIntegrado != null ? $PatrullajeIntegrado->fecha_registro : "" }}" required="required"  autocomplete="off" >
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <label for="dia"> Día: </label>
                            <input type="text" name="dia" id="dia" class="form-control number" readonly="readonly"
                                   value="{{ $PatrullajeIntegrado != null ? $PatrullajeIntegrado->dia : "" }}" required
                                   {{ \Incidencias\App::$PERFIL_ADMINISTRADOR == Auth::user()->perfil_id ? "" : "readonly"  }}
                                   autocomplete="off" >
                        </div>
                        <div class="col-sm-3">
                            <label for="hora_inicio"> Hora Inicio: </label>
                            <input type="text" name="hora_inicio" id="hora_inicio" class="form-control"
                                   value="{{ $PatrullajeIntegrado != null ? $PatrullajeIntegrado->hora_inicio : "" }}" required autocomplete="off" >
                        </div>
                        <div class="col-sm-3">
                            <label for="hora_final"> Hora Final: </label>
                            <input type="text" name="hora_final" id="hora_final" class="form-control"
                                   value="{{ $PatrullajeIntegrado != null ? $PatrullajeIntegrado->hora_final : "" }}" required autocomplete="off" >
                        </div>
                        <div class="col-sm-4">
                            <label for="camioneta_id"> Camioneta: </label>
                            <select name="camioneta_id" id="camioneta_id" class="form-control" required>
                                <option value="">-- SELECCIONE --</option>
                                @foreach($Camionetas as $q)
                                    <option value="{{ $q->id }}" {{ $PatrullajeIntegrado != null && $PatrullajeIntegrado->camioneta_id == $q->id ? "selected" : ""  }}>{{ $q->name  }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="placa"> Placa: </label>
                            <input type="text" name="placa" id="placa" class="form-control" required="required"
                                   value="{{ $PatrullajeIntegrado != null ? $PatrullajeIntegrado->placa : "" }}"  readonly autocomplete="off" >
                        </div>
                        <div class="col-sm-9">
                            <label for="sector_id"> Zonas: </label>
                            <select name="sector_id[]" id="sector_id" style="width:100% !important" class="form-control" data-initial="{{ ($PatrullajeIntegradoSector != null && count($PatrullajeIntegradoSector) > 0 ) ?  implode(",", $PatrullajeIntegradoSector)  : "" }}" required multiple="multiple">
                                @foreach($Sectors as $q)
                                    <option value="{{ $q->id }}">{{ $q->name  }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="trabajador_id"> Sereno Chofer: </label>
                            <select name="trabajador_id" id="trabajador_id" style="width:100% !important" class="form-control" required>
                                <option value="">-- SELECCIONE --</option>
                                @foreach($Trabajadors as $q)
                                    <option value="{{ $q->id }}" {{ $PatrullajeIntegrado != null && $PatrullajeIntegrado->trabajador_id == $q->id ? "selected" : ""  }}>{{ $q->name  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="efectivo_policial"> Efectivo policial: </label>
                            <input type="text" name="efectivo_policial" id="efectivo_policial" class="form-control"
                                   value="{{ $PatrullajeIntegrado != null ? $PatrullajeIntegrado->efectivo_policial : "" }}" required autocomplete="off" >
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>

{{ Html::script('/auth/plugins/select2/js/select2.js') }}
<!-- MaintenenceJs -->
<script type="text/javascript" src="/auth/views/patrullajeIntegrado/_Maintenance.js"></script>

