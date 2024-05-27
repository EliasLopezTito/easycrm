<div class="modal fade" id="modalCamionetaRecorridoMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $CamionetaRecorrido != null ? "Editar" : "Registrar" }} Camioneta Recorrido</h4>
            </div>

            <form>
                <input type="hidden" id="id" name="id" value="{{ $CamionetaRecorrido != null ? $CamionetaRecorrido->id : 0 }}">
                <div class="modal-body">

                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="camioneta_id"> Camioneta: </label>
                            <select name="camioneta_id" id="camioneta_id" class="form-control" required>
                                <option value="">-- SELECCIONE --</option>
                                @foreach($Camionetas as $q)
                                    <option value="{{ $q->id }}" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->camioneta_id == $q->id ? "selected" : ""  }}>{{ $q->name  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="placa"> Placa: </label>
                            <input type="text" name="placa" id="placa" class="form-control" autocomplete="off" value="{{ $CamionetaRecorrido != null ? $CamionetaRecorrido->placa : "" }}" readonly="readonly">
                        </div>
                        <div class="col-sm-3">
                            <label for="vinculado"> Vinculado: </label>
                            <input type="text" name="vinculado" id="vinculado" class="form-control" autocomplete="off" value="{{ $CamionetaRecorrido != null ? $CamionetaRecorrido->vinculado : "" }}" readonly="readonly">
                        </div>
                        <div class="col-sm-2">
                            <label for="recorrido"> KM: </label>
                            <input type="text" name="recorrido" id="recorrido" class="form-control number" value="{{ $CamionetaRecorrido != null ? $CamionetaRecorrido->recorrido : "" }}"autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label for="mes"> Mes: </label>
                            <select name="mes" id="mes" class="form-control" required>
                                <option value="">-- SELECCIONE --</option>
                                <option value="1" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 1 ? "selected" : "" }}>ENERO</option>
                                <option value="2" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 2 ? "selected" : "" }}>FEBRERO</option>
                                <option value="3" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 3 ? "selected" : "" }}>MARZO</option>
                                <option value="4" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 4 ? "selected" : "" }}>ABRIL</option>
                                <option value="5" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 5 ? "selected" : "" }}>MAYO</option>
                                <option value="6" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 6 ? "selected" : "" }}>JUNIO</option>
                                <option value="7" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 7 ? "selected" : "" }}>JULIO</option>
                                <option value="8" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 8 ? "selected" : "" }}>AGOSTO</option>
                                <option value="9 {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 9 ? "selected" : "" }}">SEPTIEMBRE</option>
                                <option value="10" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 10 ? "selected" : "" }}>OCTUBRE</option>
                                <option value="11" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 11 ? "selected" : "" }}>NOVIEMBRE</option>
                                <option value="12" {{ $CamionetaRecorrido != null && $CamionetaRecorrido->mes == 12 ? "selected" : "" }}>DICIEMBRE</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="fecha_form"> Fecha: </label>
                            <input type="text" name="fecha_form" id="fecha_form" class="form-control"
                                   value="{{ $CamionetaRecorrido != null ? $CamionetaRecorrido->fecha : "" }}" required="required"  autocomplete="off" required >
                        </div>
                        <div class="col-sm-3">
                            <label for="hora"> Hora: </label>
                            <input type="text" name="hora" id="hora" class="form-control"
                                   value="{{ $CamionetaRecorrido != null ? $CamionetaRecorrido->hora : "" }}" required autocomplete="off" required >
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
<script type="text/javascript" src="/auth/views/camionetaRecorrido/_Maintenance.js"></script>

