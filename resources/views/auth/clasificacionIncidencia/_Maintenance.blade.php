<div class="modal fade" id="modalClasificacionIncidenciaMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $ClasificacionIncidencia != null ? "Editar" : "Registrar" }} Categoría</h4>
            </div>

            <form>
                <input type="hidden" id="id" name="id" value="{{ $ClasificacionIncidencia != null ? $ClasificacionIncidencia->id : 0 }}">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="categoria_id"> Categoría: </label>
                            <select name="categoria_id" id="categoria_id" class="form-control" required>
                                <option value=""> -- Seleccionne -- </option>
                                @foreach($categorias as $c)
                                    <option value="{{ $c->id }}" {{ $ClasificacionIncidencia != null && $ClasificacionIncidencia->categoria_id == $c->id ? "selected" : ""  }}>
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
                                   value="{{ $ClasificacionIncidencia != null ? $ClasificacionIncidencia->name : "" }}" required autocomplete="off" >
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
<script type="text/javascript" src="/auth/views/clasificacionIncidencia/_Maintenance.js"></script>

