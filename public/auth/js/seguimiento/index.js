console.log("esto es el js de seguimiento");

actionAjax("/seguimiento/usuarios/", null, "GET", function(data){
    /* console.log("data de seguimiento : ", data); */
});

const $table = $("#tableSeguimiento");
const $dataTable = $table.DataTable({
    "stripeClasses": ['odd-row', 'even-row'],
    "lengthChange": true,
    "lengthMenu": [[10,25,100,200,500,-1],[10,25,100,200,500,"Todo"]],
    "info": false,
    "buttons": [],
    "ajax": {
        url: "/seguimiento/usuarios/"
    },
    "columns": [
       /*  { title: "ID", data: "id", className: "text-center" }, */
        { title: "Nombres", data: null, className: "text-center", render: function(data){
            return data.name+' '+data.last_name;
        } },
        { title: "Online", data: null, className: "text-center", render: function(data){
            if(data.online == 0){
                return "<p class='text-danger'>● OffLine</p>";
            }else if(data.online == 1){
                return "<p class='text-success'>● En Línea</p>";
            }
        } },
        { title: "Ultimo inicio de sesión", data: "inicio_sesion", className: "text-center" },
        { title: "Ultimo cierre de sesión", data: "cerrar_sesion", className: "text-center" },
        { title: "Creación de cuenta", data: "created_at", className: "text-center" }
    ]
});

function invocarVista(url, onHiddenView){
    $.ajax({
        url: url,
        type: "GET",
        dataType: "html",
        cache: false,
        success: function (data) {
            if (onHiddenView) onHiddenView(data);
        },
        beforeSend: function () {
            $("#loading").show();
        },
        complete: function () {
            $("#loading").hide();
        }
    });
}

function invocarModal(url, onHiddenModal) {
    $.ajax({
        url: url,
        type: "GET",
        dataType: "html",
        cache: false,
        success: function (data) {
            const $modal = $("<div class='parent'>").append(data);
            $modal.find(">.modal").on("hidden.bs.modal", function () {
                if (onHiddenModal) onHiddenModal($(this));
                $(this).parent().remove();
            });
            $modal.find(">.modal").modal("show");

            $("body").append($modal);
        },
        beforeSend: function () {
            $("#loading").show();
        },
        complete: function () {
            $("#loading").hide();
        }
    });
}

function onSuccessForm(data, $form, $modal, onSucess) {
    if($form != null)
        $form.find("span[data-valmsg-for]").text("");

    if (data.Success === true) {
        $form.trigger("reset");
        if($modal){$modal.attr("data-reload", "true");}
        swal("Bien!", data.Message ? data.Message : "Registro/Guardado Correctamente", "success");
        if ($modal) $modal.modal("hide");
        if (onSucess) onSucess(data);
    }else {
        if (data.Errors) {
            $.each(data.Errors,
                function (i, item) {
                    if($form != null) {
                        if ($form.find("span[data-valmsg-for=" + i + "]").length !== 0)
                            $form.find("span[data-valmsg-for=" + i + "]").text(item[0]);
                    }
                });
        }

        swal("Algo Salio Mal!", data.Message ? data.Message : "Verifique los campos ingresados", "error");
    }
}

function onFailureForm() {
    swal("Algo Salio Mal!", "Ocurrio un error al Guardar!!", "error");
}

function confirmAjax(url, parameters, type, msg, msgSuccess, onSuccess, onErrors) {
    swal({
        title: "Confirmación",
        text: msg ? msg : "¿ Está seguro ?",
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    },
        function () {
            actionAjax(url, parameters, type, onSuccess, true, msgSuccess, onErrors);
        });
}

function actionAjax(url, parameters, type, onSuccess, isToConfirm, msgSuccess, onErrors) {
    $.ajax({
        url: url,
        data: parameters,
        type: type != null ? type : "POST",
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
            if (isToConfirm === true) {
                if (data.Success === true) {
                    swal("Bien!", msgSuccess ? msgSuccess : "Proceso realizado Correctamente", "success");
                    if (onSuccess) onSuccess(data);
                } else {
                    if (onErrors) onSuccess(data);
                    else swal("Algo Salio Mal!", data.Message, "error");
                }
            } else {
                if (onSuccess) onSuccess(data);
            }
        },
        beforeSend: function () {
            if (isToConfirm !== true) $("#loading").show();
        },
        complete: function () {
            if (isToConfirm !== true) $("#loading").hide();
        }
    });
}

function createModal(title, body, onHidden) {
    const template = `<div id="myModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">${title}</h4>
                          </div>
                          <div class="modal-body">
                            ${body}
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                          </div>
                        </div>
                      </div>
                    </div>`;

    const $modal = $(template);
    $modal.on("hidden.bs.modal", function () {
        $modal.remove();
        if (onHidden) onHidden();
    });

    $modal.modal("show");
}

function getDate() {
    const now = new Date();
    const primerDia = new Date(now.getFullYear(), now.getMonth(), 1);
    const ultimoDia = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    return {
        Now: now,
        FirstDay: primerDia,
        LastDay: ultimoDia
    };
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if(charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function agregarCommaMillions(data) {
    var str = data.toString().split('.');
    if (str[0].length >= 4) {
        str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1,');
    }
    return str.join('.');
}

function getFormatDate(date) {
    const array = date.split("/");
    const f = new Date(array[2], array[1] - 1, array[0]);
    return f.format("yyyy-mm-dd");
}

function readImage(input, img) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }else{
        img.attr('src', '/auth/layout/img/default.gif');
    }
}