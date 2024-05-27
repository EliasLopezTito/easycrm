<div id="modalMantenimientoEstadoDetalle" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Listado Detalle</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="estado_id" name="estado_id" value="{{ $Estado->id }}">
                <table id="tableEstadoDetalle" class="table table-bordered table-striped display nowrap margin-top-10 dataTable no-footer"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="auth/js/estado/_Mantenimiento_Detalle.min.js"></script>
