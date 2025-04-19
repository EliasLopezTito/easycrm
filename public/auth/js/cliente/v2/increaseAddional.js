$(document).on('click', '.btn-editar', function () {
    const id = $(this).data('id');
    const clienteId = $(this).data('cliente');
    const formHtml = `
        <form id="edit-form-${id}" enctype="multipart/form-data">
            <input type="hidden" name="idClientAdditional" value="${id}">
            <input type="hidden" name="idClient" value="${clienteId}">
            <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                ${generateInputImgBlock('dniFront', 'Foto del DNI (Parte Frontal)')}
                ${generateInputImgBlock('dniRear', 'Foto del DNI (Parte Posterior)')}
                ${generateInputImgBlock('izyPay', 'Foto del IZYPAY')}
                ${generateInputImgBlock('vaucher', 'Foto del Comprobante de Pago')}
            </div>

            <label for="schoolNameUpdate" style="margin-top: 10px;">Nombre del Colegio</label>
            <input type="text" name="schoolNameUpdate" id="schoolNameUpdate" class="form-input">

            <label for="completionDateUpdate" style="margin-top: 10px;">Fecha de Termino</label>
            <input type="date" name="completionDateUpdate" id="completionDateUpdate" class="form-input">

            <br>
            <button type="button" class="btn btn-secondary mt-2 save-additional" data-id="${id}">Guardar</button>
        </form>
    `;
    $(`#form-${id}`).html(formHtml).slideDown();
});
function generateInputImgBlock(name, label) {
    return `
        <div class="col-lg-10 col-12">
            <div class="form-group">
                <label for="${name}Update" style="margin-top: 10px; font-weight: bold;">${label}</label>
                <input type="file" name="${name}Update" id="${name}Update" class="form-control" accept="image/png, image/jpeg, image/jpg">
            </div>
        </div>
        <div class="col-lg-2 col-12">
            <div class="checkbox input-delete-check" style="margin-top: 38px;">
                <label>
                    <input type="checkbox" name="${name}Delete" id="${name}Delete" value="1"> Eliminar Imagen
                </label>
            </div>
        </div>
    `;
}

$(document).on('click', '.save-additional', function () {
    const id = $(this).data('id');
    const container = $(`#form-${id}`);
    const inputs = container.find(':input');
    const formData = new FormData();
    formData.append('_token', csrfToken);
    inputs.each(function () {
        const input = $(this)[0];
        const name = $(this).attr('name');
        if (!name) return;
        if (input.type === 'file') {
            if (input.files.length > 0) {
                formData.append(name, input.files[0]);
            }
        } else if (input.type === 'checkbox') {
            if (input.checked) {
                formData.append(name, input.value);
            }
        } else {
            formData.append(name, $(this).val());
        }
    });
    $.ajax({
        url: uploadAdditional,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            Swal.fire({
                title: '¡Éxito!',
                text: 'Los datos se guardaron correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                location.reload();
            });
        },
        error: function (err) {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al guardar: ' + (err.responseJSON?.message || 'Por favor, inténtalo de nuevo.'),
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
});
