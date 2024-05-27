<div class="modal fade" id="modalRefreshOrderCargoMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Establecer Orden</h4>
            </div>

            <div class="modal-footer">
                <div class="col-md-12">
                    <div class="form-group dragging">
                        <ol class="serialization vertical">
                            @foreach ($cargos as $cargo )
                                <li data-id="{{ $cargo->id }}" data-name="{{ $cargo->name }}">
                                    {{ $cargo->name }}
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
                <button id="btnGuardar"  type="button" class="btn btn-primary pull-right" style="margin-bottom:5px" id="btnSubmit">Actualizar Orden</button>
            </div>
        </div>
    </div>
</div>

{{ Html::script('/auth/plugins/sortable/jquery-sortable.js') }}
<!-- MaintenenceJs -->
{{ Html::script('/auth/views/cargo/_RefreshOrder.js') }}
