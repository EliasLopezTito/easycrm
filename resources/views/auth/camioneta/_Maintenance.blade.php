<div class="modal fade" id="modalCamionetaMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $Camioneta != null ? "Editar" : "Registrar" }} Camioneta</h4>
            </div>

            <form>
                <input type="hidden" id="id" name="id" value="{{ $Camioneta != null ? $Camioneta->id : 0 }}">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="marca"> Marca/Modelo: </label>
                            <input type="text" name="marca" id="marca" class="form-control"
                                   value="{{ $Camioneta != null ? $Camioneta->marca : "" }}" required autocomplete="off" >
                        </div>
                        <div class="col-sm-6">
                            <label for="placa"> Placa: </label>
                            <input type="text" name="placa" id="placa" class="form-control" maxlength="10"
                                   value="{{ $Camioneta != null ? $Camioneta->placa : "" }}" required autocomplete="off" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="numeroCamioneta"> N° Camioneta: </label>
                            <input type="text" name="numeroCamioneta" id="numeroCamioneta" class="form-control" maxlength="10"
                                   value="{{ $Camioneta != null ? $Camioneta->numeroCamioneta : "" }}" required autocomplete="off" >
                        </div>
                        <div class="col-sm-6">
                            <label for="anio"> Año: </label>
                            <input type="text" name="anio" id="anio" class="form-control number"  minlength="4" maxlength="4"
                                   value="{{ $Camioneta != null ? $Camioneta->anio : "" }}" required autocomplete="off" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="name"> Nombre: </label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ $Camioneta != null ? $Camioneta->name : "" }}" required autocomplete="off" >
                        </div>
                        <div class="col-sm-6 switch-field">
                            <div class="switch-title">Vinculado</div>
                            <input id="siVinculado" type="radio" name="vinculado" value="1" {{ $Camioneta == null || ($Camioneta != null && $Camioneta->vinculado) ? "checked" : ""  }}>
                            <label for="siVinculado">SI</label>
                            <input id="NoVinculado" type="radio" name="vinculado" value="0" {{ ($Camioneta != null && !$Camioneta->vinculado) ? "checked" : ""  }} >
                            <label for="NoVinculado">NO</label>
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

<!-- MaintenenceJs -->
<script type="text/javascript" src="/auth/views/camioneta/_Maintenance.js"></script>

