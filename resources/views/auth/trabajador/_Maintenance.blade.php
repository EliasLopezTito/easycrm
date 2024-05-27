<div class="modal fade" id="modalTrabajadorMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $trabajador != null ? "Editar" : "Registrar" }} Trabajador</h4>
            </div>

            <form enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="image"> Imagen: </label>
                            <div class="img-content user">
                                <img src="{{ $trabajador != null ?  "/uploads/trabajadores/".$trabajador->images->name :
                                 "/auth/layout/img/upload-image.PNG" }}" class="img-responsive" alt="Upload Image">
                            </div>
                            <input type="file" class="styled form-control" id="image" accept="image/jpeg, image/png">
                            <input type="hidden" id="id" name="id" value="{{ $trabajador != null ? $trabajador->id : 0 }}">
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label for="name"> Apellidos y Nombres: </label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="{{ $trabajador != null ? $trabajador->name : "" }}" required autocomplete="off">
                                </div>
                                <div class="col-sm-4">
                                    <label for="dni"> DNI: </label>
                                    <input type="text" name="dni" id="dni" value="{{ $trabajador != null ? $trabajador->dni : "" }}"  minlength="5" class="form-control" autocomplete="off">
                                </div>
                                <div class="col-sm-8">
                                    <label for="personalCargo_id"> Cargo: </label>
                                    <select name="personalCargo_id" id="personalCargo_id" class="form-control" required>
                                        <option value=""> -- Seleccionne -- </option>
                                        @foreach($personalCargos as $c)
                                            <option value="{{ $c->id }}" {{ $trabajador != null && $trabajador->personalCargo_id == $c->id ? "selected" : ""  }}>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="macro_id"> Macro: </label>
                                    <select name="macro_id" id="macro_id" class="form-control" required>
                                        <option value=""> -- Seleccionne -- </option>
                                        @foreach($macros as $m)
                                            <option value="{{ $m->id }}" {{ $trabajador != null && $trabajador->macro_id == $m->id ? "selected" : ""  }}>
                                                {{ $m->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="grupo_id"> Grupo: </label>
                                    <select name="grupo_id" id="grupo_id" class="form-control" required>
                                        <option value=""> -- Seleccionne -- </option>
                                        @foreach($grupos as $g)
                                            <option value="{{ $g->id }}" {{ $trabajador != null && $trabajador->grupo_id == $g->id ? "selected" : ""  }}>
                                                {{ $g->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 p-0">
                            <div class="turno-div hidden">
                                <div class="col-sm-6">
                                    <label for="turno_id"> Turno: </label>
                                    <select name="turno_id" id="turno_id" class="form-control" required>
                                        <option value=""> -- Seleccionne -- </option>
                                        @foreach($turnos as $t)
                                            <option value="{{ $t->id }}" {{ $trabajador != null && $trabajador->turno_id == $t->id ? "selected" : ""  }}>
                                                {{ $t->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="turno_suplente_id"> Turno Suplente: </label>
                                    <select name="turno_suplente_id" id="turno_suplente_id" class="form-control" required>
                                        <option value=""> -- Seleccionne -- </option>
                                        @foreach($turnos as $t)
                                            <option value="{{ $t->id }}" {{ $trabajador != null && $trabajador->turno_suplente_id == $t->id ? "selected" : ""  }}>
                                                {{ $t->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="contrato_id"> Contrato: </label>
                                <select name="contrato_id" id="contrato_id" class="form-control" required>
                                    <option value=""> -- Seleccionne -- </option>
                                    @foreach($contratos as $c)
                                        <option value="{{ $c->id }}" {{ $trabajador != null && $trabajador->contrato_id == $c->id ? "selected" : ""  }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="telefono"> Teléfono: </label>
                                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ $trabajador != null ? $trabajador->telefono : "" }}" autocomplete="off">
                            </div>
                            <div class="col-sm-6">
                                <label for="direccion"> Dirección: </label>
                                <input type="text" name="direccion" id="direccion" class="form-control" value="{{ $trabajador != null ? $trabajador->direccion : "" }}" autocomplete="off">
                            </div>
                            <div class="col-sm-6">
                                <label for="referencia"> Referencia: </label>
                                <input type="text" name="referencia" id="referencia" class="form-control" value="{{ $trabajador != null ? $trabajador->referencia : "" }}" autocomplete="off">
                            </div>
                            <div class="col-sm-6">
                                <label for="persona1"> Persona 1: </label>
                                <input type="text" name="persona1" id="persona1" class="form-control" value="{{ $trabajador != null ? $trabajador->persona1 : "" }}" autocomplete="off">
                            </div>
                            <div class="col-sm-6">
                                <label for="telefono1"> telefono: </label>
                                <input type="text" name="telefono1" id="telefono1" class="form-control" value="{{ $trabajador != null ? $trabajador->telefono1 : "" }}" autocomplete="off">
                            </div>
                            <div class="col-sm-6">
                                <label for="persona2"> Persona 2: </label>
                                <input type="text" name="persona2" id="persona2" class="form-control" value="{{ $trabajador != null ? $trabajador->persona2 : "" }}" autocomplete="off">
                            </div>
                            <div class="col-sm-6">
                                <label for="telefono2"> telefono: </label>
                                <input type="text" name="telefono2" id="telefono2" class="form-control" value="{{ $trabajador != null ? $trabajador->telefono2 : "" }}" autocomplete="off">
                            </div>
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
{{ Html::script('/auth/views/trabajador/_Maintenance.js') }}
