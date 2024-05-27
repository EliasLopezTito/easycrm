<div class="modal fade" id="modalModalidadIncidenciaMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $ModalidadIncidencia != null ? "Editar" : "Registrar" }} Modalidad Incidencia</h4>
            </div>

            <form>
                <input type="hidden" id="id" name="id" value="{{ $ModalidadIncidencia != null ? $ModalidadIncidencia->id : 0 }}">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="image"> Icono: </label>
                            <div class="img-content-icon">
                                <img src="{{ $ModalidadIncidencia != null ?  "/uploads/modalidadIncidencias/".$ModalidadIncidencia->images->name :
                                 "/auth/layout/img/icon_default.PNG" }}" class="img-responsive" alt="Upload Image">
                            </div>
                            <input type="file" class="styled form-control" id="image" accept="image/jpeg, image/png">
                            <input type="hidden" id="id" name="id" value="{{ $ModalidadIncidencia != null ? $ModalidadIncidencia->id : 0 }}">
                        </div>
                        <div class="col-sm-12">
                            <label for="clasificacionIncidencia_id"> Clasificación: </label>
                            <select name="clasificacionIncidencia_id" id="clasificacionIncidencia_id" class="form-control" required>
                                <option value=""> -- Seleccionne -- </option>
                                @foreach($clasificacionIncidencias as $c)
                                    <option value="{{ $c->id }}" {{ $ModalidadIncidencia != null && $ModalidadIncidencia->clasificacionIncidencia_id == $c->id ? "selected" : ""  }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="name"> Nombre: </label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ $ModalidadIncidencia != null ? $ModalidadIncidencia->name : "" }}" required autocomplete="off" >
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
<script type="text/javascript" src="/auth/views/modalidadIncidencia/_Maintenance.js"></script>

