<div id="modalMantenimientoUsuario" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog">
        <form enctype="multipart/form-data" action="{{ route('user.user.store') }}" id="registroUsuario" method="POST"
              data-ajax="true" data-close-modal="true" data-ajax-loading="#loading" data-ajax-success="OnSuccessRegistroUsuario" data-ajax-failure="OnFailureRegistroUsuario">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $Usuario != null ? "Modificar" : " Registrar" }} Usuario</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ $Usuario != null ? $Usuario->id : 0 }}">
                <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name">Nombres</label>
                                <input type="text" class="form-input" name="name" value="{{ $Usuario != null ? $Usuario->name : "" }}" id="name" required>
                                <span data-valmsg-for="name"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name">Apellidos</label>
                                <input type="text" class="form-input" name="last_name" value="{{ $Usuario != null ? $Usuario->last_name : "" }}" id="last_name" required>
                                <span data-valmsg-for="last_name"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-input" name="email" value="{{ $Usuario != null ? $Usuario->email : "" }}" id="email" required
                                    {{ $Usuario != null ? "disabled" : "" }}>
                                <span data-valmsg-for="email"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-input" name="password" id="password" {{ $Usuario != null ? "" : " required" }}>
                                <span data-valmsg-for="password"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="profile_id">Perfil</label>
                                <select name="profile_id" class="form-input"  id="profile_id" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($Profiles as $q)
                                        <option value="{{ $q->id }}" {{ $Usuario != null && $Usuario->profile_id == $q->id ? "selected" : "" }}>{{ $q->name }}</option>
                                    @endforeach
                                </select>
                                <span data-valmsg-for="profile_id"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="turno_id">Turno</label>
                                <select name="turno_id" class="form-input"  id="turno_id" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($Turnos as $q)
                                        <option value="{{ $q->id }}" {{ $Usuario != null && $Usuario->turno_id == $q->id ? "selected" : "" }}>{{ $q->name }}</option>
                                    @endforeach
                                </select>
                                <span data-valmsg-for="turno_id"></span>
                            </div>
                            <div class="col-sm-4 switch-field">
                                <div class="switch-title">Recibe Lead</div>
                                <input type="radio" name="recibe_lead" value="1" id="recibe" {{ $Usuario == null || $Usuario->recibe_lead == 1 ? "checked": "" }}>
                                <label for="recibe">Si</label>
                                <input type="radio" name="recibe_lead" value="0" id="norecibe" {{ $Usuario != null && $Usuario->recibe_lead == 0 ? "checked": "" }}>
                                <label for="norecibe">No</label>
                            </div>
                            <div class="col-sm-4 switch-field">
                                <div class="switch-title">Estado</div>
                                <input type="radio" name="activo" value="1" id="activo" {{ $Usuario == null || $Usuario->activo == 1 ? "checked": "" }}>
                                <label for="activo">Activo</label>
                                <input type="radio" name="activo" value="0" id="inactivo" {{ $Usuario != null && $Usuario->activo == 0 ? "checked": "" }}>
                                <label for="inactivo">Inactivo</label>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-bold btn-pure btn-primary">{{ $Usuario != null ? "Modificar" : " Registrar" }} Usuario</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="auth/js/usuario/_Mantenimiento.min.js"></script>
