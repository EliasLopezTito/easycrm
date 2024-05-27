<div id="modalImportarCliente" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="width: 380px">
            <div class="modal-header">
                <h5 class="modal-title">Importar Leads</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="import_perfil_id">Perfil</label>
                        <select name="import_perfil_id" id="import_perfil_id" class="form-input">
                            @foreach($Vendedor as $q)
                                <option value="{{ $q->id }}">{{ $q->name }}</option>
                            @endforeach
                        </select>
                        <span data-valmsg-for="import_perfil_id"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="import_archivo_id">Archivo (.xslx)</label>
                        <input type="file" name="import_archivo_id" id="import_archivo_id" accept=".xlsx"  required>
                        <span data-valmsg-for="import_archivo_id"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-bold btn-pure btn-primary">Importar Excel</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="auth/js/cliente/_Importar.min.js"></script>
