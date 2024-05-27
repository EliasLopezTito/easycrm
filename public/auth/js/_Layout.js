const PERFILES = {
    ADMINISTRADOR: 1, VENDEDOR: 2, CALL: 3, REINGRESOS: 4
};

const MODALIDADES = {
    CARRERA: 1, CURSO: 2
}

const ESTADOS = {
    NUEVO: 1, SEGUIMIENTO: 2, OPORTUNUDAD: 3, CIERRE: 4, NO_CONTACTADO: 5, PERDIDO: 6, OTROS: 7, REINGRESO: 12, REASIGNADO: 13,
};

const ACCIONES = {
    TELEFONO: 1, CORREO: 2, WHATSAPP: 3, MENSAJE: 4
}

const ESTADOS_DETALLE = {
    MATRICULADO: 8, REINGRESO: 32
};

const CARRERAS = {
    SEMI_PRESENCIAL_PRIMEROS_AUXILIOS: 19, SEMI_MOVILIZACION_MASAJE: 20,
    SEMI_PRESENCIAL_INYECTABLES: 24, SEMI_PRESENCIAL_SATURA: 26
}

var pagination = false;

$(function () {

    if (window.location.pathname != "/") localStorage.setItem("cliente_id", "");
    $("#exportExcel").click(function () {
        invocarModal("/cliente/partialViewExport/");
    });

    $("#importExcel").click(function () {
        invocarModal("/cliente/partialViewImport/");
    });

    $("#notifications > a").click(function () {
        if (!$(this).parent().hasClass("show")) getNotifications();
    });

    $("#ModalCambiarPassword").on("click", function () {
        invocarModal("/changePassword/partialView");
    })

    function getNotifications() {
        $(".notifications-menu .dropdown-menu").html("<i class='fa fa-circle-o-notch fa-spin fa-2x fa-fw'></i>").addClass("text-center p-15");
        $.get("/cliente/notifications", function (data) {
            let html = '<li class="header">Tienes ' + data.data.length + (data.data.length > 1 ? ' notificaciones' : ' notificación') + '</li>';
            html += '<li><ul class="menu inner-content-div">';
            $.each(data.data, function (i, v) {
                let icon = "fa fa-circle-o"; let color = "";
                if (v.cliente_seguimientos) {
                    switch (v.cliente_seguimientos.accion_realizar_id) {
                        case ACCIONES.TELEFONO: icon = "fa fa-phone"; color = "text-success"; break;
                        case ACCIONES.CORREO: icon = "fa fa-envelope"; color = "text-warning"; break;
                        case ACCIONES.MENSAJE: icon = "fa fa-comment"; color = "text-info"; break;
                        case ACCIONES.WHATSAPP: icon = "fa fa-whatsapp"; color = "text-success"; break;
                    }
                    html += '<li><a href="#" data-info="info-' + v.clientes.id + '" class="card-detail-notification"><i class="fa ' + icon + ' ' + color + '"></i>' + v.cliente_seguimientos.accion_realizar.name + ' a ' + v.clientes.nombres + ' a las ' + v.cliente_seguimientos.hora_accion_realizar + '</a></li>';
                }
                if (v.user_id) {
                    html += '<li><a href="/' + v.data_export_format.path + '" target="_blank" data-info="info-' + v.data_export_format.file_name + '"  ><i class="fa text-success"></i>Exportación desde ' + v.data_export_format.fechaInicio + ' al ' + v.data_export_format.fechaFinal + '</a></li>';
                }
            });
            html += '</li></ul>';
            $(".notifications-menu .dropdown-menu").removeClass("text-center p-15").html("").append(html);
        });
    }
});

$("body").find("a").on("click", function () {
    localStorage.setItem("cliente_id", "");
});

$(document).on("click", "a.card-detail-notification", function () {
    const $id = $(this).attr("data-info").split("-")[1];
    localStorage.setItem("cliente_id", $id);
    window.location.href = "/";
});

try {
    window.addEventListener("submit", function (e) {
        const form = e.target;
        if (form.getAttribute("enctype") === "multipart/form-data") {
            if (form.dataset.ajax) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $(form).find("input[type=text]").each(function () {
                    if (this.inputmask)
                        this.inputmask._valueSet(this.inputmask.unmaskedvalue(), true);
                });
                const xhr = new XMLHttpRequest();
                xhr.open(form.method, form.action);

                xhr.addEventListener("loadend",
                    function () {
                        if (form.getAttribute("data-ajax-loading") !== null &&
                            form.getAttribute("data-ajax-loading") !== "")
                            document.getElementById(form.getAttribute("data-ajax-loading").substr(1)).style
                                .display = "none";

                        if (form.getAttribute("data-ajax-complete") !== null &&
                            form.getAttribute("data-ajax-complete") !== "")
                            window[form.getAttribute("data-ajax-complete")].apply(this, []);
                    });

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200)
                            window[form.getAttribute("data-ajax-success")].apply(this,
                                [JSON.parse(xhr.responseText)]);
                        else
                            window[form.getAttribute("data-ajax-failure")].apply(this, [xhr.status]);
                    }
                };

                const confirm = form.getAttribute("data-ajax-confirm");

                if (confirm) {
                    swal({
                        title: "Confirmacion",
                        text: confirm,
                        type: "info",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                        function () {
                            if (form.getAttribute("data-ajax-begin") !== null &&
                                form.getAttribute("data-ajax-begin") !== "")
                                window[form.getAttribute("data-ajax-begin")].apply(this, []);

                            xhr.send(new FormData(form));
                        });
                } else {
                    if (form.getAttribute("data-ajax-loading") !== null &&
                        form.getAttribute("data-ajax-loading") !== "")
                        document.getElementById(form.getAttribute("data-ajax-loading").substr(1)).style.display =
                            "block";

                    if (form.getAttribute("data-ajax-begin") !== null && form.getAttribute("data-ajax-begin") !== "")
                        window[form.getAttribute("data-ajax-begin")].apply(this, []);

                    xhr.send(new FormData(form));
                }
            }
        }
    }, true);
} catch (err) { console.log(err); }

try {

    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $.validator.setDefaults({
        ignore: []
        // other default options
    });

} catch (err) { console.log(err); }
$(function () {

    $(document).on("show.bs.modal", ".modal", function (event) {
        var zIndex = 1040 + (10 * $(".modal:visible").length);
        $(this).css("z-index", zIndex);
        setTimeout(function () {
            $(".modal-backdrop").not(".modal-stack").css("z-index", zIndex - 1).addClass("modal-stack");
        }, 0);

        $("body").css("overflow", "hidden");
    });

    $(document).on("hidden.bs.modal", ".modal", function (event) {
        if ($(".modal.fade.in").length === 0) {
            $("body").css("overflow", "auto");
        }
    });

    const swalclose = window.swal.close;
    const swal = window.swal;
    window.swal = function () {
        const previousWindowKeyDown = window.onkeydown;
        swal.apply(this, Array.prototype.slice.call(arguments, 0));
        window.onkeydown = previousWindowKeyDown;
    };
    window.swal.close = function () {
        swalclose.apply(this);
    };

    $(document).on("keyup keypress", "form", function (e) {
        const keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $.ajaxSetup({ cache: false });

});

function invocarVista(url, onHiddenView) {
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
    if ($form != null)
        $form.find("span[data-valmsg-for]").text("");

    if (data.Success === true) {
        $form.trigger("reset");
        if ($modal) { $modal.attr("data-reload", "true"); }
        swal("Bien!", data.Message ? data.Message : "Registro/Guardado Correctamente", "success");
        if ($modal) $modal.modal("hide");
        if (onSucess) onSucess(data);
    } else {
        if (data.Errors) {
            $.each(data.Errors,
                function (i, item) {
                    if ($form != null) {
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
    if (charCode > 31 && (charCode < 48 || charCode > 57))
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
        reader.onload = function (e) {
            img.attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        img.attr('src', '/auth/layout/img/default.gif');
    }
}


