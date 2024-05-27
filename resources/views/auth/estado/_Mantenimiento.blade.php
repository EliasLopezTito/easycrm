<div id="modalMantenimientoEstado" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form enctype="multipart/form-data" action="{{ route('user.estado.store') }}" id="registroEstado" method="POST"
              data-ajax="true" data-close-modal="true" data-ajax-loading="#loading" data-ajax-success="OnSuccessRegistroEstado" data-ajax-failure="OnFailureRegistroEstado">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $Estado != null ? "Modificar" : " Registrar" }} Estado</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ $Estado != null ? $Estado->id : 0 }}">
                @if($Estado == null)
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 switch-field">
                            <div class="switch-title">Tipo Estado</div>
                            <input type="radio" name="tipo" value="0" id="principal" checked>
                            <label for="principal">Principal</label>
                            <input type="radio" name="tipo" value="1" id="detalle">
                            <label for="detalle">Detalle</label>
                        </div>
                    </div>
                </div>
                <div class="filter form-group hidden">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="estado_id">Estado Principal</label>
                            <select name="estado_id" class="form-input"  id="estado_id">
                                <option value="">-- Seleccione --</option>
                                @foreach($Estados as $q)
                                    <option value="{{ $q->id }}">{{ $q->name }}</option>
                                @endforeach
                            </select>
                            <span data-valmsg-for="estado_id"></span>
                        </div>
                    </div>
                </div>
                @else
                    <input type="hidden" id="tipo" name="tipo" value="{{ $Type }}" >
                    @if($Type)
                        <input type="hidden" id="estado_id" name="estado_id" value="{{ $Estado->estado_id }}" >
                    @endif
                @endif
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-input" name="name" value="{{ $Estado != null ? $Estado->name : "" }}" id="name" autocomplete="off" required>
                            <span data-valmsg-for="name"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-bold btn-pure btn-primary">{{ $Estado != null ? "Modificar" : " Registrar" }} Estado</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="auth/js/estado/_Mantenimiento.min.js"></script>
