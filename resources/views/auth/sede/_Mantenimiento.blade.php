<div id="modalMantenimientoSede" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form enctype="multipart/form-data" action="{{ route('user.sede.store') }}" id="registroSede" method="POST"
              data-ajax="true" data-close-modal="true" data-ajax-loading="#loading" data-ajax-success="OnSuccessRegistroSede" data-ajax-failure="OnFailureRegistroSede">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $Sede != null ? "Modificar" : " Registrar" }} Sede</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ $Sede != null ? $Sede->id : 0 }}">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-input" name="name" value="{{ $Sede != null ? $Sede->name : "" }}" id="name" autocomplete="off" required>
                            <span data-valmsg-for="name"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-bold btn-pure btn-primary">{{ $Sede != null ? "Modificar" : " Registrar" }} Sede</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="auth/js/sede/_Mantenimiento.min.js"></script>
