
<div class="modal fade" id="modalIncidenciaRelevanteMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $IncidenciaRelevante != null ? "Editar" : "Registrar" }} Incidencia Relevante</h4>
            </div>

            <form>
                <input type="hidden" id="id" name="id" value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->id : 0 }}">
                <input type="hidden" id="user_id" name="user_id" value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->user_id : Auth::user()->id }}">
                <input type="hidden" id="perfil_id" name="perfil_id" value="{{ Auth::user()->perfil_id }}">
                @if(\Incidencias\App::$PERFIL_ADMINISTRADOR != Auth::user()->perfil_id)
                    <input type="hidden" id="fechaRelevante_hidden" name="fechaRelevante_hidden" value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->fecha : "" }}">
                    <input type="hidden" id="turno_id" name="turno_id" value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->turno_id : $TurnoOrganizacion->turno_id }}">
                @endif

                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="nro_incidencia"> N° Incidencia: </label>
                                    <input type="text" name="nro_incidencia" id="nro_incidencia" class="form-control number"
                                           value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->nro_incidencia : "" }}" required="required"  autocomplete="off" >
                                </div>
                                @if(\Incidencias\App::$PERFIL_ADMINISTRADOR == Auth::user()->perfil_id)
                                    <div class="col-sm-4">
                                        <label for="fecha_registro"> Fecha Registro: </label>
                                        <input type="text" name="fecha_registro" id="fecha_registro" class="form-control"
                                               value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->fecha_registro : "" }}" required="required"  autocomplete="off" >
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="turno_id"> Turno: </label>
                                        <select name="turno_id" id="turno_id" class="form-control" required="required" style="width:100% !important">
                                            <option value="">--SELECCIONE--</option>
                                            @foreach($Turnos as $q)
                                                <option value="{{ $q->id  }}" {{ $IncidenciaRelevante != null && $IncidenciaRelevante->turno_id == $q->id ? "selected" : ""  }}>{{ $q->name  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="trabajador_id"> Operador Camara: </label>
                                    <select name="trabajador_id" id="trabajador_id" class="form-control" required="required" style="width:100% !important">
                                        <option value="">--SELECCIONE--</option>
                                        @foreach($Trabajadors as $q)
                                            <option value="{{ $q->id  }}" {{ $IncidenciaRelevante != null && $IncidenciaRelevante->trabajador_id == $q->id ? "selected" : ""  }}>{{ $q->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="fechaRelevante"> Fecha: </label>
                                    <input type="text" name="fechaRelevante" id="fechaRelevante" class="form-control" {{ \Incidencias\App::$PERFIL_ADMINISTRADOR == Auth::user()->perfil_id ? "" : "disabled" }}
                                           value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->fecha : "" }}" required="required"  autocomplete="off" >
                                </div>
                                <div class="col-sm-2">
                                    <label for="hora"> Hora: </label>
                                    <input type="text" name="hora" id="hora" class="form-control"
                                           value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->hora : "" }}" required="required"  autocomplete="off" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for="lugar_incidencia_id"> Lugar de la Incidencia: </label>
                                    <select name="lugar_incidencia_id" id="lugar_incidencia_id" class="form-control" required="required" style="width:100% !important">
                                    <option value="">--SELECCIONE--</option>
                                    @foreach($LugarIncidencias as $q)
                                        <option value="{{ $q->id  }}"  {{ $IncidenciaRelevante != null && $IncidenciaRelevante->lugar_incidencia_id == $q->id ? "selected" : ""  }}>{{ $q->name  }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label for="nro_calle"> Nro: </label>
                                    <input type="text" name="nro_calle" id="nro_calle" class="form-control number"
                                           value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->nro_calle : "" }}" required="required" autocomplete="off" >
                                </div>
                                <div class="col-sm-5">
                                    <label for="categoria_id"> Categoría: </label>
                                    <select name="categoria_id" id="categoria_id" class="form-control" required="required" >
                                        <option value="">--SELECCIONE--</option>
                                        @foreach($Categorias as $q)
                                            <option value="{{ $q->id  }}" {{ $IncidenciaRelevante != null && $IncidenciaRelevante->categoria_id == $q->id ? "selected" : ""  }} >{{ $q->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="latitud"> Latitud: </label>
                                    <input type="text" name="latitud" id="latitud" class="form-control"
                                           value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->latitud : "" }}" required="required" autocomplete="off" >
                                </div>
                                <div class="col-sm-6">
                                    <label for="longitud"> Longitud: </label>
                                    <input type="text" name="longitud" id="longitud" class="form-control"
                                           value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->longitud : "" }}" required="required" autocomplete="off" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="clasificacionIncidencia_id"> Clasificación Incidencia: </label>
                                    <select name="clasificacionIncidencia_id" id="clasificacionIncidencia_id" class="form-control" required="required" {{ $IncidenciaRelevante != null ? "" : "disabled" }}  >
                                        <option value="">--SELECCIONE--</option>
                                        @foreach($ClasificacionIncidencias as $q)
                                            @if($IncidenciaRelevante != null && $IncidenciaRelevante->clasificacionIncidencia_id == $q->id)
                                                <option value="{{ $q->id  }}" selected>{{ $q->name  }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="modalidadIncidencia_id"> Modalidad Incidencia: </label>
                                    <select name="modalidadIncidencia_id" id="modalidadIncidencia_id" class="form-control" required="required"  {{ $IncidenciaRelevante != null ? "" : "disabled" }}  >
                                        <option value="">--SELECCIONE--</option>
                                        @foreach($ModalidadIncidencias as $q)
                                            @if($IncidenciaRelevante != null && $IncidenciaRelevante->modalidadIncidencia_id == $q->id)
                                                <option value="{{ $q->id  }}" selected>{{ $q->name  }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="vehiculo_id"> Vehículo Utilizado: </label>
                                    <select name="vehiculo_id" id="vehiculo_id" class="form-control" required="required" >
                                        <option value="">--SELECCIONE--</option>
                                        @foreach($Vehiculos as $q)
                                            <option value="{{ $q->id  }}" {{ $IncidenciaRelevante != null && $IncidenciaRelevante->vehiculo_id == $q->id ? "selected" : ""  }}>{{ $q->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="arma_id"> Arma Utilizada: </label>
                                    <select name="arma_id" id="arma_id" class="form-control" required="required"  >
                                        <option value="">--SELECCIONE--</option>
                                        @foreach($Armas as $q)
                                            <option value="{{ $q->id  }}" {{ $IncidenciaRelevante != null && $IncidenciaRelevante->arma_id == $q->id ? "selected" : ""  }}>{{ $q->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="objeto"> Objeto Robado: </label>
                                    <input type="text" id="objeto" name="objeto" class="form-control" value="{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->objeto : "" }}" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="macro_id"> MacroSector: </label>
                                    <select name="macro_id" id="macro_id" class="form-control" required="required" >
                                        <option value="">--SELECCIONE--</option>
                                        @foreach($MacroSectors as $q)
                                            <option value="{{ $q->id  }}" {{ $IncidenciaRelevante != null && $IncidenciaRelevante->macro_id == $q->id ? "selected" : ""  }}>{{ $q->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="sector_id"> Sector: </label>
                                    <select name="sector_id" id="sector_id" class="form-control" required="required" {{ $IncidenciaRelevante != null ? "" : "disabled" }}>
                                        <option value="">--SELECCIONE--</option>
                                        @foreach($Sectors as $q)
                                            @if($IncidenciaRelevante != null && $IncidenciaRelevante->sector_id == $q->id)
                                                <option value="{{ $q->id  }}" selected>{{ $q->name  }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="subsector_id"> SubSector: </label>
                                    <select name="subsector_id" id="subsector_id" class="form-control" required="required" {{ $IncidenciaRelevante != null ? "" : "disabled" }}>
                                        <option value="">--SELECCIONE--</option>
                                        @foreach($SubSectors as $q)
                                            @if($IncidenciaRelevante != null && $IncidenciaRelevante->subsector_id == $q->id)
                                                <option value="{{ $q->id  }}" selected>{{ $q->name  }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label for="descripcion_incidencia"> Descripción de la Incidencia: </label>
                                    <textarea name="descripcion_incidencia" id="descripcion_incidencia" maxlength="250" class="form-control text-uppercase" rows="2" required="required" >{{ $IncidenciaRelevante != null ? $IncidenciaRelevante->descripcion_incidencia : "" }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-center">
                                <b class="text-uppercase">Imagen Referencial 1</b>
                                <div class="img-content image1 incidencia">
                                    <img src="{{ ($IncidenciaRelevante != null && $IncidenciaRelevante->imagesOne != null) ?  "/uploads/cecoms/".$IncidenciaRelevante->imagesOne->name :
                                     "/auth/layout/img/default.gif" }}" class="img-responsive" alt="Upload Image">
                                </div>
                                <input type="file" class="styled form-control" id="image1" accept="image/jpeg, image/png" >
                            </div>
                            <div class="mt-15 text-center">
                                <b class="text-uppercase">Imagen Referencial 2</b>
                                <div class="img-content image2 incidencia">
                                    <img src="{{ ($IncidenciaRelevante != null && $IncidenciaRelevante->imagesTwo != null)  ?  "/uploads/cecoms/".$IncidenciaRelevante->imagesTwo->name :
                                     "/auth/layout/img/default.gif" }}" class="img-responsive" alt="Upload Image">
                                </div>
                                <input type="file" class="styled form-control" id="image2" accept="image/jpeg, image/png">
                            </div>
                            <div class="mt-15 text-center">
                                <b>Imagen Referencial 3</b>
                                <div class="img-content image3 incidencia">
                                    <img src="{{ ($IncidenciaRelevante != null && $IncidenciaRelevante->imagesThree != null) ?  "/uploads/cecoms/".$IncidenciaRelevante->imagesThree->name :
                                     "/auth/layout/img/default.gif" }}" class="img-responsive" alt="Upload Image">
                                </div>
                                <input type="file" class="styled form-control" id="image3" accept="image/jpeg, image/png" >
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

{{ Html::script('/auth/plugins/select2/js/select2.js') }}
{{ Html::script('/auth/plugins/maxlength/bootstrap-maxlength.min.js') }}
<!-- MaintenenceJs -->
<script type="text/javascript" src="/auth/views/incidenciaRelevante/_Maintenance.js"></script>

