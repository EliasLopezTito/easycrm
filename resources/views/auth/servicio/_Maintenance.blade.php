<div class="modal fade" id="modalServicioMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $Servicio != null ? "Editar" : "Registrar" }} Servicio</h4>
            </div>

            <form>
                <input type="hidden" id="id" name="id" value="{{ $Servicio != null ? $Servicio->id : 0 }}">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="name"> Nombre: </label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ $Servicio != null ? $Servicio->name : "" }}" required autocomplete="off" >
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
<script type="text/javascript" src="/auth/views/servicio/_Maintenance.js"></script>

