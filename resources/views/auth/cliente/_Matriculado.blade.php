<div id="modalMatriculadoCliente" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form enctype="multipart/form-data" action="{{ route('user.client.updateMatriculado') }}" id="matriculadoCliente" method="POST"
              data-ajax="true" data-close-modal="true" data-ajax-loading="#loading" data-ajax-success="OnSuccessMatriculadoCliente" data-ajax-failure="OnFailureMatriculadoCliente">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actualizar Matricula</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" value="{{ $Cliente->id }}" name="id" id="id">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="modalidad_id">Modalidad</label>
                            <select name="modalidad_id" class="form-input" id="modalidad_id" required>
                                <option value="">-- Modalidad --</option>
                                @foreach($Modalidades as $q)
                                    <option value="{{ $q->id }}" {{$Cliente->modalidad_id == $q->id ? "Selected" : ""}}>{{ $q->name }}</option>
                                @endforeach
                            </select>
                            <span data-valmsg-for="carrera_id"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="carrera_id">Carrera</label>
                            <select name="carrera_id" class="form-input" id="carrera_id" required>
                                <option value="">-- Carrera --</option>
                                @foreach($Carreras as $q)
                                    <option value="{{ $q->id }}" {{$Cliente->carrera_id == $q->id ? "Selected" : ""}}>{{ $q->name }}</option>
                                @endforeach
                            </select>
                            <span data-valmsg-for="carrera_id"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="turno_id">Turno</label>
                            <select name="turno_id" class="form-input" id="turno_id" required>
                                <option value="">-- Turno --</option>
                                @foreach($Turnos as $q)
                                    <option value="{{ $q->id }}" {{$Cliente->turno_id == $q->id ? "Selected" : ""}}>{{ $q->name }}</option>
                                @endforeach
                            </select>
                            <span data-valmsg-for="turno_id"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="horario_id">Horario</label>
                            <select name="horario_id" class="form-input" id="horario_id" required>
                                <option value="">-- Horario --</option>
                                @foreach($Horarios as $q)
                                    <option value="{{ $q->id }}" {{$Cliente->horario_id == $q->id ? "Selected" : ""}}>{{ $q->horario }}</option>
                                @endforeach
                            </select>
                            <span data-valmsg-for="horario_id"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-bold btn-pure btn-primary">Actualizar Matricula</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="auth/js/cliente/_Matriculado.min.js"></script>
