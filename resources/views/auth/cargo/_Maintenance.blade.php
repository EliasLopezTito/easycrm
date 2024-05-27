<div class="modal fade" id="modalCargoMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $Cargo != null ? "Editar" : "Registrar" }} Cargo</h4>
            </div>

            <form>
                <input type="hidden" id="id" name="id" value="{{ $Cargo != null ? $Cargo->id : 0 }}">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="name"> Nombre: </label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ $Cargo != null ? $Cargo->name : "" }}" required autocomplete="off" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 switch-field">
                            <div class="switch-title">Visible</div>
                            <input id="siVisible" type="radio" name="visible" value="1" {{ $Cargo == null || ($Cargo != null && $Cargo->visible) ? "checked" : ""  }}>
                            <label for="siVisible">Si</label>
                            <input id="NoVisible" type="radio" name="visible" value="0" {{ $Cargo != null && !$Cargo->visible ? "checked" : ""  }} >
                            <label for="NoVisible">No</label>
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
<script type="text/javascript" src="/auth/views/cargo/_Maintenance.js"></script>

