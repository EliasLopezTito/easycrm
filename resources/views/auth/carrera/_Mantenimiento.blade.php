<div id="modalMantenimientoCarrera" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form enctype="multipart/form-data" action="{{ route('user.carrera.store') }}" id="registroCarrera" method="POST"
              data-ajax="true" data-close-modal="true" data-ajax-loading="#loading" data-ajax-success="OnSuccessRegistroCarrera" data-ajax-failure="OnFailureRegistroCarrera">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $Carrera != null ? "Modificar" : " Registrar" }} Curso</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ $Carrera!= null ? $Carrera->id : 0 }}">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="modalidad_id">Modalidad</label>
                            <select name="modalidad_id" class="form-input"  id="modalidad_id">
                                <option value="">-- Seleccione --</option>
                                @foreach($Modalidades as $q)
                                    <option value="{{ $q->id }}" {{ $Carrera != null && $Carrera->modalidad_id == $q->id ? "selected" : "" }}>{{ $q->name }}</option>
                                @endforeach
                            </select>
                            <span data-valmsg-for="modalidad_id"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-input" name="name" value="{{ $Carrera != null ? $Carrera->name : "" }}" id="name" autocomplete="off" required>
                            <span data-valmsg-for="name"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-bold btn-pure btn-primary">{{ $Carrera != null ? "Modificar" : " Registrar" }} Curso</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="auth/js/carrera/_Mantenimiento.min.js"></script>
