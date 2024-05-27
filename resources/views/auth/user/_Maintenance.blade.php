<div class="modal fade" id="modalUserMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $user != null ? "Editar" : "Registrar" }} Usuario</h4>
            </div>

            <form enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="image"> Imagen: </label>
                            <div class="img-content user">
                                <img src="{{ $user != null ?  "/uploads/users/".$user->images->name :
                                 "/auth/layout/img/upload-image.PNG" }}" class="img-responsive" alt="Upload Image">
                            </div>
                            <input type="file" class="styled form-control" id="image" accept="image/jpeg, image/png" {{ $user != null ? "" : "required" }}>
                            <input type="hidden" id="id" name="id" value="{{ $user != null ? $user->id : 0 }}">
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label for="name"> Apellidos y Nombres: </label>
                                    <div class="input-group">
                                        <input type="text" name="name" id="name" class="form-control" value="{{ $user != null ? $user->name : "" }}" readonly="readonly" required >
                                        <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" id="btnBuscarTrabajador">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                                <div class="col-sm-5 switch-field">
                                    <div class="switch-title">¿Tiene Suplente?</div>
                                    <input id="siUser_alternative" type="radio" name="user_alternative" value="1" {{ ($user != null && $user->user_alternative) ? "checked" : ""  }}>
                                    <label for="siUser_alternative">Si</label>
                                    <input id="NoUser_alternative" type="radio" name="user_alternative" value="0" {{ $user == null || ($user != null && !$user->user_alternative) ? "checked" : ""  }} >
                                    <label for="NoUser_alternative">No</label>
                                </div>
                                <div class="col-sm-7 user_name_alternative hidden">
                                    <label for="user_name_alternative"> Suplente: </label>
                                    <div class="input-group">
                                        <input type="text" name="user_name_alternative" id="user_name_alternative" class="form-control" value="{{ $user != null ? $user->user_name_alternative : "" }}" readonly="readonly" required >
                                        <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" id="btnBuscarTrabajadorSuplente">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label for="email"> E-Mail: </label>
                                    <input type="email" name="email" id="email" value="{{ $user != null ? $user->email : "" }}"  class="form-control" readonly="readonly">
                                </div>
                                <div class="col-sm-5">
                                    <label for="password"> Contraseña: </label>
                                    <input type="password" name="password" id="password" class="form-control" {{ $user != null ? "" : "required" }}>
                                </div>
                                <div class="col-sm-7">
                                    <label for="perfil"> Perfil: </label>
                                    <input type="text" name="perfil" id="perfil" class="form-control" value="{{ $user != null ? $user->perfils->name : "NINGUNO" }}" readonly="readonly">
                                </div>
                                <div class="col-sm-6">
                                    <label for="macro"> Macro: </label>
                                    <input type="text" name="macro" id="macro" class="form-control" value="{{ $user != null ? $user->macros->name : "NINGUNO" }}" readonly="readonly">
                                </div>
                                <div class="col-sm-6">
                                    <label for="grupo"> Grupo: </label>
                                    <input type="text" name="grupo" id="grupo" class="form-control" value="{{ $user != null ? $user->grupos->name : "NINGUNO" }}" readonly="readonly">
                                </div>
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

{{ Html::script('/auth/plugins/helperSeleccion/helper-seleccion.js') }}
<!-- MaintenenceJs -->
{{ Html::script('/auth/views/user/_Maintenance.js') }}
