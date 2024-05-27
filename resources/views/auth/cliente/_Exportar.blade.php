<div id="modalExportarCliente" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="width: 380px">
            <div class="modal-header">
                <h5 class="modal-title">Exportar Leads</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" name="export_fecha_inicio" id="export_fecha_inicio">
                <input type="hidden" name="export_fecha_final" id="export_fecha_final">
                <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Fecha</label>
                            <div id="export_reportrange" class="text-capitalize" style="">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-angle-down"></i>
                            </div>
                        </div>
                    </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="export_estado_id">Estado</label>
                        <select name="export_estado_id" class="form-input"  id="export_estado_id">
                            <option value="">-- Todos --</option>
                            @foreach($Estados as $q)
                                <option value="{{ $q->id }}"> {{ $q->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if(in_array(\Illuminate\Support\Facades\Auth::guard('web')->user()->profile_id, [\easyCRM\App::$PERFIL_ADMINISTRADOR, \easyCRM\App::$PERFIL_CAJERO]))
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="export_vendedor_id">Vendedor</label>
                        <select name="export_vendedor_id" class="form-input"  id="export_vendedor_id">
                            <option value="">-- Todos --</option>
                            @foreach($Vendedores as $q)
                                <option value="{{ $q->id }}"> {{ $q->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="export_modalidad_id">Modalidad</label>
                        <select name="export_modalidad_id" class="form-input"  id="export_modalidad_id">
                            <option value="">-- Todos --</option>
                            @foreach($Modalidades as $q)
                                <option value="{{ $q->id }}"> {{ $q->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="export_carrera_id">Carrera / Curso</label>
                        <select name="export_carrera_id" class="form-input"  id="export_carrera_id">
                            <option value="">-- Todos --</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="export_turno_id">Turno</label>
                        <select name="export_turno_id" class="form-input"  id="export_turno_id">
                            <option value="">-- Todos --</option>
                            @foreach($Turnos as $q)
                                <option value="{{ $q->id }}"> {{ $q->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-bold btn-pure btn-primary">Exportar Excel</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/auth/js/cliente/_Exportar.min.js"></script>
