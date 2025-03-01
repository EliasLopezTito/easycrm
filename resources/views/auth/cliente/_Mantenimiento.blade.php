<div id="modalMantenimientoCliente" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog">
        <form enctype="multipart/form-data" action="{{ route('user.client.store') }}" id="registroCliente" method="POST"
              data-ajax="true" data-close-modal="true" data-ajax-loading="#loading" data-ajax-success="OnSuccessRegistroCliente" data-ajax-failure="OnFailureRegistroCliente">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Lead</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="nombres">Nombres</label>
                                <input type="text" class="form-input" name="nombres" id="nombres" required>
                                <span data-valmsg-for="nombres"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="apellidos">Apellidos</label>
                                <input type="text" class="form-input" name="apellidos" id="apellidos" required>
                                <span data-valmsg-for="apellidos"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="email">Email</label>
                                <input type="email" class="form-input" name="email" id="email" required>
                                <span data-valmsg-for="email"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="dni">DNI</label>
                                <input type="text" class="form-input" name="dni" id="dni" minlength="8" maxlength="10" onkeypress="return isNumberKey(event)" required>
                                <span data-valmsg-for="dni"></span>
                            </div>
                            <div class="col-md-3">
                                <label for="celular">Celular</label>
                                <input type="text" class="form-input" name="celular" id="celular" minlength="9" maxlength="10" onkeypress="return isNumberKey(event)" required>
                                <span data-valmsg-for="celular"></span>
                            </div>
                            <div class="col-md-3">
                                <label for="whatsapp">whatsapp</label>
                                <input type="text" class="form-input" name="whatsapp" id="whatsapp" minlength="9" maxlength="10" onkeypress="return isNumberKey(event)">
                                <span data-valmsg-for="whatsapp"></span>
                            </div>
                            <div class="col-md-3">
                                <label for="fecha_nacimiento">Fecha Nacimiento</label>
                                <input type="date" class="form-input" name="fecha_nacimiento" id="fecha_nacimiento">
                                <span data-valmsg-for="fecha_nacimiento"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="provincia_id">Provincia</label>
                                <select name="provincia_id" class="form-input"  id="provincia_id" required>
                                        <option value="">-- Seleccione --</option>
                                    @foreach($Provincias as $q)
                                        <option value="{{ $q->id }}">{{ $q->name }}</option>
                                    @endforeach
                                </select>
                                <span data-valmsg-for="provincia_id"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="distrito_id">Distrito</label>
                                <select name="distrito_id" class="form-input"  id="distrito_id" required>
                                    <option value="">-- Seleccione --</option>
                                </select>
                                <span data-valmsg-for="distrito_id"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="modalidad_id">Modalidad</label>
                                <select name="modalidad_id" class="form-input"  id="modalidad_id" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($Modalidades as $q)
                                        <option value="{{ $q->id }}">{{ $q->name }}</option>
                                    @endforeach
                                </select>
                                <span data-valmsg-for="modalidad_id"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="carrera_id">Carrera o Curso</label>
                                <select name="carrera_id" class="form-input" id="carrera_id" required>
                                    <option value="">-- Seleccione --</option>
                                </select>
                                <span data-valmsg-for="carrera_id"></span>
                            </div>


                            @if(\Illuminate\Support\Facades\Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_ADMINISTRADOR)
                                <div class="col-md-4">
                                    <label for="fuente_id">Origen del Lead</label>
                                    <select name="fuente_id" class="form-input"  id="fuente_id" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($Fuentes as $q)
                                            <option value="{{ $q->id }}">{{ $q->name }}</option>
                                        @endforeach
                                    </select>
                                    <span data-valmsg-for="fuente_id"></span>
                                </div>
                            @else
                                <div class="col-md-4">
                                    <label for="fuente_id">Origen del Lead</label>
                                    <select name="fuente_id" class="form-input"  id="fuente_id" required>
                                        <option value="">-- Seleccione --</option>
                                        <option value="32">Presencial</option>
                                        <option value="25">Activaciones</option>
                                        <option value="4">Llamada</option>
                                        <option value="8">Tradicional</option>
                                        <option value="3">Recomendado</option>
                                        <option value="10">WhatsApp</option>
                                    </select>
                                    <span data-valmsg-for="fuente_id"></span>
                                </div>
                            @endif


                            @if(\Illuminate\Support\Facades\Auth::guard('web')->user()->profile_id == \easyCRM\App::$PERFIL_RESTRINGIDO)
                                <div class="col-md-4">
                                    <label for="ciclo_id">¿En qué ciclo te quedaste?</label>
                                    <select name="ciclo_id" class="form-input"  id="ciclo_id" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($Ciclos as $q)
                                            <option value="{{ $q->id }}">{{ $q->name }}</option>
                                        @endforeach
                                    </select>
                                    <span data-valmsg-for="ciclo_id"></span>
                                </div>
                            @else
                                <div class="col-md-4">
                                    <label for="enterado_id">¿Cómo te enteraste de Loayza?</label>
                                    <select name="enterado_id" class="form-input"  id="enterado_id" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($Enterados as $q)
                                            <option value="{{ $q->id }}">{{ $q->name }}</option>
                                        @endforeach
                                    </select>
                                    <span data-valmsg-for="enterado_id"></span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!----------------------------------------------------------------------------------------------->
                    @if(\Illuminate\Support\Facades\Auth::guard('web')->user()->profile_id == 1)
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="name_id">Asesor (Opcional)</label>
                                    <select name="name_id" class="form-input"  id="name_id">
                                            <option value="">-- Seleccione --</option>
                                            @foreach($Asesores as $q)
                                                <option value="{{ $q->id }}">{{ $q->name }}</option>
                                            @endforeach
                                        </select>
                                    <span data-valmsg-for="name_id"></span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!----------------------------------------------------------------------------------------------->

                    {{-- id usuario  --}}
                    <input type="hidden" name="creador_id" value="{{ \Illuminate\Support\Facades\Auth::guard('web')->user()->id }}">
                 
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-bold btn-pure btn-primary">Registrar Lead</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="auth/js/cliente/_Mantenimiento.min.js"></script>
