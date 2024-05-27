<div id="modalMantenimientoModalidad" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form enctype="multipart/form-data" action="{{ route('user.modalidad.store') }}" id="registroModalidad" method="POST"
              data-ajax="true" data-close-modal="true" data-ajax-loading="#loading" data-ajax-success="OnSuccessRegistroModalidad" data-ajax-failure="OnFailureRegistroModalidad">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $Modalidad != null ? "Modificar" : " Registrar" }} Modalidad</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ $Modalidad != null ? $Modalidad->id : 0 }}">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-input" name="name" value="{{ $Modalidad != null ? $Modalidad->name : "" }}" id="name" autocomplete="off" required>
                            <span data-valmsg-for="name"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-bold btn-pure btn-primary">{{ $Modalidad != null ? "Modificar" : " Registrar" }} Modalidad</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="auth/js/modalidad/_Mantenimiento.min.js"></script>
